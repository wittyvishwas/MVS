<?php
/**
 * Advanced Permissions
 *
 * @category:    Aitoc
 * @package:     Aitoc_Aitpermissions
 * @version      2.9.3
 * @license:     NsnkcMilFb7W0iFXa17c232AskjWauIUC7wI4CNyQ3
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
class Aitoc_Aitpermissions_Block_Rewrite_AdminSystemConfigSwitcher
    extends Mage_Adminhtml_Block_System_Config_Switcher
{
    public function getStoreSelectOptions()
    {
        $options = parent::getStoreSelectOptions();

        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            unset($options['default']);
        }

        if ($role->isScopeStore())
        {
            $currentStore = Mage::getModel('core/store')->load($this->getRequest()->getParam('store'), 'code')->getId();
            $allowedStoreviewIds = $role->getAllowedStoreviewIds();

            if (!in_array($currentStore, $allowedStoreviewIds))
            {
                $storeViewId = $allowedStoreviewIds[0];
				
                $referer = $_SERVER['HTTP_REFERER'];
                $pos = strpos($referer, 'system_config');
                
                if($pos!='')
                {
                	//do nothing
                }
                else
                {
                	// redirecting to first allowed store
                	$url = Mage::getModel('adminhtml/url');
                	$storeView = Mage::getModel('core/store')->load($storeViewId);
                	$website = Mage::getModel('core/website')->load($storeView->getWebsiteId());
               		Mage::app()->getResponse()->setRedirect($url->getUrl('*/*/*', array('store' => $storeView->getCode(), 'website' => $website->getCode())));
                
                }
                
                
                
                //$websiteId = $storeView->getWebsiteId();
                //$url = Mage::getModel('adminhtml/url');
                //$website = Mage::getModel('core/website')->load($websiteId);
               // echo $url->getUrl('*/*/*', array('website' => $website->getCode())); die;
                //Mage::app()->getResponse()->setRedirect($url->getUrl('*/*/*', array('website' => $website->getCode())));
                
                
            }
        }

        if ($role->isScopeWebsite())
        {
            $currentWebsite = Mage::getModel('core/website')->load($this->getRequest()->getParam('website'), 'code')->getId();
            $allowedWebsites = $role->getAllowedWebsiteIds();

            if (!in_array($currentWebsite, $allowedWebsites))
            {
                $websiteId = $allowedWebsites[0];

                // redirecting to first allowed website
                $url = Mage::getModel('adminhtml/url');
                $website = Mage::getModel('core/website')->load($websiteId);

                Mage::app()->getResponse()->setRedirect($url->getUrl('*/*/*', array('website' => $website->getCode())));
            }
        }

        return $options;
    }
    
    protected function _afterToHtml($html)
    {
        $role = Mage::getSingleton('aitpermissions/role');
        if (Mage::getSingleton('aitpermissions/role')->isPermissionsEnabled())
        {
        	$allowedStoreviewIds = Mage::getSingleton('aitpermissions/role')->getAllowedStoreviewIds();
            if (count($allowedStoreviewIds) <= 1 && $role->isScopeStore())
            {
                return '';
            }
        }
        
        return $html;
    }
}