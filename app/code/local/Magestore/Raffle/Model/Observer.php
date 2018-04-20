<?php

class Magestore_Raffle_Model_Observer {

    public function getlink() {
        $link = Mage::app()->getRequest()->getRouteName() .
            Mage::app()->getRequest()->getControllerName() .
            Mage::app()->getRequest()->getActionName() .
            Mage::app()->getRequest()->getModuleName();
        return $link;
    }

    public function catalog_product_collection_apply_limitations_before($observer) {
        if (!Mage::registry('load_list_raffle')) {
            Mage::register('load_list_raffle', '1');
            if ($this->getlink() != 'raffleindexindexraffle')
                return $this;
            $productCollection = $observer['collection'];
            $productCollection->addFieldToFilter('entity_id', array('in' => $Ids = Mage::helper('raffle')->getProductRaffleIds(Mage::app()->getStore()->getId())));
            return $this;
        }
    }
    public function sales_order_save_after($observer){
        $orderStateActivePackage = Mage::getStoreConfig('raffle/general/create_tickets_when_state_order');
        //get the current order
        $order = $observer->getEvent()->getOrder();
        //get the customer id in the order
        $customerId = $order->getCustomerId();
        $helper = Mage::helper('raffle');
        foreach ($order->getAllVisibleItems() as $item) {
            $productId = $item->getProductId();
            $raffle = $helper->getRaffleByProduct($productId);
            if($order->getStatus() == $orderStateActivePackage&&$raffle->getId()){
                $raffle->addTickets($customerId, $item->getQtyOrdered(),$order->getId());
                Mage::getSingleton('checkout/session')->setData('bought_tickets','1');
            }				
        }
    }
    public function addToTopmenu(Varien_Event_Observer $observer){
        $display = 1;
        if($display){
            try{
                $menu = $observer->getMenu();
                $tree = $menu->getTree();
                $node = new Varien_Data_Tree_Node(array(
                        'name'   => __('Raffle'),
                        'id'     => 'raffle',
                        'url'    => Mage::app()->getStore()->getUrl('raffle'), 
                ), 'id', $tree, $menu);
                $menu->addChild($node);
				$tree = $node->getTree();
				$data = array(
					'name'   => 'Finished Raffles',
					'id'     => 'finished_raffle',
					'url'    => Mage::app()->getStore()->getUrl('raffle/index/finished'),
				);
				$subNode = new Varien_Data_Tree_Node($data, 'id', $tree, $node);
                $node->addChild($subNode);
            } catch (Exception $e) {
            
            }
        }
    }
}