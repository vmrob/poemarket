<?php
/*
	Edit this with extreme caution. These operations are guaranteed performed once each for each installation,
	as long as you ONLY APPEND to this list. Don't remove anything.
	
	The site is locked down while the operations are being performed so keep them quick if possible.
*/
return array(
	function($app) {
		$app->sql->query("
			CREATE TABLE `{$app->config['mysql_table_prefix']}key_value` (
				`key` varchar(60) NOT NULL DEFAULT '',
				`value` tinytext,
				PRIMARY KEY (`key`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
		");
		$app->sql->query("
			CREATE TABLE `{$app->config['mysql_table_prefix']}users` (
				`user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`user_email` tinytext,
				`user_poe_session` varchar(60) DEFAULT NULL,
				`user_secret` varchar(60) DEFAULT NULL,
				PRIMARY KEY (`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
		");
	},
	function($app) {
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` ADD `user_name` VARCHAR(60)  DEFAULT ''  AFTER `user_secret`;
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` ADD UNIQUE INDEX (`user_name`);
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` CHANGE `user_secret` `user_secret` VARCHAR(80)  CHARACTER SET latin1  COLLATE latin1_swedish_ci  NOT NULL  DEFAULT NULL;
		");
	},
	function($app) {
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}key_value` ENGINE = InnoDB;
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` ENGINE = InnoDB;
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` CHARACTER SET = utf8;
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}key_value` CHARACTER SET = utf8;
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}key_value` CHANGE `key` `key` VARCHAR(60)  CHARACTER SET utf8  NOT NULL  DEFAULT '';
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}key_value` CHANGE `value` `value` TINYTEXT  CHARACTER SET utf8  NOT NULL  DEFAULT '';
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` CHANGE `user_email` `user_email` TINYTEXT  CHARACTER SET utf8  NOT NULL  DEFAULT '';
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` CHANGE `user_poe_session` `user_poe_session` VARCHAR(60)  CHARACTER SET utf8  NOT NULL  DEFAULT '';
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` CHANGE `user_secret` `user_secret` VARCHAR(80)  CHARACTER SET utf8  NOT NULL  DEFAULT '';
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` CHANGE `user_name` `user_name` VARCHAR(60)  CHARACTER SET utf8  NOT NULL  DEFAULT '';
		");
		$app->sql->query("
			CREATE TABLE `{$app->config['mysql_table_prefix']}worker_tasks` (
				`task_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` int(11) unsigned NOT NULL,
				`task_time` int(11) unsigned NOT NULL,
				`task_worker_name` varchar(40) NOT NULL DEFAULT '',
				`task_status_text` tinytext NOT NULL,
				`task_status_time` int(11) unsigned NOT NULL,
				`task_status` enum('waiting','assigned','failed') NOT NULL DEFAULT 'waiting',
				`task_type` enum('inventory-update') NOT NULL DEFAULT 'inventory-update',
				PRIMARY KEY (`task_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	},
	function($app) {
		$app->sql->query("
			CREATE TABLE `{$app->config['mysql_table_prefix']}base_items` (
			  `base_item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `base_item_type` enum('gem','currency') NOT NULL DEFAULT 'currency',
			  `base_item_display_info` text NOT NULL,
			  PRIMARY KEY (`base_item_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");

		$app->sql->query("
			INSERT INTO `{$app->config['mysql_table_prefix']}base_items` (`base_item_id`, `base_item_type`, `base_item_display_info`)
			VALUES
				(50,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyGemQuality.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Gemcutter\\'s Prism\\\",\\\"explicit_mods\\\":[\\\"Improves the quality of a gem\\\"],\\\"instructions\\\":\\\"Right click this item then left click a gem to apply it. The maximum quality is 20%.\\\"}'),
				(51,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyIdentification.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Scroll of Wisdom\\\",\\\"explicit_mods\\\":[\\\"Identifies an item\\\"],\\\"instructions\\\":\\\"Right click this item then left click an unidentified item to apply it.\\\\nShift click to unstack.\\\"}'),
				(52,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyWeaponQuality.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Blacksmith\\'s Whetstone\\\",\\\"explicit_mods\\\":[\\\"Improves the quality of a weapon\\\"],\\\"instructions\\\":\\\"Right click this item then left click a weapon to apply it. Has greater effect on lower rarity weapons. The maximum quality is 20%.\\\\nShift click to unstack.\\\"}'),
				(53,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyUpgradeToMagic.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Orb of Transmutation\\\",\\\"explicit_mods\\\":[\\\"Upgrades a normal item to a magic item\\\"],\\\"instructions\\\":\\\"Right click this item then left click a normal item to apply it.\\\\nShift click to unstack.\\\"}'),
				(54,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyAddModToMagic.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Orb of Augmentation\\\",\\\"explicit_mods\\\":[\\\"Enchants a magic item with a new random property\\\"],\\\"instructions\\\":\\\"Right click this item then left click a magic item to apply it. Magic items can have up to two random properties.\\\\nShift click to unstack.\\\"}'),
				(55,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyUpgradeToMagicShard.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Transmutation Shard\\\",\\\"explicit_mods\\\":[],\\\"instructions\\\":\\\"A stack of 20 shards becomes an Orb of Transmutation.\\\\nShift click to unstack.\\\"}'),
				(56,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyImplicitMod.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Blessed Orb\\\",\\\"explicit_mods\\\":[\\\"Randomises the numeric values of the implicit properties of an item\\\"],\\\"instructions\\\":\\\"Right click this item then left click another item to apply it.\\\\nShift click to unstack.\\\"}'),
				(57,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyArmourQuality.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Armourer\\'s Scrap\\\",\\\"explicit_mods\\\":[\\\"Improves the quality of an armour\\\"],\\\"instructions\\\":\\\"Right click this item then left click an armour to apply it. Has greater effect on lower rarity armours. The maximum quality is 20%.\\\\nShift click to unstack.\\\"}'),
				(58,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyUpgradeRandomly.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Orb of Chance\\\",\\\"explicit_mods\\\":[\\\"Upgrades a normal item to a random rarity\\\"],\\\"instructions\\\":\\\"Right click this item then left click a normal item to apply it.\\\\nShift click to unstack.\\\"}'),
				(59,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyFlaskQuality.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Glassblower\\'s Bauble\\\",\\\"explicit_mods\\\":[\\\"Improves the quality of a flask\\\"],\\\"instructions\\\":\\\"Right click this item then left click a flask to apply it. Has greater effect on lower rarity flasks. The maximum quality is 20%.\\\\nShift click to unstack.\\\"}'),
				(60,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyRerollSocketColours.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Chromatic Orb\\\",\\\"explicit_mods\\\":[\\\"Reforges the colour of sockets on an item\\\"],\\\"instructions\\\":\\\"Right click this item then left click a socketed item to apply it.\\\\nShift click to unstack.\\\"}'),
				(61,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyRerollSocketNumbers.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Jeweller\\'s Orb\\\",\\\"explicit_mods\\\":[\\\"Reforges the number of sockets on an item\\\"],\\\"instructions\\\":\\\"Right click this item then left click a socketed item to apply it.\\\\nShift click to unstack.\\\"}'),
				(62,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyRerollSocketLinks.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Orb of Fusing\\\",\\\"explicit_mods\\\":[\\\"Reforges the links between sockets on an item\\\"],\\\"instructions\\\":\\\"Right click this item then left click a socketed item to apply it.\\\\nShift click to unstack.\\\"}'),
				(63,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyPortal.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Portal Scroll\\\",\\\"explicit_mods\\\":[\\\"Creates a portal to town\\\"],\\\"instructions\\\":\\\"Right click on this item to use it.\\\\nShift click to unstack.\\\"}'),
				(64,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyRerollMagic.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Orb of Alteration\\\",\\\"explicit_mods\\\":[\\\"Reforges a magic item with new random properties\\\"],\\\"instructions\\\":\\\"Right click this item then left click a magic item to apply it.\\\\nShift click to unstack.\\\"}'),
				(65,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyUpgradeToRareShard.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Alchemy Shard\\\",\\\"explicit_mods\\\":[],\\\"instructions\\\":\\\"A stack of 20 shards becomes an Orb of Alchemy.\\\"}'),
				(66,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyRerollMagicShard.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Alteration Shard\\\",\\\"explicit_mods\\\":[],\\\"instructions\\\":\\\"A stack of 20 shards becomes an Orb of Alteration.\\\\nShift click to unstack.\\\"}'),
				(67,'currency','{\\\"type\\\":\\\"currency\\\",\\\"image\\\":\\\"https:\\\\/\\\\/web-grindinggear.netdna-ssl.com\\\\/image\\\\/Art\\\\/2DItems\\\\/Currency\\\\/CurrencyUpgradeToRare.png\\\",\\\"width\\\":1,\\\"height\\\":1,\\\"name\\\":\\\"Orb of Alchemy\\\",\\\"explicit_mods\\\":[\\\"Upgrades a normal item to a rare item\\\"],\\\"instructions\\\":\\\"Right click this item then left click a normal item to apply it.\\\\nShift click to unstack.\\\"}');
		");
	},
);
?>