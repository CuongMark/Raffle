<?php

class Magestore_Raffle_Block_Raffle extends Mage_Core_Block_Template
{
	public function _prepareLayout(){
		return parent::_prepareLayout();
	}
    public function addToTopLink() {
        $topBlock = $this->getParentBlock();
        if ($topBlock) {
            $topBlock->addLink($this->__('Raffle'), 'raffle', 'raffle', true, array(), 10);
        }
    }

	public function getCustomerTickets(){
        if(!Mage::getSingleton('customer/session')->isLoggedIn())
            return null;
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $tickets = Mage::getModel('raffle/tickets')
            ->getCollection()
            ->addFieldToFilter('main_table.customer_id',$customer->getId())
            ->setOrder('ticket_id','DESC');
        $tickets->getSelect()->joinLeft(array('raffle'=>Mage::getSingleton('core/resource')->getTableName('raffle')),'main_table.raffle_id = raffle.raffle_id',array('name'=>'raffle.name','product_id'=>'raffle.product_id','price'=>'raffle.price * (main_table.num_end - main_table.num_start +1)'));
        $tickets->getSelect()->joinLeft(array('order'=>Mage::getSingleton('core/resource')->getTableName('sales_flat_order')),'main_table.order_id = order.entity_id',array('increment_id'=>'order.increment_id'));
        $tickets->getSelect()->joinLeft(array('prize'=>Mage::getSingleton('core/resource')->getTableName('raffle_win_number')),'main_table.order_id = order.entity_id',array('increment_id'=>'order.increment_id'));
        return $tickets;
	}
	public function getFinishedRaffle(){
		return Mage::getModel('raffle/raffle')
                ->getCollection()
                ->addFieldToFilter('status',3);
	}
	public function isRaffleProduct($productId = null){
		if(Mage::registry('current_product'))
			$productId = Mage::registry('current_product')->getId();
		return Mage::getModel('raffle/raffle')
                ->getCollection()
                ->addFieldToFilter('product_id',$productId)
				->getSize();
	}
}