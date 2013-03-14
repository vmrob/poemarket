<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

$data = array();

if (isset($_POST['login'], $_POST['email'], $_POST['password'])) {
	$data['error'] = 'Not implemented.';
}

render_page('login', 'Log In', $data);
?>