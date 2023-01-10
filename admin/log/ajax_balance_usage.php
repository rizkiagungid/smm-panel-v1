<?php
/**
 * Penulis Kode - SMM Panel script
 * Domain: http://penuliskode.com/
 * Documentation: http://penuliskode.com/smm/script/version-n1/documentation.html
 *
 */

require '../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!");
	}
	if ($model->db_query($db, "*", "users", "id = '".$_SESSION['login']."' AND level = 'Admin'")['count'] == 0) {
		exit("No direct script access allowed!");
	}
	require('../../lib/ssp.class.php');
	$table = 'balance_logs';
	$primaryKey = 'id';

	$columns = array(
		array( 'db' => '`a`.`id`', 'dt' => 0, 'field' => 'id'),
		array( 'db' => '`b`.`username`', 'dt' => 1, 'field' => 'username'),
		array( 'db' => '`a`.`type`', 'dt' => 2, 'formatter' => function($i) {
			return ($i == 'plus') ? 'Penambahan' : 'Pengurangan';
		}, 'field' => 'type'),
		array( 'db' => '`a`.`amount`',  'dt' => 3, 'formatter' => function($i, $a) {
			if ($a['2'] == 'plus') {
				$icon = "plus";
				$label = 'primary';
			} else {
				$icon = "minus";
				$label = 'danger';
			}
			return "<span class=\"label label-".$label."\"><i class=\"fa fa-".$icon." fa-fw\"></i> Rp ".number_format($i,0,',','.')."</span>";
		}, 'field' => 'amount'),
		array( 'db' => '`a`.`note`', 'dt' => 4, 'field' => 'note'),
		array( 'db' => '`a`.`created_at`', 'dt' => 5, 'field' => 'created_at')
	);
	$sql_details = array(
		'user' => $config['db']['username'],
		'pass' => $config['db']['password'],
		'db'   => $config['db']['name'],
		'host' => $config['db']['host']
	);
	$joinQuery = "FROM `{$table}` AS `a` LEFT JOIN `users` AS `b` ON (`b`.`id` = `a`.`user_id`)";
	$extraWhere = '';
	$groupBy = '';
	$having = '';
	print(json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
	));
} else {
	exit("No direct script access allowed!");
}