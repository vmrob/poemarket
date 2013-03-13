<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

if (isset($_GET['secret']) && $_GET['secret'] == $_->config['git_secret']) {
	exec('(cd ../ && git checkout master && git pull origin master && git clean -fd) &> /dev/null &');
}
?>