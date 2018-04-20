<?php
class Magestore_Raffle_Block_Adminhtml_Raffle_Renderer_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row){
        $customerId = $row->getCustomerId();
        $customer = Mage::getModel('customer/customer')->load($customerId);
        if($customer){
            return '<a href="'.Mage::helper("adminhtml")->getUrl('adminhtml/customer/edit',array('id'=>$customerId)).'" >'.$customer->getFirstname().' '.$customer->getMiddlename().' '.$customer->getLastname().'</a>';
        }
        return '';
    }
}