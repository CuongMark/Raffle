<?php

class Magestore_Raffle_Model_Raffle extends Mage_Core_Model_Abstract
{
    const STATUS_NOT_START = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_DISABLE = 4;
    
    public function _construct(){
        parent::_construct();
        $this->_init('raffle/raffle');
    }
    public function removeOldRules() {
        $oldRule = Mage::getModel('raffle/prizes')->getCollection()
                ->addFieldToFilter('raffle_id',$this->getId());
        foreach ($oldRule as $key => $value) {
            $value->delete();
        }
        return $this;
    }
    public function setNewRules($option) {
        $arrayName = $option['name'];
        foreach ($arrayName as $key => $value) {
            Mage::getModel('raffle/prizes')
                ->setRaffleId($this->getId())
                ->setName($value)
                ->setPrice($option['price'][$key])
                ->setTotal($option['total'][$key])
                ->setRest($option['total'][$key])
                ->setStatus(1)
                ->save();
        }
    }
	public function getRules(){
		return Mage::getModel('raffle/prizes')->getCollection()
                ->addFieldToFilter('raffle_id',$this->getId());
	}
    public function getStarted() {
        return $this->getStatus()!=null&&$this->getStatus()!=self::STATUS_NOT_START;
    }
    public function addTickets($customerId,$qty,$oder_id) {
        $ticket = Mage::getModel('raffle/tickets')
            ->setData('raffle_id',$this->getId())
            ->setData('customer_id',$customerId)
            ->setOrderId($oder_id)
            ->setData('total',$qty)
            ->setData('num_start',$this->getData('current_num')+1)
            ->setData('num_end',$this->getData('current_num') + $qty)
            ->setData('created_time',now())
            ->save();

        $this->setData('current_num',$this->getData('current_num') + $qty );
        if($this->getData('current_num')>=$this->getTotal()) {
            $ticket->setStatus(0)->gennerateRandNums();
            $this->setStatus(self::STATUS_COMPLETED);
        }
        $this->save();
    }
    public function createProduct(){
        $name = $this->getName();
        $price = $this->getPrice();
        $productId = $this->getProductId();
        $qty = $this->getTotal();
        if($productId){
            $product  =  Mage::getModel('catalog/product')->load($productId);
        }else{
            $product = Mage::getModel('catalog/product');
            $attributeSetName = 'Default';
            $entityType = Mage::getSingleton('eav/entity_type')->loadByCode('catalog_product');
            $entityTypeId = $entityType->getId();
            $product->setStockData(array(
                'is_in_stock' => 1,
                'qty' => $qty
            ));
            $setId = Mage::getResourceModel('catalog/setup', 'core_setup')->getAttributeSetId($entityTypeId, $attributeSetName);
            $product->setAttributeSetId($setId);
            $product->setTypeId('virtual');
            $product->setSku('raffle_' . $name);
            $product->setWebsiteIDs(array(1)); 
            $product->setTaxClassId(0);
            $product->setCategoryIds(Mage::app()->getStore()->getRootCategoryId());
            $product->setCreatedAt(now());
        }
        $product->setPrice($price);
        $product->setName($name);
        $product->setDescription($this->getData('description'));
        $product->setShortDescription($this->getData('description'));
        $product->setStatus(1);
        try{
            if(!$productId)
                $product->getResource()->save($product);
            $product->save($product);
            $this->setProductId($product->getId())->save();
            return $product->getId();
        }catch(Exception $e){
//            Zend_debug::dump($e->__toString());exit;
        }
    }
}