<?php
require '../../../mainconfig.php';
require '../../../lib/check_session_admin.php';

if (!isset($_GET['id'])) {
	$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan tidak diterima.');
}  else {
	$data_target = $model->db_query($db, "*", "users", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
	if ($data_target['count'] == 0) {
		$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
	} else {
		if ($model->db_update($db, "users", array('api_key' => str_rand(30)), "id = '".$_GET['id']."'") == true) {
			$result_msg = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'API Key berhasil diubah.');
		} else {
			$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'API Key gagal diubah.');
		}
	}
}
require '../../../lib/result.php';