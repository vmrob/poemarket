<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

if ($_->config['update_secret'] && isset($_GET['secret']) && $_GET['secret'] == $_->config['update_secret']) {
	ignore_user_abort(true);
	set_time_limit(0);
	$lock_html = 'Eek! You caught us doing something embarrassing! The site\'s being updated, but it\'ll probably be done by the time you finish reading this. Sorry you had to see us this way.';
	if (try_site_lock($lock_html)) {
		$old_ops = require(dirname(dirname(__FILE__)).'/support/update_operations.inc.php');
		exec($_->config['update_command']);
		$new_ops = require(dirname(dirname(__FILE__)).'/support/update_operations.inc.php');
		for ($i = count($old_ops); $i < count($new_ops); ++$i) {
			$new_ops[$i]($_);
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