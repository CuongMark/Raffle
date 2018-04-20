<?php

class Magestore_Raffle_Model_Mysql4_Tickets extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct(){
		$this->_init('raffle/tickets', 'ticket_id');
	}
}