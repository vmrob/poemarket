<?php
/*
	Edit this with extreme caution. These operations are guaranteed performed once each for each installation,
	as long as you ONLY APPEND to this list. Don't remove anything.
	
	The site is locked down while the operations are being performed so keep them quick if possible.
*/
return array(
	function($app) {
		$app->SQL->query('
			CREATE TABLE `poem_users` (
				`user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`user_email` int(11) DEFAULT NULL,
				`user_poe_session` varchar(60) DEFAULT NULL,
				`user_secret` varchar(60) DEFAULT NULL,
				PRIMARY KEY (`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
		');
	},
);
?>