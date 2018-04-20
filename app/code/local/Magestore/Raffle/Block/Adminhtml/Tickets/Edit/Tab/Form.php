<?php

class Magestore_Raffle_Block_Adminhtml_Tickets_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$this->setForm($form);
		
		if (Mage::getSingleton('adminhtml/session')->setTicketData()){
			$data = Mage::getSingleton('adminhtml/session')->setTicketData();
			Mage::getSingleton('adminhtml/session')->setTicketData(null);
		}elseif(Mage::registry('ticket_data'))
			$data = Mage::registry('ticket_data');
                
		$fieldset = $form->addFieldset('raffle_form', array('legend'=>Mage::helper('raffle')->__('Tickets information')));
		$disabled = ($data->getStarted())?true:false;
		$fieldset->addField('payout', 'text', array(
			'label'		=> Mage::helper('raffle')->__('Payout'),
			'class'		=> 'required-entry',
			'required'	=> true,
			'disabled'  => $disabled,
			'name'		=> 'payout',
		));
		$form->setValues($data);
		return parent::_prepareForm();
	}
}