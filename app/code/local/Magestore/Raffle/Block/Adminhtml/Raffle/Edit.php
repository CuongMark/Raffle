<?php

class Magestore_Raffle_Block_Adminhtml_Raffle_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct(){
		parent::__construct();
		
		$this->_objectId = 'id';
		$this->_blockGroup = 'raffle';
		$this->_controller = 'adminhtml_raffle';
		
		$this->_updateButton('save', 'label', Mage::helper('raffle')->__('Save Raffle'));
		$this->_updateButton('delete', 'label', Mage::helper('raffle')->__('Delete Raffle'));
		
		$this->_addButton('saveandcontinue', array(
			'label'		=> Mage::helper('adminhtml')->__('Save And Continue Edit'),
			'onclick'	=> 'saveAndContinueEdit()',
			'class'		=> 'save',
		), -100);

		$this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('raffle_content') == null)
					tinyMCE.execCommand('mceAddControl', false, 'raffle_content');
				else
					tinyMCE.execCommand('mceRemoveControl', false, 'raffle_content');
			}

			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
			function saveAndApply(){
				editForm.submit($('edit_form').action+'back/edit/apply/yes');
			}
		";
                if(!(Mage::registry('raffle_data') && Mage::registry('raffle_data')->getId()&&Mage::registry('raffle_data')->getStatus()!=1)){
                    $this->_addButton('saveAndApply', array(
                            'label' => Mage::helper('adminhtml')->__('Save and Apply'),
                            'onclick' => 'saveAndApply()',
                            'class' => 'add',
                    ), 0);
                }
	}

	public function getHeaderText(){
		if(Mage::registry('raffle_data') && Mage::registry('raffle_data')->getId())
			return Mage::helper('raffle')->__("Edit Raffle '%s'", $this->htmlEscape(Mage::registry('raffle_data')->getName()));
		return Mage::helper('raffle')->__('Add Raffle');
	}
}