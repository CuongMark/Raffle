<?php
class Magestore_Raffle_Block_Adminhtml_Tickets extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct(){
		$this->_controller = 'adminhtml_tickets';
		$this->_blockGroup = 'raffle';
		$this->_headerText = Mage::helper('raffle')->__('Tickets Manager');
		// $this->_addButtonLabel = Mage::helper('raffle')->__('Add Raffle');
		parent::__construct();
	}
}