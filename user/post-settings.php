<?php
require '../mainconfig.php';
require '../lib/check_session.php';
require '../lib/is_login.php';
require '../lib/csrf_token.php';

if (!isset($_GET['action'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	exit(header("Location: ".$config['web']['base_url']."user/settings.php"));
} elseif (in_array($_GET['action'], array('profile','password', 'api_key')) == false) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	exit(header("Location: ".$config['web']['base_url']."user/settings.php"));
}

if ($_GET['action'] == 'profile') {
	if ($_POST) {
		$input_data = array('full_name', 'password');
		if (check_input($_POST, $input_data) == false) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
		} else {
			$input_post = array(
				'full_name' => $_POST['full_name'],
				'password' => $_POST['password'],
			);
			if (check_empty($input_post) == true) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
			} else {
				if (password_verify($input_post['password'], $login['password']) == false) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Password salah.');
				} else {
					if ($model->db_update($db, "users", array('full_name' => $input_post['full_name']), "id = '".$login['id']."'")) {
						$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Profil berhasil diubah.');
					} else {
						$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Profil gagal diubah.');
					}
				}
			}
		}
	}
	exit(header("Location: ".$config['web']['base_url']."user/settings.php"));
} elseif ($_GET['action'] == 'password') {
	if ($_POST) {
		$input_data = array('password', 'new_password', 'new_password2');
		if (check_input($_POST, $input_data) == false) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
		} else {
			$input_post = array(
				'password' => $_POST['password'],
				'new_password' => $_POST['new_password'],
				'new_password2' => $_POST['new_password2'],
			);
			if (check_empty($input_post) == true) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
			} else {
				if (password_verify($input_post['password'], $login['password']) == false) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Password salah.');
				} elseif (strlen($input_post['new_password']) < 5) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Password minimal 5 karakter.');
				} elseif ($input_post['new_password'] <> $input_post['new_password2']) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Konfirmasi Password Baru salah.');
				} else {
					if ($model->db_update($db, "users", array('password' => password_hash($input_post['new_password'], PASSWORD_DEFAULT)), "id = '".$login['id']."'")) {
						$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Password berhasil diubah.');
					} else {
						$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Password gagal diubah.');
					}
				}
			}
		}
	}
	exit(header("Location: ".$config['web']['base_url']."user/settings.php"));
} elseif ($_GET['action'] == 'api_key') {
	$model->db_update($db, "users", array('api_key' => str_rand(30)), "id = '".$login['id']."'");
	$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'API Key berhasil dibuat ulang.');
	exit(header("Location: ".$config['web']['base_url']."api/documentation.php"));
}
?>