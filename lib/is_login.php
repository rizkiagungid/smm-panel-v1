<?php
/**
 * Penulis Kode - SMM Panel script
 * Domain: http://penuliskode.com/
 * Documentation: http://penuliskode.com/smm/script/version-n1/documentation.html
 *
 */

if (isset($_SESSION['login'])) {
	$login = $model->db_query($db, "*", "users", "id = '".$_SESSION['login']."'");
	if ($login['count'] <> 1) {
		exit(header("Location: ".$config['web']['base_url']."logout.php"));
	} elseif ($login['rows']['status'] <> 1) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Akun Anda dinonaktifkan.');
		exit(header("Location: ".$config['web']['base_url']."logout.php"));
	}
	$login = $login['rows'];
}