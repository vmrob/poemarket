#!/usr/bin/env php
<?php
// this is a stand-alone script for a worker process that handles tasks queued up in the worker_tasks table

define('RELAUNCH_CODE', 100);

// if the script didn't receive a '--child' argument, it'll spawn a child process which will handle the work
// when the script changes, the child will exit and this will relaunch it
if (!in_array('--child', $_SERVER['argv'])) {
	while (true) {
		passthru('"'.escapeshellcmd(__FILE__).'" --child', $ret);
		if ($ret != RELAUNCH_CODE) {
			echo "unexpected worker termination\n";
			exit($ret); // something odd happened
		}
	}
	exit; // we should never get here
}

// store our own hash so we can detect changes
$self_hash = md5_file(__FILE__);

// change directory, get our configuration
chdir(dirname(__FILE__));
$config = require('config.inc.php');

// add one more item to our configuration: an identifier that should be unique to each worker
$config['worker_name'] = 'worker-'.getmypid().'-'.mt_rand(100000000, 999999999).'-'.substr($config['site_revision_number'], 0, 8);

// begin our work...

echo "{$config['worker_name']} launching\n";

// connect to the database
$sql = new mysqli($config['mysql_host'], $config['mysql_username'], $config['mysql_password'], $config['mysql_database']);

if ($sql->connect_error) {
	die("mysql connect error - {$sql->connect_error}\n");
}

// we'll call this every now and then to see if we're out-of-date
function check_self() {
	global $self_hash, $sql;

	if (md5_file(__FILE__) != $self_hash) {
		// we've changed, relaunch
		echo "change detected, relaunching\n";
		$sql->close();
		exit(RELAUNCH_CODE);
	}
}

// utility function...
function compile_set_fields($fields) {
	global $sql;

	$parts = array();

	foreach ($fields as $name => $value) {
		$parts[] = "`{$name}` = '{$sql->real_escape_string($value)}'"; 
	}

	return implode(', ', $parts);
}

// utility function...
function update_row($table, $key, $value, $fields) {
	global $sql, $config;
	
	$sql->query("
		UPDATE {$config['mysql_table_prefix']}{$table} 
		SET ".compile_set_fields($fields)." 
		WHERE `$key` = '{$sql->real_escape_string($value)}'
	");
}

// BEGIN TASK LOGIC

function handle_inventory_update_task($task) {
	// TODO: update the inventory
}

function handle_task($task) {
	global $config;

	echo "working on task {$task['task_id']}\n";

	if ($task['task_type'] == 'inventory-update') {
		handle_inventory_update_task($task);
	} else {
		// uhh, unknown task type? mark it as failed
		update_row('worker_tasks', 'task_id', $task['task_id'], array(
			'task_status'      => 'failed',
			'task_status_text' => "Failed. The assigned worker ({$config['worker_name']}) didn't know what to do.",
			'task_status_time' => time(),
		));
	}
}

// END TASK LOGIC

// loop infinitely, awaiting new tasks
while (true) {
	check_self();
	$sql->autocommit(false);
	$result = $sql->query("
		SELECT * FROM {$config['mysql_table_prefix']}worker_tasks 
		WHERE task_status = 'waiting' 
		ORDER BY task_time ASC, task_id ASC LIMIT 0, 1 
		FOR UPDATE
	");
	if ($task = $result->fetch_assoc()) {
		update_row('worker_tasks', 'task_id', $task['task_id'], array(
			'task_worker_name' => $config['worker_name'],
			'task_status'      => 'assigned',
			'task_status_text' => "Started.",
			'task_status_time' => time(),
		));
	}
	$result->close();
	$sql->commit();
	$sql->autocommit(true);
	if ($task) {
		handle_task($task);
	}
	sleep(1);
}

exit(1); // we should never reach this point
?>