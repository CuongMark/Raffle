<?php

class Magestore_Raffle_Model_Randnumbers extends Mage_Core_Model_Abstract
{
	public function _construct(){
		parent::_construct();
		$this->_init('raffle/randnumbers');
	}
}