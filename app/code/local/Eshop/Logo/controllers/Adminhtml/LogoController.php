<?php

class Eshop_Logo_Adminhtml_LogoController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("logo/logo")->_addBreadcrumb(Mage::helper("adminhtml")->__("Logo  Manager"),Mage::helper("adminhtml")->__("Logo Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Logo"));
			    $this->_title($this->__("Manager Logo"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Logo"));
				$this->_title($this->__("Logo"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("logo/logo")->load($id);
				if ($model->getId()) {
					Mage::register("logo_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("logo/logo");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Logo Manager"), Mage::helper("adminhtml")->__("Logo Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Logo Description"), Mage::helper("adminhtml")->__("Logo Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("logo/adminhtml_logo_edit"))->_addLeft($this->getLayout()->createBlock("logo/adminhtml_logo_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("logo")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Logo"));
		$this->_title($this->__("Logo"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("logo/logo")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("logo_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("logo/logo");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Logo Manager"), Mage::helper("adminhtml")->__("Logo Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Logo Description"), Mage::helper("adminhtml")->__("Logo Description"));


		$this->_addContent($this->getLayout()->createBlock("logo/adminhtml_logo_edit"))->_addLeft($this->getLayout()->createBlock("logo/adminhtml_logo_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


		if ($post_data) {
		try {
					 //save image
			try{
				if((bool)$post_data['image']['delete']==1) {
					        $post_data['image']='';
				}
				else {
				
					unset($post_data['image']);
				
					if (isset($_FILES)){
				
					if ($_FILES['image']['name']) {
			
						if($this->getRequest()->getParam("id")){
							$model = Mage::getModel("logo/logo")->load($this->getRequest()->getParam("id"));
							if($model->getData('image')){
									$io = new Varien_Io_File();
									$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('image'))));	
							}
						}
									$path = Mage::getBaseDir('media') . DS . 'logo' . DS .'logo'.DS;
									$uploader = new Varien_File_Uploader('image');
									$uploader->setAllowedExtensions(array('jpg','png','gif','jpeg'));
									$uploader->setAllowRenameFiles(true);
									$uploader->setFilesDispersion(false);
									$destFile = $path.$_FILES['image']['name'];
									$filename = $uploader->getNewFileName($destFile);
									$uploader->save($path, $filename);
			
									$post_data['image']='logo/logo/'.$filename;
					}
			    }
			}

        } catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
        }
//save image


						$model = Mage::getModel("logo/logo")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Logo was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setLogoData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setLogoData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("logo/logo");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("logo/logo");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
}
