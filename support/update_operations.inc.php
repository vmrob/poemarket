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
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` ADD `user_name` VARCHAR(60)  NOT NULL  DEFAULT ''  AFTER `user_secret`
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` ADD UNIQUE INDEX (`user_name`)
		");
		$app->sql->query("
			ALTER TABLE `{$app->config['mysql_table_prefix']}users` CHANGE `user_secret` `user_secret` VARCHAR(80)  CHARACTER SET latin1  COLLATE latin1_swedish_ci  NULL  DEFAULT NULL
		");
	},
);
?>