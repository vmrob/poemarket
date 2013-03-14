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
		$new_config = require(dirname(dirname(__FILE__)).'/support/config.inc.php');
		if ($new_config['site_revision_number'] != $_->config['site_revision_number']) {
			// yep, revision definitely changed. check for update ops
			$new_ops = require(dirname(dirname(__FILE__)).'/support/update_operations.inc.php');
			if (count($old_ops) < count($new_ops)) {
				sleep(10); // sleep for a moment to let scripts finish
			}
			for ($i = count($old_ops); $i < count($new_ops); ++$i) {
				$new_ops[$i]($_);
			}
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