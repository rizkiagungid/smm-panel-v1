<?php
require '../mainconfig.php';
require '../lib/check_session.php';
if ($_POST) {
	require '../lib/is_login.php';
	$input_data = array('method', 'phone', 'amount');
	if (check_input($_POST, $input_data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	} else {
		$validation = array(
			'method' => $_POST['method'],
			'amount' => $_POST['amount'],
		);
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
		} else {
			$method = $model->db_query($db, "*", "deposit_methods", "id = '".mysqli_real_escape_string($db, $_POST['method'])."' AND status = '1'");
			$deposit_request = $model->db_query($db, "*", "deposits", "user_id = '".$login['id']."' AND status = 'Pending'");
			if ($method['count'] == 0) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Metode Deposit tidak ditemukan.');
			} else {
				if ($_POST['amount'] < $method['rows']['min_amount']) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Minimal deposit Rp '.number_format($method['rows']['min_amount'],0,',','.').'.');
				} elseif ($method['rows']['payment'] == 'pulsa' AND empty($_POST['phone'])) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Nomor Pengirim tidak boleh kosong.');
				} elseif ($deposit_request['count'] >= 1) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Anda tidak dapat membuat permintaan deposit lagi, karena Anda memiliki 1 permintaan berstatus <i>Pending</i>.');
				} else {
					$post_amount = $_POST['amount'];
					if ($method['rows']['payment'] == 'bank' AND $method['rows']['type'] == 'auto') {
						$post_amount = $_POST['amount'] + rand(000,999);
					}
					$input_post = array(
						'user_id' => $login['id'],
						'payment' => $method['rows']['payment'],
						'type' => $method['rows']['type'],
						'method_name' => $method['rows']['name'],
						'post_amount' => $post_amount,
						'amount' => $post_amount * $method['rows']['rate'],
						'note' => $method['rows']['note'],
						'phone' => $_POST['phone'],
						'status' => 'Pending',
						'created_at' => date('Y-m-d H:i:s')
					);
					$deposit_type = ($input_post['type'] == 'auto') ? 'Otomatis' : 'Manual';
					$insert = $model->db_insert($db, "deposits", $input_post);
					if ($insert == true) {
$tanggal = date('Y-m-d');
$kode = 'ulindzgn-L9rJn0P3EG5fSgi'; 
$bank = $method['rows']['name']; 

$url = "https://cekduit.my.id/api/input.php";

$curlHandle = curl_init();
curl_setopt($curlHandle, CURLOPT_URL, $url);
curl_setopt($curlHandle, CURLOPT_POSTFIELDS, "kode=".$kode."&jumlah=".$post_amount."&bank=".$bank."&tanggal=".$tanggal);
curl_setopt($curlHandle, CURLOPT_HEADER, 0);
curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
curl_setopt($curlHandle, CURLOPT_POST, 1);
$result = curl_exec($curlHandle);
curl_close($curlHandle);

$b = json_decode($result, true);
						$_SESSION['result'] = array('alert' => 'success', 'title' => 'Permintaan Deposit berhasil dibuat.', 'msg' => '<br />ID Deposit: '.$insert.'<br />Metode Deposit: '.$input_post['method_name'].' ('.$deposit_type.')<br />Jumlah Transfer: Rp '.number_format($input_post['post_amount'],0,',','.').'<br />Saldo Diterima: Rp '.number_format($input_post['amount'],0,',','.').'<br />'.$input_post['note']);
					} else {
						$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan Deposit gagal dibuat.');
					}
				}
			}
		}
	}
}
require '../lib/header.php';
?>
						<div class="row">
							<div class="col-lg-8">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-money"></i> Deposit Baru</h4>
<form class="form-horizontal" method="post" id="ajax-result">
	<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
	<div class="form-group">
		<label>Jenis Pembayaran</label>
			<select class="form-control" name="payment" id="payment">
				<option value="0">Pilih...</option>
				<option value="bank">Transfer Bank</option>
				<option value="pulsa">Transfer Pulsa</option>
			</select>
	</div>
	<div class="form-group">
		<label>Jenis Permintaan</label><br />
			<label><input type="radio" name="type" value="auto"> Otomatis</label><br />
			<label><input type="radio" name="type" value="manual"> Manual</label>
	</div>
	<div class="form-group">
		<label>Metode Pembayaran</label>
			<select class="form-control" name="method" id="method">
				<option value="0">Pilih Jenis Pembayaran & Permintaan...</option>
			</select>
	</div>
	<div class="form-group hide" id="phone">
		<label>Nomor Pengirim</label>
		<input type="number" class="form-control" name="phone" placeholder="08123456789">
	</div>
	<div class="form-group">
		<label>Jumlah</label>
		<input type="number" class="form-control" name="amount" placeholder="50000" id="post-amount"><small class="text-danger" id="min-amount"></small>
	</div>
	<div class="form-group">
		<label>Saldo Diterima</label><input type="number" class="form-control" id="amount" readonly>
	</div>
	<div class="form-group">
			<button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i> Reset</button>
			<button class="btn btn-success" type="submit"><i class="fa fa-check"></i> Submit</button>
	</div>
</form>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-info-circle"></i> Cara Melakukan Deposit Saldo</h4>
<h4>Langkah-langkah:</h4>
<ul>
	<li>Pilih jenis pembayaran yang Anda inginkan, tersedia 2 opsi: <b>Transfer Bank</b> & <b>Transfer Pulsa</b>.</li>
	<li>Pilih jenis permintaan yang Anda inginkan, tersedia 2 opsi:
		<ul>
			<li><b>Otomatis:</b> Pembayaran Anda akan dikonfirmasi secara otomatis oleh sistem dalam 5-10 menit setelah melakukan pembayaran.</li>
			<li><b>Manual:</b> Anda harus melakukan konfirmasi pembayaran pada Admin, agar permintaan deposit Anda dapat diterima.</li>
		</ul>
	</li>
	<li>Pilih metode pembayaran yang Anda inginkan.</li>
	<li>Masukkan jumlah deposit.</li>
	<li>Jika Anda memilih jenis pembayaran <b>Transfer Pulsa</b>, Anda diharuskan menginput nomor HP yang digunakan untuk transfer pulsa.</li>
</ul>
<h4>Penting:</h4>
<ul>
	<li>Anda hanya dapat memiliki maksimal 3 permintaan deposit Pending, jadi jangan melakukan <i>spam</i> dan segera lunasi pembayaran.</li>
	<li>Jika permintaan deposit tidak dibayar dalam waktu lebih dari 24 jam, maka permintaan deposit akan otomatis dibatalkan.</li>
</ul>
									</div>
								</div>
							</div>
<script type="text/javascript">
$(document).ready(function() {
	function get_methods(payment, type) {
		$.ajax({
			type: "POST",
			url: "<?php echo $config['web']['base_url'] ?>ajax/deposit-get-method.php",
			data: "payment=" + payment + "&type=" + type,
			dataType: "html",
			success: function(data) {
				$('#method').html(data);
			}, error: function() {
				$('#ajax-result').html('<font color="red">Terjadi kesalahan! Silahkan refresh halaman.</font>');
			}
		});
	}
	$('input[type=radio][name=type]').change(function() {
		get_methods($('#payment').val(), this.value);
	});
	$('#payment').change(function() {
		get_methods($('#payment').val(), $('input[type=radio][name=type]:checked').val());
		if ($('#payment').val() == 'pulsa') {
			$('#phone').removeClass('hide');
		} else {
			$('#phone').addClass('hide');
		}
	});
	$('#method').change(function() {
		var method = $('#method').val();
		$.ajax({
			type: "POST",
			url: "<?php echo $config['web']['base_url'] ?>ajax/deposit-select-method.php",
			data: "method=" + method,
			dataType: "html",
			success: function(data) {
				$('#min-amount').html(data);
			}, error: function() {
				$('#ajax-result').html('<font color="red">Terjadi kesalahan! Silahkan refresh halaman.</font>');
			}
		});
	});
	$('#post-amount').keyup(function() {
		var method = $('#method').val();
		var amount = $('#post-amount').val();
		$.ajax({
			type: "POST",
			url: "<?php echo $config['web']['base_url'] ?>ajax/deposit-get-amount.php",
			data: "method=" + method + "&amount=" + amount,
			dataType: "html",
			success: function(data) {
				$('#amount').val(data);
			}, error: function() {
				$('#ajax-result').html('<font color="red">Terjadi kesalahan! Silahkan refresh halaman.</font>');
			}
		});
	});
});
</script>
<?php
require '../lib/footer.php';
?>