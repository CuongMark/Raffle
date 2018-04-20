<?php

$installer = $this;
$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('raffle')};
CREATE TABLE {$this->getTable('raffle')} (
    `raffle_id` int(11) unsigned NOT NULL auto_increment,
    `name` varchar(255) NOT NULL default '',
    `prefix` varchar(255) NOT NULL default '',
    `total` int(11) NOT NULL default '0',
    `current_num` int(11) NOT NULL default '0',
    `product_id` int(11) NOT NULL default '0',
    `price` decimal(12,4) NOT NULL default '0.00',
    `description` text NOT NULL default '',
    `user_created` varchar(255) NOT NULL default '',
    `user_update` varchar(255) NOT NULL default '',
    `created_time` datetime NULL,
    `update_time` datetime NULL,
    `status` smallint(6) NOT NULL default '0',
    PRIMARY KEY (`raffle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('raffle_prizes')};
CREATE TABLE {$this->getTable('raffle_prizes')} (
    `prize_id` int(11) unsigned NOT NULL auto_increment,
    `name` varchar(255) NOT NULL default '',
    `raffle_id` int(11) NOT NULL default '0',
    `total` int(11) NOT NULL default '0',
    `rest` int(11) NOT NULL default '0',
    `price` decimal(12,4) NOT NULL default '0.00',
    `status` smallint(6) NOT NULL default '0',
    PRIMARY KEY (`prize_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('raffle_tickets')};
CREATE TABLE {$this->getTable('raffle_tickets')} (
    `ticket_id` int(11) unsigned NOT NULL auto_increment,
    `customer_id` varchar(255) NOT NULL default '',
    `raffle_id` int(11) NOT NULL default '0',
    `total` int(11) NOT NULL default '0',
    `num_start` int(11) NOT NULL default '0',
    `num_end` int(11) NOT NULL default '0',
    `order_id` int(11) NOT NULL default '0',
    `created_time` datetime NULL,
    `payout` varchar(255) NOT NULL default '',
    `payout_time` datetime NULL,
    `user_payout` int(11) NOT NULL default '0',
    `status` smallint(6) NOT NULL default '0',
    PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('raffle_win_number')};
CREATE TABLE {$this->getTable('raffle_win_number')} (
    `rand_number_id` int(11) unsigned NOT NULL auto_increment,
    `ticket_id` varchar(255) NOT NULL default '',
    `prize_id` int(11) NOT NULL default '0',    
    `number` int(11) NOT NULL,    
    `status` smallint(6) NOT NULL default '0',
    PRIMARY KEY (`rand_number_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 