<?php
$installer = $this;
$installer->startSetup();
$installer->run("
     ALTER TABLE {$this->getTable('raffle_random_numbers')} DROP COLUMN `raffle_id`;
     ALTER TABLE {$this->getTable('raffle_random_numbers')} DROP COLUMN `rule_name`;
     ALTER TABLE {$this->getTable('raffle_random_numbers')} DROP COLUMN `ticket_id`;
     ALTER TABLE {$this->getTable('raffle_random_numbers')} DROP COLUMN `customer_id`;
     ALTER TABLE {$this->getTable('raffle_random_numbers')} DROP COLUMN `customer_email`;
	 ALTER TABLE {$this->getTable('mangento_customer_raffles')} ADD COLUMN `payout` varchar(255) NOT NULL default '';
	 ALTER TABLE {$this->getTable('mangento_customer_raffles')} ADD COLUMN `payout_time` datetime NULL;
	 ALTER TABLE {$this->getTable('mangento_customer_raffles')} ADD COLUMN `user_payout` int(11) NOT NULL default '0';
");
$installer->endSetup();
