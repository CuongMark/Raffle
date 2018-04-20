<?php

class Magestore_Raffle_Model_Mysql4_Prizes extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct(){
		$this->_init('raffle/prizes', 'prize_id');
	}
}