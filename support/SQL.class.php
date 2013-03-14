<?php
class SQL {
	var $db_link, $num_queries, $queries;

	// TODO: switch to mysqli

	function SQL($host, $username, $password, $pconnect = FALSE) {
		 $connect_func      = $pconnect ? 'mysql_pconnect' : 'mysql_connect';
		 $this->db_link     = $connect_func($host, $username, $password);
		 $this->num_queries = 0;
		 $this->queries     = array();
	}

	function select_db($database) {
		mysql_select_db($database, $this->db_link) or $this->throw_error('select_db');
	}

	function query($query) {
		$result = mysql_query($query, $this->db_link) or $this->throw_error('query', $query);

		$this->num_queries++;
		return $result;
	}

	function affected_rows() {
		$rows = mysql_affected_rows($this->db_link);
		return $rows;
	}

	function fetch_assoc($result) {
		$row = mysql_fetch_assoc($result) or array();
		return $row;
	}

	function fetch_row($result) {
		$row = mysql_fetch_row($result) or array();
		return $row;
	}

	function fetch_field($query) {
		$array = $this->fetch_row($this->query($query));
		return $array[0];
	}

	function num_rows($result) {
		$num_rows = mysql_num_rows($result);
		return $num_rows;
	}

	function insert_id() {
		$insert_id = mysql_insert_id($this->db_link);
		return $insert_id;
	}

	function result($result, $row) {
		$row = mysql_result($result, $row);
		return $row;
	}

	function compile_set_fields($array) {
		$output = array();

		foreach ($array as $field_name => $data) {
			$output[] = "`{$field_name}` = '".$this->real_escape_string($data)."'"; 
		}

		return implode(', ', $output);
	}

	function compile_array($result, $key = null) {
		$array = array();
	
		if (isset($key)) {
			while ($row = $this->fetch_assoc($result)) {
				$array[$row[$key]] = $row;
			}
	
			return $array;
		} else {
			while ($row = $this->fetch_assoc($result)) {
				$array[] = $row;
			}
	
			return $array;
		}
	}

	function escape_string($unescaped_string) {
		return mysql_real_escape_string($unescaped_string);
	}

	function real_escape_string($unescaped_string) {
		return mysql_real_escape_string($unescaped_string);
	}

	function throw_error($function, $note = null) {
		$error_note = $function.':'.(isset($note) ? " {$note}" : '')."\n\n".mysql_errno($this->db_link).':'.mysql_error($this->db_link);

		trigger_error($error_note, E_USER_ERROR);
	}
}
?>