<?php

class Magestore_Raffle_Block_Adminhtml_Raffle_Edit_Tab_Winners extends Mage_Adminhtml_Block_Widget_Grid
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
		$ruleId = array();
		$rules = Mage::getModel('raffle/rule')->getCollection()
					->addFieldToFilter('raffle_id',$this->getRequest()->getParam('id'));
		foreach($rules as $_rule){
			$ruleId[] = $_rule->getId();
		}
        $collection = Mage::getModel('raffle/randnumbers')->getCollection();
		if($this->getRequest()->getParam('id'))
		$collection->getSelect()
				->joinLeft(
					array('rules'=>Mage::getModel('core/resource')->getTableName('raffle_rule')),
					"main_table.rule_id = rules.rule_id",
						array(
							'main_table.rule_name' => 'rules.name',
					)
				)
				->joinLeft(
					array('winner'=>Mage::getModel('core/resource')->getTableName('mangento_customer_raffles')),
					"main_table.raffle_randomnum >= winner.raffle_ticketnum_start and main_table.raffle_randomnum <= winner.raffle_ticketnum_end and winner.raffle_id =" .$this->getRequest()->getParam('id'),
					array(
						'main_table.customer_email' => 'winner.customer_email',
				)
			);
		$collection ->addFieldToFilter('main_table.rule_id',array('in'=>$ruleId));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('randomnum_id', array(
            'header'    => Mage::helper('raffle')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'randomnum_id',
        ));
        $this->addColumn('rule_name', array(
            'header'    => Mage::helper('raffle')->__('Rule'),
            'align'     =>'left',
            'index'     => 'main_table.rule_name',
        ));
        $this->addColumn('customer_email', array(
            'header'    => Mage::helper('raffle')->__('Winner'),
            'align'     =>'left',
            'index'     => 'customer_email',
        ));
        $this->addColumn('raffle_randomnum', array(
            'header'    => Mage::helper('raffle')->__('Win Number'),
            'align'     =>'left',
            'index'     => 'raffle_randomnum',
			'renderer'  => 'Magestore_Raffle_Block_Adminhtml_Renderer'
        ));
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