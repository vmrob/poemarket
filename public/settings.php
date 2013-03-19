<?php
require_once dirname(dirname(__FILE__)).'/support/startup.inc.php';

require_login();

$currencies = array();

$result = $_->sql->query("SELECT * FROM {$_->config['mysql_table_prefix']}base_items WHERE base_item_type = 'currency'");

while ($row = $_->sql->fetch_assoc($result)) {
	$currencies[] = array_merge($row, array(
		'display_info' => json_decode($row['base_item_display_info']),
	));
}

$data = array(
	'currencies' => $currencies,
);

render_page('settings', 'Settings', $data);
?>