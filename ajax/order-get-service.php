<?php
require '../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_POST['category'])) {
		exit("No direct script access allowed!");
	}
	if (empty($_POST['category'])) {
		exit('<option value="0">Pilih Kategori...</option>');
	}
	$service = $model->db_query($db, "*", "services", "category_id = '".mysqli_real_escape_string($db, $_POST['category'])."' AND status = '1'");
	if ($service['count'] == 0) {
		print('<option value="0">Layanan tidak ditemukan...</option>');
	} else {
		print('<option value="0">Pilih...</option>');
		if ($service['count'] == 1) {
			print('<option value="'.$service['rows']['id'].'">'.$service['rows']['service_name'].'</option>');
		} else {
			foreach ($service['rows'] as $key) {
				print('<option value="'.$key['id'].'">'.$key['service_name'].'</option>');
			}
		}
	}
} else {
	exit("No direct script access allowed!");
}