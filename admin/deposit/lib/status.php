<?php
require '../../../mainconfig.php';
require '../../../lib/check_session_admin.php';
if (!isset($_GET['id']) OR !isset($_GET['status'])) {
	$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan tidak diterima.');
} else if (in_array($_GET['status'], array('Pending','Canceled','Success')) == false) {
	$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
} else {
	$data_target = $model->db_query($db, "*", "deposits", "id = '".mysqli_real_escape_string($db, $_GET['id'])."' AND status = 'Pending'");
	if ($data_target['count'] == 0) {
		$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
	} else {
		if ($model->db_update($db, "deposits", array('status' => $_GET['status']), "id = '".$_GET['id']."'") == true) {
			if ($_GET['status'] == 'Success') {
				$data_target_user = $model->db_query($db, "*", "users", "id = '".$data_target['rows']['user_id']."'");
				$model->db_update($db, "users", array('balance' => $data_target_user['rows']['balance'] + $data_target['rows']['amount']), "id = '".$data_target['rows']['user_id']."'");
				$model->db_insert($db, "balance_logs", array('user_id' => $data_target['rows']['user_id'], 'type' => 'plus', 'amount' => $data_target['rows']['amount'], 'note' => 'Deposit saldo. ID Deposit: '.$_GET['id'].'.', 'created_at' => date('Y-m-d H:i:s')));
				$result_msg = array('alert' => 'success', 'title' => 'Permintaan deposit diterima!', 'msg' => '<br />ID Deposit: '.$_GET['id'].'<br />Pengguna: '.$data_target_user['rows']['username'].'<br />Saldo diterima: Rp '.number_format($data_target['rows']['amount'],0,',','.').'');
			} else {
				$result_msg = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Status berhasil diubah.');
			}
		} else {
			$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Status gagal diubah.');
		}
	}
}
require '../../../lib/result.php';