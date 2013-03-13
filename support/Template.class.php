<?php
class Template {
	var $file, $args;

	function Template($file, $args = array()) {
		$this->file = $file;
		$this->args = $args;
	}

	function __get($name) {
		return $this->args[$name];
	}

	function __invoke() {
		require dirname(dirname(__FILE__)).'/templates/'.$this->file.'.tpl.php';
	}
	
	static function Render($file, $args = array()) {
		$tpl = new Template($file, $args);
		$tpl();
	}
}
?>