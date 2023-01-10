<?php
require '../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_POST['method'])) {
		exit("No direct script access allowed!");
	}
	if (empty($_POST['method'])) {
		exit('');
	}
	$method = $model->db_query($db, "*", "deposit_methods", "id = '".mysqli_real_escape_string($db, $_POST['method'])."'");
	if ($method['count'] == 0) {
		print('');
	} else {
		print('Minimal: Rp '.number_format($method['rows']['min_amount'],0,',','.'));
	}
} else {
	exit("No direct script access allowed!");
}