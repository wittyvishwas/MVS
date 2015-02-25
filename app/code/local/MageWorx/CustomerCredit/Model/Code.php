<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_CustomerCredit_Model_Code extends Mage_Core_Model_Abstract
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const USED_YES = 1;
    const USED_NO = 0;

    protected $_eventPrefix	= 'customercredit_code';
    protected $_eventObject  = 'code';

    protected function _construct() {
        $this->_init('customercredit/code');
    }

    public function loadByCode($code) {
        return $this->load($code, 'code');
    }
    
    /**
     * Validates data for code
     * @param Varien_Object $object
     * @returns boolean|array - returns true if validation passed successfully. Array with error
     * description otherwise
     */
    public function validateData(Varien_Object $object) {
        if($object->getData('from_date') && $object->getData('to_date')){
            $dateStartUnixTime = strtotime($object->getData('from_date'));
            $dateEndUnixTime   = strtotime($object->getData('to_date'));

            if ($dateEndUnixTime < $dateStartUnixTime) {
                return array(Mage::helper('rule')->__("End Date should be greater than Start Date"));
            }
        }
        return true;
    }
    
    /**
     * Load post data
     * @param array $codeData
     */
    public function loadPost(array $codeData) {
        if (!empty($codeData['details'])) {
            foreach ($codeData['details'] as $key => $value) {
                /**
                 * convert dates into Zend_Date
                 */
                if (in_array($key, array('from_date', 'to_date')) && $value) {
                    $value = Mage::app()->getLocale()->date(
                        $value,
                        Varien_Date::DATE_INTERNAL_FORMAT,
                        null,
                        false
                    );
                }
                $this->setData($key, $value);
            }
        }
        if ($this->getIsNew() && array_key_exists('settings', $codeData)) {
            $dataSettings = $codeData['settings'];
            if (array_key_exists('use_config', $codeData) && is_array($codeData['use_config'])) {
                foreach ($codeData['use_config'] as $settingCode=>$value) {
                    $dataSettings[$settingCode] = null;
                }
            }
            $this->setData('generate', $dataSettings);
        }
    }
    
    /**
     * 
     * @return object
     */
    protected function _beforeSave() {            
        if ($this->getIsNew()) {
            $this->_defineCode();
        } else {
            if (!$this->getIsUsed()) {
                $this->setUsedDate(now());
                $this->getLogModel()->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId());
                $this->getLogModel()->setActionType(MageWorx_CustomerCredit_Model_Code_Log::ACTION_TYPE_USED);
            }
        }
        
        //set log data
        if ($this->getIsNew()) {
            $this->getLogModel()->setActionType(MageWorx_CustomerCredit_Model_Code_Log::ACTION_TYPE_CREATED);
        } elseif (!$this->getLogModel()->hasActionType()) {
            $this->getLogModel()->setActionType(MageWorx_CustomerCredit_Model_Code_Log::ACTION_TYPE_UPDATED);
        }
        $this->getLogModel()->setCredit($this->getCredit());
        return parent::_beforeSave();
    }

    protected function _afterSave()
    {
        parent::_afterSave();
        $this->getLogModel()->unsetData();
    }

    /**
     * Generate new codes
     */
    public function generate()
    {
        $qty = (int)$this->getData('generate', 'qty');
        $startData = $this->getData();
        for ($i = 0; $i < $qty; $i++) {
            $this->setData($startData);
            $this->unsetData($this->getIdFieldName());
            $this->save();
        }
    }

    /**
     * Define Code
     * @return MageWorx_CustomerCredit_Model_Code
     */
    protected function _defineCode() {
        if($this->getCode()) return $this;

        $codeGenerator = Mage::getSingleton('customercredit/code_generator');
        /* @var $codeGenerator MageWorx_CustomerCredit_Model_Code_Generator */

        $generate = $this->getGenerate();
        if (is_array($generate)) {
            foreach ($generate as $setting => $value) {
                if (is_null($value)) {
                    $generate[$setting] = Mage::getStoreConfig('mageworx_customers/customercredit_recharge_codes/'.$setting);
                }
            }
        }
        $codeGenerator->setLength((int)$generate['code_length'])
            ->setBlockSize((int)$generate['group_length'])
            ->setBlockSeparator((string)$generate['group_separator']);

        switch ($generate['code_format']) {
            default:
            case 'num':
                $codeGenerator->setUseNumbers(true)
                    ->setUseBig(false)
                    ->setUseSmall(false);
                break;
            case 'alphanum':
                $codeGenerator->setUseNumbers(true)
                    ->setUseBig(true)
                    ->setUseSmall(false);
                break;
            case 'alphabet':
                $codeGenerator->setUseNumbers(false)
                    ->setUseBig(true)
                    ->setUseSmall(false);
                break;
        }    
        $generatedCode = $codeGenerator->generate();
        $this->setCode($generatedCode); 
        return $this;
    }

    /**
     * Check if code was used
     * @return boolean
     */
    public function isUsed() {
       $collection = $this->getLogModel()->getCollection();
       $collection->getSelect()
               ->where('code_id=?',$this->getId())
               ->where('action_type=?',MageWorx_CustomerCredit_Model_Code_Log::ACTION_TYPE_USED)
               ->where('customer_id=?',Mage::getSingleton('customer/session')->getCustomerId());//->__toString();
        if($collection->getSize()) {
            return true;
        }
        return false;
    }

    /**
     * Check is code active
     * @return boolean
     */
    public function isActive(){
        $curDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATE_INTERNAL_FORMAT);
        $fromDate = $this->getFromDate();
        $toDate   = $this->getToDate();

        if ($fromDate && $curDate < $fromDate) return false;
        if ($toDate && $curDate > $toDate) return false;

        return true;
    }

    /**
     * check Website
     * @param int $websiteId
     * @return boolean
     */
    public function checkWebsite($websiteId = null) {
        $websiteId = Mage::app()->getWebsite($websiteId)->getId();
        return $this->getWebsiteId() == $websiteId;
    }

    /**
     * 
     * @return boolean
     */
    public function checkCredit()
    {
        return $this->getCredit() > 0;
    }

    
    public function isValidForUse()
    {
        if (!$this->isActive() || $this->isUsed() || !$this->checkWebsite() || !$this->checkCredit()) {
            Mage::throwException(Mage::helper('customercredit')->__('Recharge Code is invalid.'));
        }
        return true;
    }

    /**
     * Possible depricated
     */
    public function isValidForUpdateByAdmin() {}

    
    public function isDeletable() {
        return true;
    }

    /**
     * Use code
     * @return MageWorx_CustomerCredit_Model_Code
     */
    public function useCode() {
        if (!$this->isValidForUse())
        return $this;

        if (!($customerId = Mage::getSingleton('customer/session')->getCustomerId())) {
            Mage::throwException(Mage::helper('customercredit')->__('Customer ID is not set'));
        }
        $this->setCustomerId($customerId);

        $credit = Mage::getModel('customercredit/credit')->refill($this);

        if($this->getIsOnetime()) {
            $this->setCredit(0)
                ->setIsUsed(true)
                ->setIsActive(0);
        }
        $this->save();
    }

    /**
     * Retreive log model instance
     * @return MageWorx_CustomerCredit_Model_Code_Log
     */
    public function getLogModel(){
        if (!$this->hasData('log_model')) {
            $this->setLogModel(Mage::getModel('customercredit/code_log'));
        }
        return $this->getData('log_model');
    }
}