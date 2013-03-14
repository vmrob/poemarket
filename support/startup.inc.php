<?php
define('UPDATE_HTML', 'Eek! You caught us doing something embarrassing! The site\'s being updated, but it\'ll be done soon. Sorry you had to see us this way.');

function __autoload($class) {
	require_once $class.'.class.php';
}

require_once 'functions.inc.php';

// see if the site is locked down
if ($lock_html = site_lock_html()) {
	die($lock_html);
}

$config = require('config.inc.php');
$_ = new Application($config);

// make sure our database is up-to-date
if (needs_update_operations()) {
	ignore_user_abort(true);
	set_time_limit(0);
	if (try_site_lock(UPDATE_HTML)) {
		sleep(10); // wait a moment to let other scripts finish
		perform_update_operations();
		site_unlock();
		die('Congratulations! You just performed a site update! Refresh the page to see the mess you\'ve made.');
	} else if ($lock_html = site_lock_html()) {
		die($lock_html);
	}
	die('That\'s odd. Hopefully this message will go away soon...');
}
?>