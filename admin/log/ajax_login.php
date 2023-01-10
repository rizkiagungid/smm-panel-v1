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
	$table = 'login_logs';
	$primaryKey = 'id';

	$columns = array(
		array( 'db' => '`a`.`id`', 'dt' => 0, 'field' => 'id'),
		array( 'db' => '`b`.`username`', 'dt' => 1, 'field' => 'username'),
		array( 'db' => '`a`.`ip_address`', 'dt' => 2, 'field' => 'ip_address'),
		array( 'db' => '`a`.`created_at`', 'dt' => 3, 'field' => 'created_at')
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