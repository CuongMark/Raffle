<?php

class Magestore_Raffle_Block_Adminhtml_Raffle_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('raffleGrid');
		$this->setDefaultSort('raffle_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection(){
		$collection = Mage::getModel('raffle/raffle')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns(){
		$this->addColumn('raffle_id', array(
			'header'	=> Mage::helper('raffle')->__('ID'),
			'align'	 =>'right',
			'width'	 => '50px',
			'index'	 => 'raffle_id',
		));
		$this->addColumn('name', array(
			'header'	=> Mage::helper('raffle')->__('Name'),
			'align'	 =>'left',
			'index'	 => 'name',
		));
		$this->addColumn('price', array(
			'header' => Mage::helper('raffle')->__('Price'),
			'width'	 => '150px',
			'index'	 => 'price',
		));
		$this->addColumn('current_num', array(
			'header' => Mage::helper('raffle')->__('Current Number'),
			'width'	 => '150px',
			'index'	 => 'current_num',
		));
		$this->addColumn('total', array(
			'header' => Mage::helper('raffle')->__('Total'),
			'width'	 => '150px',
			'index'	 => 'total',
		));
		$this->addColumn('status', array(
			'header'	=> Mage::helper('raffle')->__('Status'),
			'align'	 => 'left',
			'width'	 => '80px',
			'index'	 => 'status',
			'type'   => 'options',
			'options'=> Mage::getSingleton('raffle/status')->getOptionArray(), 
		));

		$this->addColumn('action',
			array(
				'header'	=>	Mage::helper('raffle')->__('Action'),
				'width'		=> '100',
				'type'		=> 'action',
				'getter'	=> 'getId',
				'actions'	=> array(
					array(
						'caption'	=> Mage::helper('raffle')->__('Edit'),
						'url'		=> array('base'=> '*/*/edit'),
						'field'		=> 'id'
					)),
				'filter'	=> false,
				'sortable'	=> false,
				'index'		=> 'stores',
				'is_system'	=> true,
		));

		$this->addExportType('*/*/exportCsv', Mage::helper('raffle')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('raffle')->__('XML'));

		return parent::_prepareColumns();
	}

	protected function _prepareMassaction(){
		$this->setMassactionIdField('raffle_id');
		$this->getMassactionBlock()->setFormFieldName('raffle');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'		=> Mage::helper('raffle')->__('Delete'),
			'url'		=> $this->getUrl('*/*/massDelete'),
			'confirm'	=> Mage::helper('raffle')->__('Are you sure?')
		));

		$statuses = Mage::getSingleton('raffle/status')->getOptionArray();

		array_unshift($statuses, array('label'=>'', 'value'=>''));
		$this->getMassactionBlock()->addItem('status', array(
			'label'=> Mage::helper('raffle')->__('Change status'),
			'url'	=> $this->getUrl('*/*/massStatus', array('_current'=>true)),
			'additional' => array(
				'visibility' => array(
					'name'	=> 'status',
					'type'	=> 'select',
					'class'	=> 'required-entry',
					'label'	=> Mage::helper('raffle')->__('Status'),
					'values'=> $statuses
				))
		));
		return $this;
	}

	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}