<?php

class Magestore_Raffle_Model_Mysql4_Ticket extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct(){
		$this->_init('raffle/ticket', 'customer_raffle_id');
	}
}