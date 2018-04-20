<?php

class Magestore_Raffle_Adminhtml_TicketsController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction(){
		$this->loadLayout()
			->_setActiveMenu('raffle/tickets')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Tickets Manager'), Mage::helper('adminhtml')->__('Tickets Manager'));
		return $this;
	}
 
	public function indexAction(){
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id	 = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('raffle/tickets')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data))
				$model->setData($data);

			Mage::register('ticket_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('raffle/tickets');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Ticket Manager'), Mage::helper('adminhtml')->__('Ticket Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Ticket News'), Mage::helper('adminhtml')->__('Ticket News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()->createBlock('raffle/adminhtml_tickets_edit'))
				->_addLeft($this->getLayout()->createBlock('raffle/adminhtml_tickets_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('raffle')->__('Tickets does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			$model = Mage::getModel('raffle/raffle');		
			$model->setData($data)
                                ->setId($this->getRequest()->getParam('id'));
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL)
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				else
					$model->setUpdateTime(now());
				if ($this->getRequest()->getParam('apply')) {
                                    $model->createProduct();
                                    $model->setStatus(2);
                                }
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('raffle')->__('Raffle was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
                                if($data['option']){
                                    $model->removeOldRules()->setNewRules($data['option']);
                                }
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('raffle')->__('Unable to find Raffle to save'));
		$this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('raffle/raffle');
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Raffle was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	public function massDeleteAction() {
		$raffleIds = $this->getRequest()->getParam('raffle');
		if(!is_array($raffleIds)){
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Raffle(s)'));
		}else{
			try {
				foreach ($raffleIds as $raffleId) {
					$raffle = Mage::getModel('raffle/raffle')->load($raffleId);
					$raffle->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($raffleIds)));
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
	
	public function massStatusAction() {
		$raffleIds = $this->getRequest()->getParam('raffle');
		if(!is_array($raffleIds)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Raffle(s)'));
		} else {
			try {
				foreach ($raffleIds as $raffleId) {
					$raffle = Mage::getSingleton('raffle/raffle')
						->load($raffleId)
						->setStatus($this->getRequest()->getParam('status'))
						->setIsMassupdate(true)
						->save();
				}
				$this->_getSession()->addSuccess(
					$this->__('Total of %d record(s) were successfully updated', count($raffleIds))
				);
			} catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
  
	public function exportCsvAction(){
		$fileName   = 'raffle.csv';
		$content	= $this->getLayout()->createBlock('raffle/adminhtml_tickets_grid')->getCsv();
		$content = strip_tags($content);
		$this->_prepareDownloadResponse($fileName,$content);
	}

	public function exportXmlAction(){
		$fileName   = 'raffle.xml';
		$content	= $this->getLayout()->createBlock('raffle/adminhtml_tickets_grid')->getXml();
		$this->_prepareDownloadResponse($fileName,$content);
	}
}