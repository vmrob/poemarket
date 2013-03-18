<?php
require_once dirname(dirname(dirname(__FILE__))).'/support/startup.inc.php';

require_login();

$task = NULL;

$time = time();

if (isset($_GET['initiate'])) {
	// if an inventory update task has been assigned for more than 2 minutes with no update, put it back in the queue
	$_->sql->query("
		UPDATE {$_->config['mysql_table_prefix']}worker_tasks 
		SET task_status = 'waiting', task_status_text = 'Waiting.', task_status_time = '".$time."'
		WHERE task_type = 'inventory-update' AND task_status = 'assigned' AND task_status_time < '".($time - 60 * 2)."'
	");

	// if an inventory update task failed more than 2 minutes ago, erase it
	$_->sql->query("
		DELETE FROM {$_->config['mysql_table_prefix']}worker_tasks 
		WHERE task_type = 'inventory-update' AND task_status = 'failed' AND task_status_time < '".($time - 60 * 2)."'
	");

	$_->sql->start_transaction();
	
	$result = $_->sql->query("
		SELECT t.*, (SELECT COUNT(task_id) FROM {$_->config['mysql_table_prefix']}worker_tasks WHERE task_type = 'inventory-update' AND task_time <= t.task_time) AS queue_position 
		FROM {$_->config['mysql_table_prefix']}worker_tasks t 
		WHERE t.task_type = 'inventory-update' AND t.user_id = '{$_->sql->escape_string($_->user['user_id'])}'
	");
	
	if ($row = $_->sql->fetch_assoc($result)) {
		if ($row['task_status'] == 'failed') {
			$_->sql->query("DELETE FROM {$_->config['mysql_table_prefix']}worker_tasks WHERE task_id = '{$_->sql->escape_string($row['task_id'])}'");
		} else {
			$task = $row;
		}
	}
	
	if (!$task) {
		// add the new task
		$fields = array(
			'user_id'          => $_->user['user_id'],
			'task_type'        => 'inventory-update',
			'task_time'        => $time,
			'task_status'      => 'waiting',
			'task_status_text' => 'Waiting.',
			'task_status_time' => $time,
		);
		
		$_->sql->query("INSERT INTO {$_->config['mysql_table_prefix']}worker_tasks SET {$_->sql->compile_set_fields($fields)}");
			
		$result = $_->sql->query("
				SELECT t.*, (SELECT COUNT(task_id) FROM {$_->config['mysql_table_prefix']}worker_tasks WHERE task_type = 'inventory-update' AND task_time <= t.task_time) AS queue_position 
				FROM {$_->config['mysql_table_prefix']}worker_tasks t 
				WHERE t.task_id = '{$_->sql->escape_string($_->sql->insert_id())}'
			");
			
		$task = $_->sql->fetch_assoc($result);
	}
	
	$_->sql->commit();
} else {
	// get the existing task
	$result = $_->sql->query("
		SELECT t.*, (SELECT COUNT(task_id) FROM {$_->config['mysql_table_prefix']}worker_tasks WHERE task_type = 'inventory-update' AND task_time <= t.task_time) AS queue_position 
		FROM {$_->config['mysql_table_prefix']}worker_tasks t
		WHERE t.task_type = 'inventory-update' AND t.user_id = '{$_->sql->escape_string($_->user['user_id'])}'
	");
	
	$task = $_->sql->fetch_assoc($result);
}

if ($task) {
	$data = array(
		'queue_position' => ($task['task_status'] == 'waiting' ? $task['queue_position'] : 0),
		'status' => $task['task_status'],
		'status_text' => (
			$task['task_status'] == 'waiting' ? "{$task['task_status_text']} (Position in queue: {$task['queue_position']}.)" : 
			($task['task_status'] == 'assigned' ? "{$task['task_status_text']} (Assigned to {$task['task_worker_name']}.)" : $task['task_status_text'])
		),
	);
} else {
	$data = array(
		'queue_position' => 0,
		'status' => 'completed',
		'status_text' => 'Completed.',
	);
}

die(json_encode($data));
?>
