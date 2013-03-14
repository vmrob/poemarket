<?php
class Application {
	var $config, $SQL;

	function Application($config) {
		$this->config = $config;
		$this->SQL = new SQL($config['mysql_host'], $config['mysql_username'], $config['mysql_password']);
	}
}
?>