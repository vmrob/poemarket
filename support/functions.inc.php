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
	return file_get_contents(dirname(__FILE__).'/lock.html');
}

// the site should always be locked when perform_update_operations() is called
function perform_update_operations() {
	// TODO: perform update operations
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
?>