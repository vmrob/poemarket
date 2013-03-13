<?php
class Application {
	var $config;
	
	function Application() {
		$this->config = require('config.inc.php');
	}
}
?>