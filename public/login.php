<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

$data = array();

if (isset($_POST['login'], $_POST['email'], $_POST['password'])) {	
	$ch = curl_init();

	$fields = array(
		'login' => 'Login',
		'login_email' => $_POST['email'],
		'login_password' => $_POST['password'], 
		'remember_me' => 1,
	);
	
	curl_setopt_array($ch, array(
		CURLOPT_URL => 'https://www.pathofexile.com/login',
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 5,
		CURLOPT_POSTFIELDS => $fields,
	));
	
	$result = curl_exec($ch);
	$info   = curl_getinfo($ch);

	curl_close($ch);
	
	if ($info && $info['http_code'] == 302 && $info['redirect_url'] == 'http://www.pathofexile.com/my-account') {
		// success!
		$data['error'] = 'This isn\'t finished yet. Nice password though. ;)';
	} else {
		$data['error'] = 'An error occurred. You may have put in the wrong email or password. Or maybe the Path of Exile site is down. I don\'t really know but good luck tryin\' again!';
	}
}

render_page('login', 'Log In', $data);
?>