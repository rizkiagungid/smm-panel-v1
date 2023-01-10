<?php
require '../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!");
	}
	require('../lib/ssp.class.php');
	$table = 'deposits';
	$primaryKey = 'id';

	$columns = array(
		array( 'db' => 'id', 'dt' => 0),
		array( 'db' => 'payment', 'dt' => 1, 'formatter' => function($i) {
			return ($i == 'bank') ? 'Transfer Bank' : 'Transfer Pulsa';
		}),
		array( 'db' => 'type', 'dt' => 2, 'formatter' => function($i) {
			return ($i == 'auto') ? 'Otomatis' : 'Manual';
		}),
		array( 'db' => 'method_name', 'dt' => 3),
		array( 'db' => 'post_amount',  'dt' => 4, 'formatter' => function($i) {
			return "Rp ".number_format($i,0,',','.');
		}),
		array( 'db' => 'amount',  'dt' => 5, 'formatter' => function($i) {
			return "Rp ".number_format($i,0,',','.');
		}),
		array( 'db' => 'note', 'dt' => 6),
		array( 'db' => 'status', 'dt' => 7, 'formatter' => function($i) {
			if ($i == 'Success') {
				$label = 'primary';
			} elseif ($i == 'Canceled') {
				$label = 'danger';
			} else {
				$label = 'warning';
			}
			return '<span class="label label-'.$label.'">'.$i.'</span>';
		}),
		array( 'db' => 'created_at', 'dt' => 8)
	);
	$sql_details = array(
		'user' => $config['db']['username'],
		'pass' => $config['db']['password'],
		'db'   => $config['db']['name'],
		'host' => $config['db']['host']
	);
	$joinQuery = null;
	$extraWhere = "user_id = '".$_SESSION['login']."'";
	$groupBy = '';
	$having = '';
	print(json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
	));
} else {
	exit("No direct script access allowed!");
}