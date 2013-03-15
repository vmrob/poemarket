<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

$data = array();

if ($_->user) {
	$data['header']  = 'Are you lost?';
	$data['message'] = 'You\'re already logged in!';
	render_page('system_message', $data['header'], $data);
}

if (isset($_POST['login'], $_POST['email'], $_POST['password'])) {
	$response = http_request('https://www.pathofexile.com/login', array(
		'login' => 'Login',
		'login_email' => $_POST['email'],
		'login_password' => $_POST['password'], 
		'remember_me' => 0,
	));

	// on success we get redirected
	if ($response && $response['http_code'] == 200 && $response['url'] == 'http://www.pathofexile.com/my-account') {
		// looks like success...
		$matches = array();
		preg_match('/class="profile-name">([a-zA-Z0-9_]+)</', $response['content'], $matches);
		if (count($matches) < 2 || !isset($response['cookies']['PHPSESSID']) || !$response['cookies']['PHPSESSID']) {
			$data['error'] = 'An error occurred. It wasn\'t your fault though. Most likely the Path of Exile website has changed in a way that we haven\'t accounted for yet. Please try again later.';
		} else {
			// yep, success
			$_->log_in($matches[1], $_POST['email'], $response['cookies']['PHPSESSID']);
			$data['header']  = 'Log In Successful';
			$data['message'] = 'Now what?';
			render_page('system_message', $data['header'], $data);
		}
	} else {
		$data['error'] = 'An error occurred. You may have put in the wrong email or password. Or maybe the Path of Exile site is down. I don\'t really know but good luck tryin\' again!';
	}
}

render_page('login', 'Log In', $data);
?>