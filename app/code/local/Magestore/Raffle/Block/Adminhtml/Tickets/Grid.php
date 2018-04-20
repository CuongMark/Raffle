<?php

class Magestore_Raffle_Block_Adminhtml_Tickets_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('ticketsGrid');
		$this->setDefaultSort('ticket_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection(){
		$collection = Mage::getModel('raffle/tickets')->getCollection()
						->addFieldToFilter('status',1);
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
    {
        $this->addColumn('ticket_id', array(
            'header'    => Mage::helper('raffle')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'ticket_id',
        ));
        $this->addColumn('customer_id', array(
            'header'    => Mage::helper('raffle')->__('Customers'),
            'align'     =>'left',
            'index'     => 'customer_id',
            'renderer'  => 'raffle/adminhtml_raffle_renderer_customer'
        ));
        $this->addColumn('num_start', array(
            'header'    => Mage::helper('raffle')->__('Start Number'),
            'align'     =>'left',
            'index'     => 'num_start',
        ));
        $this->addColumn('num_end', array(
            'header'    => Mage::helper('raffle')->__('End Number'),
            'align'     =>'left',
            'index'     => 'num_end',
        ));
        $this->addColumn('order_id', array(
            'header'    => Mage::helper('raffle')->__('Orders'),
            'align'     =>'left',
            'index'     => 'order_id',
            'renderer'  => 'raffle/adminhtml_raffle_renderer_order'
        ));
        $this->addColumn('created_time', array(
              'header'    => Mage::helper('raffle')->__('Created Date'),
              'width'     => '250px',
              'index'     => 'created_time',
              'type'      => 'datetime',
        ));
        $this->addColumn('winNumbers', array(
                'header'	=> Mage::helper('raffle')->__('Win numbers'),
                'align'	 => 'left',
                'width'	 => '500px',
                'index'	 => 'status',				
				'renderer'  => 'raffle/adminhtml_raffle_renderer_Winnumbers'
        ));
		$this->addColumn('winprice', array(
                'header'	=> Mage::helper('raffle')->__('Total Prize'),
                'align'	 => 'left',
                'width'	 => '80px',
                'index'	 => 'status',				'renderer'  => 'raffle/adminhtml_raffle_renderer_winprice'
        ));		
		$this->addColumn('ticket.status', array(
                'header'	=> Mage::helper('raffle')->__('Status'),
                'align'	 => 'left',
                'width'	 => '80px',
                'index'	 => 'status',
                'type'      => 'options',
                'options' => Mage::getSingleton('raffle/ticketstatus')->getOptionArray(),
        ));
        $this->addColumn('action',
            array(
                'header'	=> Mage::helper('raffle')->__('Action'),
                'width'		=> '100',
                'type'		=> 'action',
                'getter'	=> 'getId',
                'actions'	=> array(
                    array(
                        'caption'	=> Mage::helper('raffle')->__('Edit'),
                        'url'		=> array('base'=> '*/adminhtml_tickets/edit'),
                        'field'		=> 'id'
                    )),
                'filter'	=> false,
                'sortable'	=> false,
                'index'		=> 'stores',
                'is_system'	=> true,
        ));		
		$this->addExportType('*/*/exportCsv', Mage::helper('raffle')->__('CSV'));
        return parent::_prepareColumns();
  }

	// protected function _prepareMassaction(){
		// $this->setMassactionIdField('raffle_id');
		// $this->getMassactionBlock()->setFormFieldName('raffle');

		// $this->getMassactionBlock()->addItem('delete', array(
			// 'label'		=> Mage::helper('raffle')->__('Delete'),
			// 'url'		=> $this->getUrl('*/*/massDelete'),
			// 'confirm'	=> Mage::helper('raffle')->__('Are you sure?')
		// ));

		// $statuses = Mage::getSingleton('raffle/status')->getOptionArray();

		// array_unshift($statuses, array('label'=>'', 'value'=>''));
		// $this->getMassactionBlock()->addItem('status', array(
			// 'label'=> Mage::helper('raffle')->__('Change status'),
			// 'url'	=> $this->getUrl('*/*/massStatus', array('_current'=>true)),
			// 'additional' => array(
				// 'visibility' => array(
					// 'name'	=> 'status',
					// 'type'	=> 'select',
					// 'class'	=> 'required-entry',
					// 'label'	=> Mage::helper('raffle')->__('Status'),
					// 'values'=> $statuses
				// ))
		// ));
		// return $this;
	// }

	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}