<?php
/*******************************************************/
/***********  Created By : GIRISH ANAND   **************/
/*** For any query mail at girish.anand85@gmail.com ****/
/*******************************************************/
?>
<?php
class GA_Slide_Block_Adminhtml_Slide extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_slide';
    $this->_blockGroup = 'slide';
    $this->_headerText = Mage::helper('slide')->__('Slider Manager');
    $this->_addButtonLabel = Mage::helper('slide')->__('Add Slide');
    parent::__construct();
  }
}