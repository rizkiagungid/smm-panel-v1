<?php
require '../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_POST['service'])) {
		exit("No direct script access allowed!");
	}
	header('Content-Type: application/json');
	if (empty($_POST['service'])) {
		$result = array('min' => '0', 'max' => '0', 'description' => 'Deskripsi layanan.', 'price' => 1);
	} else {
		$service = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_POST['service'])."'");
		if ($service['count'] == 0) {
			$result = array('min' => '0', 'max' => '0', 'description' => 'Deskripsi layanan.', 'price' => 2);
		} else {
			$result = array('min' => number_format($service['rows']['min'],0,',','.'), 
				'max' => number_format($service['rows']['max'],0,',','.'), 
				'description' => nl2br($service['rows']['note']), 
				'price' => $service['rows']['price']
			);
			
		}
	}
	print(json_encode($result));
} else {
	exit("No direct script access allowed!");
}