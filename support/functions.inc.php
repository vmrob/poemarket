<?php
function render_page($template, $title, $args = array()) {
	global $_;
	
	$args = array_merge(array(
		'page_title'           => $_->config['site_name'].' - '.$title, 
		'site_name'            => $_->config['site_name'],
		'site_revision_number' => $_->config['site_revision_number'],
		'site_revision_time'   => $_->config['site_revision_time'],
	), $args);

	Template::Render('header',  $args);
	Template::Render($template, $args);
	Template::Render('footer',  $args);
}

function try_site_lock($lock_html) {
	$lock_file = fopen(dirname(__FILE__).'/lock.html', 'x');

	if ($lock_file) {
		fwrite($lock_file, $lock_html);
		fclose($lock_file);
		return TRUE;
	}
	
	return FALSE;
}

function site_unlock() {
	unlink(dirname(__FILE__).'/lock.html');
}

function site_lock_html() {
	return @file_get_contents(dirname(__FILE__).'/lock.html');
}

function post_value_attrib($name, $default = NULL) {
	if (isset($_POST[$name])) {
		return ' value="'.htmlspecialchars($_POST[$name]).'"';
	} else if ($default) {
		return ' value="'.htmlspecialchars($default).'"';
	} else {
		return '';
	}
}

function get_key_value($key, $default = NULL) {
	global $_;
	$result = $_->SQL->query("SELECT value FROM {$_->config['mysql_table_prefix']}key_value WHERE `key` = '".$_->SQL->escape_string($key)."'");
	return $_->SQL->num_rows($result) < 1 ? $default : $_->SQL->result($result, 0);
}

function set_key_value($key, $value) {
	global $_;
	$_->SQL->query("INSERT INTO {$_->config['mysql_table_prefix']}key_value (`key`, `value`) VALUES ('".$_->SQL->escape_string($key)."', '".$_->SQL->escape_string($value)."') ON DUPLICATE KEY UPDATE `value` = '".$_->SQL->escape_string($value)."'");
}

function last_update_operation_index() {
	global $_;
	$result = $_->SQL->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '".$_->SQL->escape_string($_->config['mysql_database'])."' AND table_name = '{$_->config['mysql_table_prefix']}key_value'");
	if ($_->SQL->num_rows($result) < 1) {
		// table doesn't exist. assume database isn't initialized
		return -1;
	}
	return intval(get_key_value('last_update_operation_index', -1));
}

function needs_update_operations() {
	return last_update_operation_index() + 1 < count((require('update_operations.inc.php')));
}

function perform_update_operations() {
	global $_;

	$ops = require('update_operations.inc.php');

	for ($i = last_update_operation_index() + 1; $i < count($ops); ++$i) {
		$ops[$i]($_);
	}
	
	set_key_value('last_update_operation_index', count($ops) - 1);
}

function http_request($url, $params = NULL, $cookies = array(), $redirects = 0) {
	$ch = curl_init();
	
	$cookie_strings = array();
	foreach ($cookies as $k => $v) {
		$cookie_strings[] = urlencode($k).'='.urlencode($v);
	}
	$cookie_string = implode('; ', $cookie_strings);	

	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_POST => ($params === NULL ? 0 : 1),
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 5,
		CURLOPT_POSTFIELDS => $params,
		CURLOPT_HEADER => 1,
		CURLOPT_COOKIE => $cookie_string,
	));

	$result = curl_exec($ch);
	$info   = curl_getinfo($ch);

	curl_close($ch);
	
	if (!$info) {
		return NULL;
	}

	$parts = preg_split('/(\r\n|\n){2,}/', $result, 2);
	while ($parts[0] == 'HTTP/1.1 100 Continue') {
		$parts = preg_split('/(\r\n|\n){2,}/', $parts[1], 2);
	}
	
	$header_lines = preg_split('/(\r\n|\n)/', $parts[0]);
	array_shift($header_lines);
	$headers = array();
	foreach ($header_lines as $line) {
		$p = explode(':', $line, 2);
		$name = trim($p[0]);
		$value = trim($p[1]);
		if (!isset($headers[$name])) {
			$headers[$name] = array();
		}
		$headers[$name][] = $value;
	}

	$content = $parts[1];
	
	if (isset($headers['Set-Cookie'])) {
		foreach ($headers['Set-Cookie'] as $c) {
			$parts = explode(';', $c, 2);
			$parts = explode('=', $parts[0], 2);
			$cookies[urldecode($parts[0])] = urldecode($parts[1]);
		}
	}

	if ($info['http_code'] == 302) {
		return $redirects < 3 ? http_request($info['redirect_url'], NULL, $cookies, $redirects + 1) : NULL;
	}

	$info['headers'] = $headers;
	$info['content'] = $content;
	$info['cookies'] = $cookies;
	return $info;
}
?>