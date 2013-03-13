<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

if ($_->config['git_secret'] && isset($_GET['secret']) && $_GET['secret'] == $_->config['git_secret']) {
	exec('(cd ../ && git checkout master && git pull origin master && git clean -fd) &> /dev/null &');
	die('Thanks bro!');
}

$data = array(
	'header' => 'Hey! You!', 
	'message' => 'What are you doing here? Shoo!', 
);

render_page('system_message', $data['header'], $data);
?>