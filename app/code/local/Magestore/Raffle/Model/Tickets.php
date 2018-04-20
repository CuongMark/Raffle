<?php

class Magestore_Raffle_Model_Tickets extends Mage_Core_Model_Abstract
{
        const STATUS_NOT_CHECK	= 0;
        const STATUS_WIN	= 1;
        const STATUS_PAYED	= 2;
        const STATUS_LOSE	= 3;
        const STATUS_DISABLED   = 4;
        const XML_PATH_SALES_EMAIL_IDENTITY = "trans_email/ident_sales";
        const XML_PATH_TO_WINNER    = "rafflee/emails/to_winner";
        const XML_PATH_TO_LOSER     = "rafflee/emails/to_loser";
        const XML_PATH_TO_ADMIN     = "rafflee/emails/to_admin";
        const XML_PATH_NEW_TICKETS  = "rafflee/emails/new_ticket";
	public function _construct(){
		parent::_construct();
		$this->_init('raffle/tickets');
	}
        public function gennerateRandNums() {
            if($this->getStatus()==self::STATUS_NOT_CHECK){
                $write = Mage::getSingleton('core/resource')->getConnection('core_write');
                $write->beginTransaction();
                try {
                    $prizes = Mage::getModel('raffle/prizes')
                        ->getCollection()
                        ->addFieldToFilter('raffle_id',$this->getRaffleId());
                    $raffle = Mage::getModel('raffle/raffle')->load($this->getRaffleId());
                    $isWin = false;

                    if($raffle->getId()){
                        $winnumber = array();
                        foreach ($prizes as $_prize) {
                            $count = $_prize->getRest();
                            $newCount = $count;
                            for($i = 0; $i<$count;$i++){
                                $rand = null;
                                while(in_array($rand, $winnumber) && count($winnumber) < $this->getTotal()-$raffle->getNumStart() ){
                                    $rand = mt_rand($this->getNumStart(),$raffle->getTotal());
                                }
                                $winnumber[] = $rand;
                                if($rand>=$this->getNumStart()&&$rand<=$this->getNumEnd()){
                                    Mage::getModel('raffle/randnum')
                                        ->setTicketId($this->getId())
                                        ->setNumber($rand)
                                        ->setPrizeId($_prize->getId())
                                        ->save();
                                    $newCount--;
                                    $isWin = true;
                                }
                            }
                            if($newCount != $count){
                                $_prize->setRest($newCount)->save();
                            }
                        }
                        if($isWin)
                            $this->setStatus(self::STATUS_WIN);
                        else
                            $this->setStatus(self::STATUS_LOSE);
                        $this->save();
                    }
                    $write->commit();
                } catch (Exception $e) {
                    $write->rollback();
//                    Zend_debug::dump($e->__toString());exit;
                }
            }
        }
        public function getWinNumbers() {
            $collection = Mage::getModel('raffle/randnum')->getCollection()
                    ->addFieldToFilter('ticket_id',$this->getId());
            $collection->getSelect()
                ->joinLeft(
                    array('prizes'=>Mage::getModel('core/resource')->getTableName('raffle_prizes')),
                    "main_table.prize_id = prizes.prize_id",
                        array('name' => 'prizes.name','price'=>'prizes.price')
            );
            return $collection;
        }

        public function getWinNumbersArray(){
	        $data = [];
	        foreach ($this->getWinNumbers() as $_number){
	            $data[] = $_number->getNumber();
            };
	        return $data;
        }


    public function getCustomer(){
        if(!$this->getData('customer')){
            $this->setData('customer', Mage::getModel('customer/customer')->load($this->getCustomerId()));
        }
        return $this->getData('customer');
    }

    public function getRaffle(){
        if(!$this->getData('raffle')){
            $this->setData('raffle', Mage::getModel('raffle/raffle')->load($this->getRaffleId()));
        }
        return $this->getData('raffle');
    }

    public function emailNewTickets() {
        $storeID = 1;//$this->getStoreId();
        $customer = $this->getCustomer();
        $raffle = $this->getRaffle();
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        $template = Mage::getStoreConfig(self::XML_PATH_NEW_TICKETS, $storeID);
        $sendTo = array(
            array(
                'name' => $customer->getName(),
                'email' => $customer->getEmail(),
            )
        );
        $mailTemplate = Mage::getModel('core/email_template');
        foreach ($sendTo as $recipient) {
            $mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeID))
                ->sendTransactional(
                    $template, Mage::getStoreConfig(self::XML_PATH_SALES_EMAIL_IDENTITY, $storeID), $recipient['email'], $recipient['name'], array(
                        'customer_name' => $recipient['name'],
                        'raffle_name' => $raffle->getName(),
                        'num_start' => $this->getData('num_start'),
                        'num_end' => $this->getData('num_end'),
                    )
                );
        }
        $translate->setTranslateInline(true);
        return $this;
    }

    public function emailWinner() {
        $storeID = 1;//$this->getStoreId();
        $customer = $this->getCustomer();
        $raffle = $this->getRaffle();
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        $template = Mage::getStoreConfig(self::XML_PATH_TO_WINNER, $storeID);
        $sendTo = array(
            array(
                'name' => $customer->getName(),
                'email' => $customer->getEmail(),
            )
        );
        $mailTemplate = Mage::getModel('core/email_template');
        foreach ($sendTo as $recipient) {
            $mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeID))
                ->sendTransactional(
                    $template, Mage::getStoreConfig(self::XML_PATH_SALES_EMAIL_IDENTITY, $storeID), $recipient['email'], $recipient['name'], array(
                        'customer_name' => $recipient['name'],
                        'raffle_name' => $raffle->getName(),
                        'win_number' => $raffle->getData('win_number'),
                    )
                );
        }
        $translate->setTranslateInline(true);
        return $this;
    }

    public function emailLoser() {
        $storeID = 1;//$this->getStoreId();
        $customer = $this->getCustomer();
        $raffle = $this->getRaffle();
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        $template = Mage::getStoreConfig(self::XML_PATH_TO_LOSER, $storeID);
        $sendTo = array(
            array(
                'name' => $customer->getName(),
                'email' => $customer->getEmail(),
            )
        );
        $mailTemplate = Mage::getModel('core/email_template');
        foreach ($sendTo as $recipient) {
            $mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeID))
                ->sendTransactional(
                    $template, Mage::getStoreConfig(self::XML_PATH_SALES_EMAIL_IDENTITY, $storeID), $recipient['email'], $recipient['name'], array(
                        'customer_name' => $recipient['name'],
                        'raffle_name' => $raffle->getName(),
                        'win_number' => $raffle->getData('win_number')
                    )
                );
        }
        $translate->setTranslateInline(true);
        return $this;
    }

    public static function status(){
        return array(
            self::STATUS_NOT_CHECK    => Mage::helper('rafflee')->__('Enable'),
            self::STATUS_WIN        => Mage::helper('rafflee')->__('WIN'),
            self::STATUS_PAYED      => Mage::helper('rafflee')->__('Paid'),
            self::STATUS_LOSE       => Mage::helper('rafflee')->__('Lose'),
            self::STATUS_DISABLED   => Mage::helper('rafflee')->__('Díabled')
        );
    }

    public function isWinTicket(){
        return $this->getStatus() == self::STATUS_WIN;
    }

    public function isChecked(){
        return $this->getStatus() == self::STATUS_NOT_CHECK;
    }
}