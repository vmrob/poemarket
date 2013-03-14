<?php
$config = array(
	/* 
		The site's name.
	*/
	'site_name' => 'PoE Market',

	/*
		The site's revision number.
	*/
	'site_revision_number' => exec('git log -1 --format="%H"'),

	/*
		The site's revision time.
	*/
	'site_revision_time' => exec('git log -1 --format="%ar"'),
	
	/*
		Your MySQL server. Often it's simply 'localhost'.
	*/
	'mysql_host' => 'localhost',
	
	/*
		Your MySQL username.
	*/
	'mysql_username' => 'myusername',
	
	/*
		Your MySQL password.
	*/
	'mysql_password' => 'mypassword',
	
	/*
		All tables are prefixed with this. If you change this after creating the site, make sure you 
		rename all the tables in your database accordingly.
	*/
	'mysql_table_prefix' => 'poem',

	/*
		If you want this installation to automatically update itself, set this to a random, secret string.
		Then, when the server should update, send a request to update.php, passing this string as GET 
		parameter 'secret' (e.g. http://example.com/update.php?secret=myrandomsecretstring).
		
		You need to ensure that your web server has write access to the site's root directory and all of 
		its contents. You probably also need to ensure that safe mode is disabled.
	 */
	'update_secret' => '',

	/*
		If the 'update_secret' configuration option is set, this command will be executed when update.php 
		receives a request using the correct secret. It's executed from the directory of update.php. If 
		you're using a git repo, you probably don't need to change this. Update operations are performed 
		following this, so don't background anything crucial.
	*/
	'update_command' => 'cd ../ && git checkout master && git pull origin master && git clean -fd',
);

/*
	You may want to overwrite some of the configuration options per machine and keep things like passwords 
	or secrets out of source control. For that purpose you can create an optional untracked file named 
	local_config.inc.php that can override configuration options. The script should simply return an array 
	with the overrides. For example, the contents might look like this:
	
	<?php
	return array(
		'update_secret' => 'myrandomsecretstring',
		'site_name'     => 'PoE Market Staging',
	);
	?>
*/
$local = @include('local_config.inc.php');

return $local ? array_merge($config, $local) : $config;
?>