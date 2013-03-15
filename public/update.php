<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

if ($_->config['update_secret'] && isset($_GET['secret']) && $_GET['secret'] == $_->config['update_secret']) {
	// TODO: make updates seamless and more robust (there may be some race conditions that could 
	// yield unexpected behavior if a user is mid-script throughout the lock and update)
	ignore_user_abort(true);
	set_time_limit(0);
	$lock_html = 'Eek! You caught us doing something embarrassing! The site\'s being updated, but it\'ll be done soon. Sorry you had to see us this way.';
	if (try_site_lock($lock_html)) {
		$old_ops = require(dirname(dirname(__FILE__)).'/support/update_operations.inc.php');
		exec($_->config['update_command']);
		if (needs_update_operations()) {
			sleep(20); // wait a moment to let other scripts finish
			perform_update_operations();
		}
		site_unlock();
	}
	die('Thanks bro!');
}

$data = array(
	'header'  => 'Hey! You!', 
	'message' => 'What are you doing here? Shoo!', 
);

render_page('system_message', $data['header'], $data);
?>