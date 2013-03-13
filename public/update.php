<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

if (isset($_GET['secret']) && $_GET['secret'] == $_->config['git_secret']) {
	$dir = dirname(dirname(__FILE__));
	exec('(cd ../ && git checkout production && git pull origin production) &> /dev/null &');
}
?>