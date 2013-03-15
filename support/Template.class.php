<?php
class Template {
	var $name, $args;

	function Template($name, $args = array()) {
		$this->name = $name;
		$this->args = $args;
	}

	function __get($name) {
		return isset($this->args[$name]) ? $this->args[$name] : NULL;
	}

	function __invoke() {
		require dirname(dirname(__FILE__)).'/templates/'.$this->name.'.tpl.php';
	}

	static function Render($name, $args = array()) {
		$tpl = new Template($name, $args);
		$tpl();
	}
}
?>