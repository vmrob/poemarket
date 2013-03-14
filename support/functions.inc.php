<?php
function render_page($template, $title, $args = array()) {
	global $_;
	
	$args = array_merge(array(
		'page_title'    => $_->config['site_name'].' - '.$title, 
		'site_name'     => $_->config['site_name'],
		'site_revision' => $_->config['site_revision'],
	), $args);
	
	Template::Render('header',  $args);
	Template::Render($template, $args);
	Template::Render('footer',  $args);
}
?>