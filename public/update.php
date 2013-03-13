<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

if (isset($_GET['secret']) && $_GET['secret'] == $_->config['git_secret']) {
	exec('(cd ../ && git checkout production && git pull origin production && git clean -fd) &> /dev/null &');
}
?>