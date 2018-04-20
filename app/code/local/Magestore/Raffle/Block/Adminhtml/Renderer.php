<?php
class Magestore_Raffle_Block_Adminhtml_Renderer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{	
	public function render(Varien_Object $row){
		return Mage::registry('raffle_data')->getData('raffle_prefix').' '.$row->getData('raffle_randomnum');
	}
}