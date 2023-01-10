<?php
require '../mainconfig.php';
require '../lib/check_session.php';
if ($_POST) {
	require '../lib/is_login.php';
    $input_name = array('subject', 'msg');
	if (check_input($_POST, $input_name) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	} else {
		$validation = array(
			'subject' => $_POST['subject'],
			'msg' => $_POST['msg']
		);
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
		} else if (strlen($validation['subject']) > 200) {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Subjek tidak boleh melebihi 200 karakter');	
        } else if (strlen($validation['msg']) > 500) {
            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesan tidak boleh melebihi 500 karakter');
        } else {
            $input_post = array(
				'user_id' => $login['id'],
				'subject' => protect_input($_POST['subject']),
				'msg' => protect_input($_POST['msg']),
				'status' => "Waiting",
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
            );
            if ($model->db_insert($db, "tickets", $input_post) == true) {
                $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Tiket berhasil dikirim.');
            } else {
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Tiket gagal dikirim.');
            }
        }
    }
}
require '../lib/header.php';
?>
                        <div class="row">
                            <div class="offset-md-2 col-md-8">
                                <div class="card-box">
                                <h4 class="header-title m-t-0 m-b-30"><i class="fa fa-edit"></i> Kirim Tiket</h4>
                                <form method="post">
	<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>" />
	<div class="form-group">
		<label>Subjek</label>
		<input type="text" class="form-control" name="subject" value="">
	</div>
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
<?php 
include '../lib/footer.php';
?>             