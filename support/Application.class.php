<?php
class Application {
	var $config, $SQL, $user;

	function Application($config) {
		$this->config = $config;
		
		$this->SQL = new SQL($config['mysql_host'], $config['mysql_username'], $config['mysql_password']);
		$this->SQL->select_db($config['mysql_database']);
		
		$user = NULL;
	}
	
	function log_in() {
	}
}
?>