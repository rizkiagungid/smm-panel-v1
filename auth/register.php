<?php
require '../mainconfig.php';
if (isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']));
}
if ($_POST) {
	$data = array('full_name', 'username', 'password', 'confirm_password', 'captcha');
	if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	} else {
		$validation = array(
			'full_name' => protect_input($_POST['full_name']),
			'username' => protect_input($_POST['username']),
			'password' => protect_input($_POST['password']),
			'confirm_password' => protect_input($_POST['confirm_password']),
			'captcha' => protect_input($_POST['captcha'])
		);
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
		} else {
			$check_ip = $model->db_query($db, "ip_address", "register_logs", "ip_address = '".get_client_ip()."'");
		     if ($validation['password'] <> $validation['confirm_password']) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Konfirmasi password tidak sesuai.');
			} else if (strlen($validation['username']) < 5 OR strlen($validation['password']) < 5) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Username/Password minimal 5 karakter.');
			} else if (strlen($validation['username']) > 12 OR strlen($validation['password']) > 12) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Username/Password maksimal 12 karakter.');
			} else if ($validation['captcha'] !== "1") {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Silahkan pilih captcha.');
			} else {
				$input_post = array(
					'level' => 'Member',
					'username' => strtolower($validation['username']),
					'password' => password_hash($validation['password'], PASSWORD_DEFAULT),
					'full_name' => $_POST['full_name'],
					'balance' => 0,
					'api_key' => str_rand(30),
					'created_at' => date('Y-m-d H:i:s')
				);
				if ($model->db_query($db, "username", "users", "username = '".mysqli_real_escape_string($db, $input_post['username'])."'")['count'] > 0) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Username sudah terdaftar.');
				} else {
					$insert = $model->db_insert($db, "users", $input_post);
					if ($insert == true) {
						$model->db_insert($db, "register_logs", array('user_id' => $insert, 'ip_address' => get_client_ip(), 'created_at' => date('Y-m-d H:i:s')));
						$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => '<br />Username: '.$input_post['username'].'<br />Password: '.$validation['password']);
					} else {
						$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Member gagal didaftarkan.');
					}
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
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-plus fa-fw"></i>Daftar Gunakan Huruf Kecil Semua</h4>
									<form class="form-horizontal" method="post">
										<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
										<div class="form-group">
											<label>Nama Lengkap</label><input type="text" class="form-control" name="full_name">
										</div>
                                        <div class="form-group">
											<label>Username</label><input type="text" class="form-control" name="username">
										</div>
										<div class="form-group">
											<label>Password</label><input type="password" class="form-control" name="password">
										</div>
                                        <div class="form-group">
											<label>Konfirmasi Password</label><input type="password" class="form-control" name="confirm_password">
										</div>
                                        <div class="form-group">
                                            <label>Apakah anda manusia?</label>
                                                <select class="form-control" name="captcha">
                                                    <option value="0">Pilih...</option>
                                                    <option value="1">Ya</option>
                                                </select>
                                        </div>
										<div class="form-group">
												<button class="btn btn-success" type="submit"><i class="fa fa-check"></i> Submit</button>
										</div>
									</form>
								</div>
							</div>
						</div>
<?php
require '../lib/footer.php';
?>