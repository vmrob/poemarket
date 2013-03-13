<?php
function render_page($template, $title, $args = array()) {
	global $_;
	$args = array_merge(array('page_title' => $_->config['site_name'].' - '.$title), $args);
	Template::Render($template, $args);
}
?>