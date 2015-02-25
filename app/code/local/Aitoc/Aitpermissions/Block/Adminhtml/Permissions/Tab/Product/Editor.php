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
class Aitoc_Aitpermissions_Block_Adminhtml_Permissions_Tab_Product_Editor extends Mage_Adminhtml_Block_Catalog_Category_Tree
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('aitpermissions/product/editor.phtml');
    }

    protected function _prepareLayout()
    {
        $this->setChild('tabs', $this->getLayout()->createBlock('aitpermissions/adminhtml_permissions_tab_product_editor_tabs', 'productEditorTabs'));
        $this->setChild('attribute', $this->getLayout()->createBlock('aitpermissions/adminhtml_permissions_tab_product_editor_attribute', 'productEditorAttribute'));
        return $this;
    }
}