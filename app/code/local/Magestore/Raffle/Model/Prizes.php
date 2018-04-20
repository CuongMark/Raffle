<?php

class Magestore_Raffle_Model_Prizes extends Mage_Core_Model_Abstract
{
	public function _construct(){
		parent::_construct();
		$this->_init('raffle/prizes');
	}
	public function getWinnumbers(){
		return Mage::getModel('raffle/randnum')->getCollection()
                ->addFieldToFilter('prize_id',$this->getId());
	}
	
}