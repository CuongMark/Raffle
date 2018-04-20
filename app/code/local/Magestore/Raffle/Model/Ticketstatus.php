<?php

class Magestore_Raffle_Model_Ticketstatus extends Varien_Object
{
    const STATUS_NOT_CHECK	= 0;
    const STATUS_WIN	= 1;
    const STATUS_PAYED	= 2;
    const STATUS_LOSE	= 3;

    static public function getOptionArray(){
        return array(
            self::STATUS_NOT_CHECK	=> Mage::helper('raffle')->__('Not Check'),
            self::STATUS_WIN   => Mage::helper('raffle')->__('WIN'),
            self::STATUS_PAYED      => Mage::helper('raffle')->__('Payed'),
            self::STATUS_LOSE       => Mage::helper('raffle')->__('Lose')
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