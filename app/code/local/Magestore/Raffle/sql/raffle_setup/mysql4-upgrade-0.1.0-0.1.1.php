<?php
$installer = $this;
$installer->startSetup();
$installer->run("
     ALTER TABLE {$this->getTable('raffle_header')} ADD COLUMN `raffle_prefix` TEXT NOT NULL;
");
$installer->endSetup();
