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
);
?>