<?php

class Magestore_Raffle_Block_Adminhtml_Raffle extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct(){
		$this->_controller = 'adminhtml_raffle';
		$this->_blockGroup = 'raffle';
		$this->_headerText = Mage::helper('raffle')->__('Raffle Manager');
		$this->_addButtonLabel = Mage::helper('raffle')->__('Add Raffle');
		parent::__construct();
	}
}