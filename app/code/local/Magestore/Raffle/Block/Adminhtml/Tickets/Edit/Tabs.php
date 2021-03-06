<?php

class Magestore_Raffle_Block_Adminhtml_Tickets_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct(){
		parent::__construct();
		$this->setId('tickets_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('raffle')->__('Tickets Information'));
	}

	protected function _beforeToHtml(){
		$this->addTab('form_section', array(
			'label'	 => Mage::helper('raffle')->__('tickets Information'),
			'title'	 => Mage::helper('raffle')->__('tickets Information'),
			'content'	 => $this->getLayout()->createBlock('raffle/adminhtml_tickets_edit_tab_info')->toHtml().$this->getLayout()->createBlock('raffle/adminhtml_tickets_edit_tab_form')->toHtml(),
		));
		// $this->addTab('Prizes', array(
			// 'label'	 => Mage::helper('raffle')->__('Prizes'),
			// 'title'	 => Mage::helper('raffle')->__('Prizes'),
			// 'content'=> $this->getLayout()->createBlock('raffle/adminhtml_tickets_edit_tab_prizes')->toHtml(),
		// ));
		// $this->addTab('tickets', array(
			// 'label'	 => Mage::helper('raffle')->__('Tickets'),
			// 'title'	 => Mage::helper('raffle')->__('Tickets'),
			// 'content'=> $this->getLayout()->createBlock('raffle/adminhtml_tickets_edit_tab_tickets')->toHtml(),
		// ));
		// $this->addTab('randomNumber', array(
			// 'label'	 => Mage::helper('raffle')->__('Random Number'),
			// 'title'	 => Mage::helper('raffle')->__('Random Number'),
			// 'content'=> $this->getLayout()->createBlock('raffle/adminhtml_tickets_edit_tab_randnum')->toHtml(),
		// ));
		return parent::_beforeToHtml();
	}
}