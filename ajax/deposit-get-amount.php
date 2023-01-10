<?php
require '../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_POST['method']) AND !isset($_POST['amount'])) {
		exit("No direct script access allowed!");
	}
	if (empty($_POST['method'])) {
		exit('0');
	}
	$method = $model->db_query($db, "*", "deposit_methods", "id = '".mysqli_real_escape_string($db, $_POST['method'])."'");
	if ($method['count'] == 0) {
		print('0');
	} else {
		print($method['rows']['rate']*$_POST['amount']);
	}
} else {
	exit("No direct script access allowed!");
}