<?php
$config = array(
	'site_name'  => 'PoE Market',
	'git_secret' => '',
);

$local = @include('local_config.inc.php');

return $local ? array_merge($config, $local) : $config;
?>