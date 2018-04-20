<?php

class Magestore_Raffle_Block_Adminhtml_Raffle_Edit_Tab_Randnum extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct(){
        parent::__construct();
        $this->setId('winnersGrid');
        $this->setDefaultSort('randomnum_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
        if(!Mage::registry('raffle_data')){
            $id	 = $this->getRequest()->getParam('id');
            $model  = Mage::getModel('raffle/raffle')->load($id);
            Mage::register('raffle_data', $model);
        }
    }

    protected function _prepareCollection(){
//        $ruleId = array();
//        $rules = Mage::getModel('raffle/rule')->getCollection()
//            ->addFieldToFilter('raffle_id',$this->getRequest()->getParam('id'));
//        foreach($rules as $_rule){
//                $ruleId[] = $_rule->getId();
//        }
        $collection = Mage::getModel('raffle/randnum')->getCollection();
        $collection->getSelect()
            ->joinLeft(
                array('prizes'=>Mage::getModel('core/resource')->getTableName('raffle_prizes')),
                "main_table.prize_id = prizes.prize_id",
                    array('name' => 'prizes.name','price'=>'prizes.price')
        );
        // $collection->getSelect()->joinLeft(
                    // array('ticket'=>Mage::getModel('core/resource')->getTableName('raffle_tickets')),
                    // 'ticket.raffle_id = prizes.raffle_id',
                    // array(
                        // 'customer_id' => 'ticket.customer_id',
            // )
        // );
        $collection ->addFieldToFilter('prizes.raffle_id',$this->getRequest()->getParam('id'));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('rand_number_id', array(
            'header'    => Mage::helper('raffle')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'rand_number_id',
        ));
        $this->addColumn('prizes.name', array(
            'header'    => Mage::helper('raffle')->__('Prizes'),
            'align'     =>'left',
            'index'     => 'name',
        ));
        $this->addColumn('prizes.price', array(
            'header'    => Mage::helper('raffle')->__('Price'),
            'align'     =>'left',
            'index'     => 'price',
        ));
        $this->addColumn('number', array(
            'header'    => Mage::helper('raffle')->__('Number'),
            'align'     =>'left',
            'index'     => 'number',
        ));
        // $this->addColumn('customer_id', array(
            // 'header'    => Mage::helper('raffle')->__('Customer'),
            // 'align'     =>'left',
            // 'index'     => 'customer_id',
            // 'renderer'  => 'raffle/adminhtml_raffle_renderer_customer'
        // ));
        return parent::_prepareColumns();
  }


	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

	public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/winnersGrid', array('_current'=>true,'id'=>$this->getRequest()->getParam('id')));
    }
}