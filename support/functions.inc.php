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
?>