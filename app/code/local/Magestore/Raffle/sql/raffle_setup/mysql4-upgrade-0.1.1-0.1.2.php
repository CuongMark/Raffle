<?php
$installer = $this;
$installer->startSetup();
$installer->run("
     ALTER TABLE {$this->getTable('mangento_customer_raffles')} ADD COLUMN `order_id` int(11) NOT NULL;
     ALTER TABLE {$this->getTable('mangento_customer_raffles')} ADD COLUMN `status` smallint(6) NOT NULL default '0';
");
$installer->endSetup();
