<?php
define('UPDATE_HTML', 'How embarrassing! The site\'s being updated, but it\'ll be done soon. Sorry you had to see us this way.');

function __autoload($class) {
	require_once $class.'.class.php';
}

require_once 'functions.inc.php';

// see if the site is locked down
if ($lock_html = site_lock_html()) {
	die($lock_html);
}

// create the application instance
$config = require('config.inc.php');
$_ = new Application($config);

// make sure our database is up-to-date. this should come before any other database queries
// no end-user should ever see this. this is primarily here for initial setup and development purposes
if (needs_update_operations()) {
	ignore_user_abort(true);
	set_time_limit(0);
	if (try_site_lock(UPDATE_HTML)) {
		perform_update_operations();
		site_unlock();
		die('Congratulations! You just updated the site! Refresh the page to see the mess you\'ve made.');
	} else if ($lock_html = site_lock_html()) {
		die($lock_html);
	}
	die('That\'s odd. Hopefully this message will go away soon...');
}

if (isset($_GET['logout'])) {
	// log the user out
	$_->log_out();
} else {
	// see if the user is logged in
	$_->check_authentication();
}
?>