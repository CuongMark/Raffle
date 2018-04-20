<?php

class Magestore_Raffle_Model_Mysql4_Randnum extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct(){
		$this->_init('raffle/randnum', 'rand_number_id');
	}
}