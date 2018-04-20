<?php

class Magestore_Raffle_Block_Adminhtml_Tickets_Edit_Tab_Info extends Mage_Adminhtml_Block_Widget_Form {

    public function __construct() {
        $this->setTemplate('raffle/ticketinformation.phtml');
    }
    public function getTicket() {
        return Mage::registry('ticket_data');
    }
    public function getReadOnly(){
        return (Mage::registry('raffle_data')&&Mage::registry('raffle_data')->getStarted());
    }
	public function getWinnumbers(){
		$ticketId = Mage::registry('ticket_data')->getId();
		$winnumbers = Mage::getModel('raffle/randnum')->getCollection()->addFieldToFilter('ticket_id',$ticketId);
		$winnumbers->getSelect()
			->joinLeft(
				array('prizes'=>Mage::getModel('core/resource')->getTableName('raffle_prizes')),
				"main_table.prize_id = prizes.prize_id",                    array('price'=>'prizes.price')
			);
		return $winnumbers;
	}
}
