<?php

class Magestore_Raffle_Block_Adminhtml_Tickets_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct(){
		parent::__construct();
		
		$this->_objectId = 'id';
		$this->_blockGroup = 'raffle';
		$this->_controller = 'adminhtml_tickets';
		$this->_updateButton('save', 'label', Mage::helper('raffle')->__('Save Ticket'));
		// $this->_updateButton('delete', 'label', Mage::helper('raffle')->__('Delete Raffle'));
		
		$this->_addButton('saveandcontinue', array(
			'label'		=> Mage::helper('adminhtml')->__('Save And Continue Edit'),
			'onclick'	=> 'saveAndContinueEdit()',
			'class'		=> 'save',
		), -100);

		$this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('tickets_content') == null)
					tinyMCE.execCommand('mceAddControl', false, 'tickets_content');
				else
					tinyMCE.execCommand('mceRemoveControl', false, 'tickets_content');
			}

			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}

	public function getHeaderText(){
		if(Mage::registry('tickets_data') && Mage::registry('tickets_data')->getId())
			return Mage::helper('raffle')->__("Edit tickets '%s'", $this->htmlEscape(Mage::registry('tickets_data')->getName()));
		return Mage::helper('raffle')->__('Add Ticket');
	}
}