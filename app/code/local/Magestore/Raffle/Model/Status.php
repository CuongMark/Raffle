<?php

class Magestore_Raffle_Model_Status extends Varien_Object
{
	const STATUS_ENABLED	= 1;
	const STATUS_PROCESSING	= 2;
	const STATUS_FINISHED	= 3;
	const STATUS_DISABLE	= 4;

	static public function getOptionArray(){
            return array(
                self::STATUS_ENABLED	=> Mage::helper('raffle')->__('Enabled'),
                self::STATUS_PROCESSING   => Mage::helper('raffle')->__('Processing'),
                self::STATUS_FINISHED   => Mage::helper('raffle')->__('Finished'),
                self::STATUS_DISABLE   => Mage::helper('raffle')->__('Disable') 
            );
	}
	
	static public function getOptionHash(){
            $options = array();
            foreach (self::getOptionArray() as $value => $label)
                $options[] = array(
                    'value'	=> $value,
                    'label'	=> $label
                );
            return $options;
	}
}