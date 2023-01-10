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
	if (!isset($_GET['id']) OR !isset($_GET['status'])) {
		exit("No direct script access allowed!");
	}
	if (in_array($_GET['status'], array('1','0')) == false) {
		exit("No direct script access allowed!");
	}
	$data_target = $model->db_query($db, "*", "deposit_methods", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
	if ($data_target['count'] == 0) {
		$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
	} else {
		if ($model->db_update($db, "deposit_methods", array('status' => $_GET['status']), "id = '".$_GET['id']."'") == true) {
			$result_msg = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Status berhasil diubah.');
		} else {
			$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Status gagal diubah.');
		}
	}
	require '../../lib/result.php';
} else {
	exit("No direct script access allowed!");
}