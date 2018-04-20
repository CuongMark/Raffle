<?php

class Magestore_Raffle_Model_Mysql4_Rule extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct(){
		$this->_init('raffle/rule', 'rule_id');
	}
}