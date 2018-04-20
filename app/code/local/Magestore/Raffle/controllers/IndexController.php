<?php

class Magestore_Raffle_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction(){
            if (!Mage::registry('current_category')) {
                $category = Mage::getModel('catalog/category')->load(Mage::app()->getStore()->getRootCategoryId())
                        ->setIsAnchor(1)
                        ->setName(Mage::helper('core')->__('Raffle'))
                        ->setDisplayMode('PRODUCTS');
                Mage::register('current_category', $category);
            }
            $this->loadLayout();
            $this->renderLayout();
	}
	public function ticketsAction(){
		$this->loadLayout();
		$this->renderLayout();
	}
	public function checkTicketAction(){
		if(!Mage::getSingleton('customer/session')->isLoggedIn())
			return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('success'=>false)));
		$ticketId = Mage::app()->getRequest()->getParam('id');
		/** @var Magestore_Raffle_Model_Tickets $win */
        $win = Mage::getModel('raffle/tickets')->load($ticketId);
        $win->gennerateRandNums();
		if($win->getStatus()==Magestore_Raffle_Model_Tickets::STATUS_WIN) {
            return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('success' => true, 'numbers' => $win->getWinNumbers())));
        }
		return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('success'=>false)));
	}
        public function finishedAction(){
            $this->loadLayout();
            $this->renderLayout();
        }
}