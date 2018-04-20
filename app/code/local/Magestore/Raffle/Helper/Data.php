<?php

class Magestore_Raffle_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getProductRaffleIds() {
            $array = array();
            $raffles = Mage::getModel('raffle/raffle')->getCollection()
                    ->addFieldToFilter('status',2);
            foreach ($raffles as $_raffle) {
                $array[] = $_raffle->getProductId();
            }
            return $array;
        }
        public function getRaffleByProduct($productId) {
            $raffles = Mage::getModel('raffle/raffle')->getCollection()
                    ->addFieldToFilter('status',2)
                    ->addFieldToFilter('product_id',$productId)
                    ->getFirstItem();
            return $raffles;
        }
}