<?php
/*******************************************************/
/***********  Created By : GIRISH ANAND   **************/
/*** For any query mail at girish.anand85@gmail.com ****/
/*******************************************************/
?>
<?php
class GA_Slide_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	$this->loadLayout();     
		$this->renderLayout();
    }
}