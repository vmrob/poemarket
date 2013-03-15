<?php
class Application {
	var $config, $sql, $user;

	// don't make any database queries in the constructor here
	// we don't even know if the database has been initialized yet
	function Application($config) {
		$this->config = $config;
		
		$this->sql = new SQL($config['mysql_host'], $config['mysql_username'], $config['mysql_password']);
		$this->sql->select_db($config['mysql_database']);
		
		$this->user = NULL;
	}
	
	function log_in($username, $email, $session) {
		$result = $this->sql->query("SELECT * FROM {$this->config['mysql_table_prefix']}users WHERE user_name = '{$this->sql->escape_string($username)}'");

		if ($this->sql->num_rows($result) < 1) {
			// new user, insert the row
			$fields = array(
				'user_name'  => $username,
				'user_email' => $email,
				'user_poe_session' => $session,
				'user_secret' => crypt($session.$email.microtime(), '$2y$10$'.sha1(mt_rand())), // can be any random string
			);

			$this->sql->query("INSERT INTO {$this->config['mysql_table_prefix']}users SET {$this->sql->compile_set_fields($fields)}");
			
			$result = $this->sql->query("SELECT * FROM {$this->config['mysql_table_prefix']}users WHERE user_name = '{$this->sql->escape_string($username)}'");
		}

		$this->user = $this->sql->fetch_assoc($result);
		
		$time = time();
		$hash = crypt($time.$this->user['user_secret'], '$2y$10$'.sha1(mt_rand()));
		setcookie('auth', "$username|$time|$hash",  $time + 60 * 60 * 24 * 90);
	}
	
	function check_authentication() {
		if (!isset($_COOKIE['auth'])) {
			return;
		}
		
		$parts = explode('|', $_COOKIE['auth'], 3);
		
		$name = $parts[0];
		$time = $parts[1];
		$hash = $parts[2];
		
		if (time() - $time > 60 * 60 * 24 * 90) {
			return;
		}
		
		$result = $this->sql->query("SELECT * FROM {$this->config['mysql_table_prefix']}users WHERE user_name = '{$this->sql->escape_string($name)}'");

		if ($this->sql->num_rows($result) < 1) {
			return;
		}
		
		$u = $this->sql->fetch_assoc($result);
		
		if (crypt($time.$u['user_secret'], $hash) != $hash) {
			return;
		}
		
		$this->user = $u;
	}
}
?>