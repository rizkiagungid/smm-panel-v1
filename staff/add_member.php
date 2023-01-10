<?php
require '../mainconfig.php';
require '../lib/check_session.php';
require '../lib/is_login.php';
if ($login['level'] == 'Member') {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Hak Akses tidak sah.');
	exit(header("Location: ".$config['web']['base_url']));
}
if ($_POST) {
	$input_data = array('full_name', 'username', 'password');
	if (check_input($_POST, $input_data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	} else {
		$validation = array(
			'full_name' => $_POST['full_name'],
			'username' => trim($_POST['username']),
			'password' => trim($_POST['password']),
		);
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
		} elseif (strlen($validation['username']) < 5) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Username minimal 5 karakter.');
		} elseif (strlen($validation['password']) < 5) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Password minimal 5 karakter.');
		} elseif ($login['balance'] < $config['register']['price']['member']) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Saldo Anda tidak cukup untuk melakukan pendaftaran Member.');
		} else {
			$input_post = array(
				'level' => 'Member',
				'username' => strtolower($validation['username']),
				'password' => password_hash($validation['password'], PASSWORD_DEFAULT),
				'full_name' => $_POST['full_name'],
				'balance' => $config['register']['bonus']['member'],
				'api_key' => str_rand(30),
				'created_at' => date('Y-m-d H:i:s')
			);
			if ($model->db_query($db, "username", "users", "username = '".mysqli_real_escape_string($db, $input_post['username'])."'")['count'] > 0) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Username sudah terdaftar.');
			} else {
				if ($model->db_insert($db, "users", $input_post) == true) {
					$model->db_update($db, "users", array('balance' => $login['balance'] - $config['register']['price']['member']), "id = '".$login['id']."'");
					$model->db_insert($db, "balance_logs", array('user_id' => $login['id'], 'type' => 'minus', 'amount' => $config['register']['price']['member'], 'note' => 'Mendaftarkan Member. Username: '.$input_post['username'].'.', 'created_at' => date('Y-m-d H:i:s')));
					$_SESSION['result'] = array('alert' => 'success', 'title' => 'Member berhasil didaftarkan!', 'msg' => '<br />Username: '.$input_post['username'].'<br />Password: '.$validation['password']);
				} else {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Member gagal didaftarkan.');
				}
			}
		}
	}
}
require '../lib/header.php';
?>

						<div class="row">
							<div class="offset-lg-3 col-lg-6">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-plus"></i> Tambah Member</h4>
<div class="alert alert-info"><b>Biaya Pendaftaran:</b> Rp <?php echo number_format($config['register']['price']['member'],0,',','.') ?><br /><b>Bonus Saldo:</b> Rp <?php echo number_format($config['register']['bonus']['member'],0,',','.') ?></div>
<form class="form-horizontal" method="post">
	<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
	<div class="form-group">
		<label>Nama Lengkap</label>
		<input type="text" class="form-control" name="full_name">
	</div>
	<div class="form-group">
		<label>Username</label>
		<input type="text" class="form-control" name="username">
	</div>
	<div class="form-group">
		<label>Password</label>
		<input type="password" class="form-control" name="password">
	</div>
	<div class="form-group">
			<button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i> Reset</button>
			<button class="btn btn-success" type="submit"><i class="fa fa-check"></i> Submit</button>
	</div>
</form>
								</div>
							</div>
						</div>
<?php
require '../lib/footer.php';
?>