<?php
function __autoload($class) {
	require_once $class.'.class.php';
}

require_once 'functions.inc.php';

$config = require('config.inc.php');
$_ = new Application($config);
?>