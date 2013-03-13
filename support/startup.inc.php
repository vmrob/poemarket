<?php
function __autoload($class) {
	require_once $class.'.class.php';
}

require_once 'functions.inc.php';

$_ = new Application();
?>