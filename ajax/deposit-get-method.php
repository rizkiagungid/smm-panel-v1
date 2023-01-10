<?php
require '../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_POST['payment']) OR !isset($_POST['type'])) {
		exit("No direct script access allowed!");
	}
	if (empty($_POST['payment']) OR empty($_POST['type'])) {
		exit('<option value="0">Pilih Jenis Pembayaran & Permintaan...</option>');
	}
	$method = $model->db_query($db, "*", "deposit_methods", "payment = '".mysqli_real_escape_string($db, $_POST['payment'])."' AND type = '".mysqli_real_escape_string($db, $_POST['type'])."' AND status = '1'");
	if ($method['count'] == 0) {
		print('<option value="0">Metode Deposit tidak ditemukan...</option>');
	} else {
		print('<option value="0">Pilih...</option>');
		if ($method['count'] == 1) {
			print('<option value="'.$method['rows']['id'].'">'.$method['rows']['name'].'</option>');
		} else {
			foreach ($method['rows'] as $key) {
				print('<option value="'.$key['id'].'">'.$key['name'].'</option>');
			}
		}
	}
} else {
	exit("No direct script access allowed!");
}