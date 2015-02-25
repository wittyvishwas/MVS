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
class Aitoc_Aitpermissions_Block_Rewrite_AdminCmsBlockEditForm extends Mage_Adminhtml_Block_Cms_Block_Edit_Form
{
    protected function _prepareForm()
    {
        parent::_prepareForm();
        
        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            // if page is not assigned to any store views but permitted, will allow to delete and disable it
            $blockModel = Mage::registry('cms_block');
            if ($blockModel->getStoreId() && is_array($blockModel->getStoreId()))
            {
                foreach ($blockModel->getStoreId() as $blockStoreId)
                {
                    if (!in_array($blockStoreId, $role->getAllowedStoreviewIds()))
                    {
                        $fieldset = $this->getForm()->getElement('base_fieldset');
                        $fieldset->removeField('is_active');
                        break 1;
                    }
                }
            }
        }
        
        return $this;
    }
}