<?php
function __autoload($class) {
	require_once $class.'.class.php';
}

require_once 'functions.inc.php';

// see if the site is locked down
if ($lock_html = site_lock_html()) {
	die($lock_html);
}

$config = require('config.inc.php');
$_ = new Application($config);
?>