<?php
// see if the site is locked down
if ($lock_html = file_get_contents(dirname(__FILE__).'/lock.html')) {
	die($lock_html);
}

function __autoload($class) {
	require_once $class.'.class.php';
}

require_once 'functions.inc.php';

$config = require('config.inc.php');
$_ = new Application($config);
?>