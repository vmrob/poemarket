<?php
require_once dirname(dirname(dirname(__FILE__))).'/support/startup.inc.php';

require_login();

$data = array(
	'queue_position' => 5,
	'status' => 'waiting',
	'status_text' => 'Please wait...',
);

die(json_encode($data));
?>
