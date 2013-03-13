<?php
function __autoload($class) {
	require_once $class.'.class.php';
}

$_ = new Application();
?>