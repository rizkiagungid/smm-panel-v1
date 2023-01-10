<?php
require '../mainconfig.php';
require '../lib/check_session.php';
require '../lib/is_login.php';
if ($_POST) {
	$input_data = array('voucher_code');
	if (check_input($_POST, $input_data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	} else {
		$validation = array(
			'voucher_code' => $_POST['voucher_code']
        );
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
		} else {
            $voucher = $model->db_query($db, "*", "vouchers", "voucher_code = '".mysqli_real_escape_string($db, $_POST['voucher_code'])."' AND status = '1'");
			if ($voucher['count'] == 0) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Kode voucher tidak ditemukan.');
            } else if ($voucher['rows']['status'] == 0) {
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Kode voucher sudah tidak aktif/terpakai.');
            } else if (strlen($validation['voucher_code']) < 5) {
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input minimal 5 karakter.');
            } else {
			$input_post = array(
				'user_id' => $login['id'],
				'status' => "0"
            );
				if ($model->db_update($db, "vouchers", $input_post, "id = '".$voucher['rows']['id']."'") == true) {
					$model->db_update($db, "users", array('balance' => $login['balance'] + $voucher['rows']['amount']), "id = '".$login['id']."'");
					$model->db_insert($db, "balance_logs", array('user_id' => $login['id'], 'type' => 'plus', 'amount' => $voucher['rows']['amount'], 'note' => 'Deposit saldo (Redeem Voucher). ID Kode Voucher: '.$voucher['rows']['id'].'.', 'created_at' => date('Y-m-d H:i:s')));
					$_SESSION['result'] = array('alert' => 'success', 'title' => 'Deposit Berhasil!', 'msg' => '<br />ID Kode Voucher: '.$voucher['rows']['id'].'<br />Metode Deposit: Redeem Voucher<br />Jumlah/Saldo Diterima: Rp '.number_format($voucher['rows']['amount'],0,',','.'));
				} else {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Deposit gagal dibuat.');
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
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-money"></i> Redeem Voucher</h4>
<form class="form-horizontal" method="post">
	<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
	<div class="form-group">
		<label>Kode Voucher</label>
		<input type="text" class="form-control" name="voucher_code">
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