<?php

class Magestore_Raffle_Block_Adminhtml_Raffle_Edit_Tab_Rule extends Mage_Adminhtml_Block_Widget_Form {

    public function __construct() {
        $this->setTemplate('raffle/rule.phtml');
    }
    public function getRuleValues() {
        if(Mage::registry('raffle_data')->getId())
        return Mage::getModel('raffle/rule')->getCollection()
            ->addFieldToFilter('raffle_id',Mage::registry('raffle_data')->getId())
            ->addFieldToFilter('status',1);
    }
	public function getReadOnly(){
		return !(Mage::registry('raffle_data')&&!Mage::registry('raffle_data')->getIsGennerated());
	}
}
