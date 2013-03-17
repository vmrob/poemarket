<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

require_login();

render_page('settings', 'Settings');
?>