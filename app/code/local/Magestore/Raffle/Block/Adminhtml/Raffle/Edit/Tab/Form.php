<?php

class Magestore_Raffle_Block_Adminhtml_Raffle_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$this->setForm($form);
		
		if (Mage::getSingleton('adminhtml/session')->getRaffleData()){
			$data = Mage::getSingleton('adminhtml/session')->getRaffleData();
			Mage::getSingleton('adminhtml/session')->setRaffleData(null);
		}elseif(Mage::registry('raffle_data'))
			$data = Mage::registry('raffle_data');
                
		$fieldset = $form->addFieldset('raffle_form', array('legend'=>Mage::helper('raffle')->__('Raffle information')));
                $disabled = ($data->getStarted())?true:false;
		$fieldset->addField('name', 'text', array(
			'label'		=> Mage::helper('raffle')->__('Name'),
			'class'		=> 'required-entry',
			'required'	=> true,
			'disabled'      => $disabled,
			'name'		=> 'name',
			'note'          => ($data->getProductId())?'<a id="view_raffle_product" target="_blank" href="' . $this->getUrl('adminhtml/catalog_product/edit', array('id' => $data->getProductId())) . '">' . $this->__('View product information.') . '</a>':''
		));
                
		$fieldset->addField('prefix', 'text', array(
			'label'		=> Mage::helper('raffle')->__('Prefix'),
			'class'		=> 'required-entry',
			'required'	=> true,
			'name'		=> 'prefix',
            'disabled'      => $disabled,
		));
                
		$fieldset->addField('price', 'text', array(
			'label'		=> Mage::helper('raffle')->__('Price'),
			'class'		=> 'required-entry',
			'required'	=> true,
			'name'		=> 'price',
            'disabled'      => $disabled,
		));
                
		$fieldset->addField('total', 'text', array(
			'label'		=> Mage::helper('raffle')->__('Total'),
			'class'		=> 'required-entry',
			'required'	=> true,
			'name'		=> 'total',
                        'disabled'      => $disabled,
                        'note'          => Mage::helper('raffle')->__('Current Numbers is: '.$data->getCurrentNum()),
		));

		$fieldset->addField('status', 'select', array(
			'label'		=> Mage::helper('raffle')->__('Status'),
			'name'		=> 'status',
			'values'	=> Mage::getSingleton('raffle/status')->getOptionHash(),
                        'disabled'      => $disabled,
		));

		$fieldset->addField('description', 'editor', array(
			'name'		=> 'description',
			'label'		=> Mage::helper('raffle')->__('Description'),
			'title'		=> Mage::helper('raffle')->__('Description'),
			'style'		=> 'width:500px; height:100px;',
			'wysiwyg'	=> false,
			'required'	=> false,
		));

		$form->setValues($data);
		return parent::_prepareForm();
	}
}