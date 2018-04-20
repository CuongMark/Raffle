<?php

class Magestore_Raffle_Model_Ticket extends Mage_Core_Model_Abstract
{
	public function _construct(){
		parent::_construct();
		$this->_init('raffle/ticket');
	}
	public function setWinNumbers(){
		$winNumber = Mage::getModel('raffle/randnumbers')->getCollection()
			->addFieldToFilter('raffle_id',$this->getData('raffle_id'))
			->addFieldToFilter('raffle_randomnum',array('gteq'=>(int)$this->getData('raffle_ticketnum_start')))
			->addFieldToFilter('raffle_randomnum',array('lteq'=>(int)$this->getData('raffle_ticketnum_end')));
		$array = array();
		$win = false;
		foreach($winNumber as $_number){
			$_number->setData('customer_id',$this->getCustomerId())
				->setData('customer_email',$this->getCustomerEmail())
				->save();
			$array[] = $_number->getData('raffle_randomnum');
			$win = true;
		}
		if($win)
			$this->setStatus(1)->save();
		else $this->setStatus(2)->save();
		return $array;
	}
	public function getWinNumbers(){
		$ruleId = array();
		$rules = Mage::getModel('raffle/rule')->getCollection()
					->addFieldToFilter('raffle_id',$this->getData('raffle_id'));
		foreach($rules as $_rule){
			$ruleId[] = $_rule->getId();
		}
		$winNumber = Mage::getModel('raffle/randnumbers')->getCollection()
			->addFieldToFilter('rule_id',$this->getData('raffle_id'))
			->addFieldToFilter('raffle_randomnum',array('gteq'=>(int)$this->getData('raffle_ticketnum_start')))
			->addFieldToFilter('raffle_randomnum',array('lteq'=>(int)$this->getData('raffle_ticketnum_end')));
			
		// $winNumber->getSelect()
			// ->columns('rule.raffle_id as raffle_id1')
			// ->join(
				// array('rule'=>Mage::getModel('core/resource')->getTableName('raffle_rule')),
				// "e.rule_id = rule.rule_id",
				// array(
					// 'price' => 'rule.price',
				// )
			// );
		return $winNumber;
	}
}