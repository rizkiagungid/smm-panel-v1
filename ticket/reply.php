<?php
require '../mainconfig.php';
require '../lib/check_session.php';
if (isset($_GET['id'])) {
	$data_target = $model->db_query($db, "*", "tickets",  "id = '".$_GET['id']."'");
	if ($data_target['count'] == 0) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal', 'msg' => 'Tiket Tidak Ditemukan');
		exit(header("Location: ".$config['web']['base_url']."ticket/"));
	} else {
		$user_data = $model->db_query($db, "*", "users",  "id = '".$data_target['rows']['user_id']."'");
		if ($_POST) {
			$input_name = array('msg');
			if (check_input($_POST, $input_name) == false) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
			} else {
				$validation = array(
					'msg' => $_POST['msg'],
				);
				if (check_empty($validation) == true) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
				} else if ($data_target['rows']['status'] == "Closed") {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Tiket sudah ditutup.');
				} else if (strlen($validation['msg']) > 500){
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesan tidak boleh melebihi 500 karakter.');
				} else {
					$input_post = array(
						'ticket_id' => $_GET['id'],
						'is_admin' => 0,
						'msg' => protect_input($_POST['msg']),
						'created_at' => date('Y-m-d H:i:s')
					);
					$insert = $model->db_insert($db, "ticket_replies", $input_post);
					$check_reply = $model->db_query($db, "*", "ticket_replies",  "ticket_id = '".$_GET['id']."' AND is_admin = '1'");
					if ($check_reply['count'] > 0) {
						$model->db_update($db, "ticket_replies", array('is_admin' => 0), "msg = '".$_POST['msg']."'");
					}
					if ($insert == true) {
						$model->db_update($db, "tickets", array('status' => "Waiting", 'updated_at' => date('Y-m-d H:i:s')), "id = '".$_GET['id']."'");
						$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!.', 'msg' => 'Tiket berhasil dibalas');
					} else {
						$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Tiket gagal dibalas.');
					}
				}
			}
		}
	}
}
require '../lib/header.php';
?>

                        <!-- end row -->
				<div class="row">
					<div class="offset-md-2 col-md-8">
						<a href="<?php echo $config['web']['base_url'] ?>ticket" class="btn btn-primary" style="margin-bottom: 20px;"><i class="fa fa-arrow-left fa-fw"></i> Kembali</a>
						<div class="card-box">
							<h4 class="header-title m-t-0 m-b-30"><i class="fa fa-edit"></i> Balas Tiket: <?php echo $data_target['rows']['subject']; ?></h4>
								<div class="card-box" style="max-height: 400px; overflow: auto;">
									<div class="alert alert-primary" style="color: #000">
										<span class="pull-right"><?php echo format_date(substr($data_target['rows']['created_at'], 0, -9)).", ".substr($data_target['rows']['created_at'], -8) ?></span>
										<b><?php echo $user_data['rows']['username']; ?></b><p style="margin: 5px 0 0 0; overflow: auto;"><?php echo $data_target['rows']['msg'] ?></p>
									</div>
<?php
$data_ticket = mysqli_query($db, "SELECT * FROM ticket_replies WHERE ticket_id = '".$_GET['id']."'");
while ($data_query = mysqli_fetch_array($data_ticket)) {				
	if ($data_query['is_admin'] == "1") {
		$label = "success";
		$text = "pull-right";
		$sender = "Admin";
	} else {
		$label = "info";
		$text = "pull-right";
		$sender = $user_data['rows']['username'];
	}
?>
									<div class="alert alert-<?php echo $label; ?>" style="color: #000">
										<span class="<?php echo $text; ?>"><?php echo format_date(substr($data_query['created_at'], 0, -9)).", ".substr($data_query['created_at'], -8) ?></span>
										<b><?php echo $sender; ?></b><p style="margin: 5px 0 0 0; overflow: auto;"><?php echo $data_query['msg'] ?></p>
									</div>
										
<?php
}
?>
								</div>
								<form method="post">
										<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>"/>
										<div class="form-group">
											<label>Pesan</label>
											<textarea class="form-control" name="msg"></textarea>
										</div>
										<button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i> Reset</button>
										<button class="btn btn-success" type="submit"><i class="fa fa-check"></i> Submit</button>
								</form>
						</div>
					</div>
				</div>
<?php require '../lib/footer.php'; ?>