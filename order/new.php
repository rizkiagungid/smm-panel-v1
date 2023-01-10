<?php
require '../mainconfig.php';
require '../lib/check_session.php';
if ($_POST) {
	require '../lib/is_login.php';
	$input_data = array('service', 'data', 'quantity');
	if (check_input($_POST, $input_data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	} else {
		$validation = array(
			'service' => $_POST['service'],
			'data' => $_POST['data'],
		);
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
		} else {
			$service = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_POST['service'])."' AND status = '1'");
			if ($service['count'] == 0) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Layanan tidak ditemukan.');
			} else {
				$total_price = ($service['rows']['price'] / 1000) * $_POST['quantity'];
				$total_profit = ($service['rows']['profit'] / 1000) * $_POST['quantity'];
				$provider = $model->db_query($db, "*", "provider", "id = '".$service['rows']['provider_id']."'");
				if ($provider['count'] == 0) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maaf saat ini layanan tidak tersedia, silakan kontak Adminn.');
				} elseif ($_POST['quantity'] < $service['rows']['min']) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Jumlah minimal pesan '.number_format($service['rows']['min'],0,',','.').'.');
				} elseif ($_POST['quantity'] > $service['rows']['max']) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Jumlah maksimal pesan '.number_format($service['rows']['max'],0,',','.').'.');
				} elseif ($login['balance'] < $total_price) {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Saldo Anda tidak cukup untuk membuat pesanan<p><span style="color: blue;">silakan isi saldo</span><a href="#"><span style="color: magenta;">&nbsp;disini</span></a></p> sisa saldo anda saat ini Rp '.number_format($login['balance'],0,',','.').'.');
				} else {
					$result_api = false;
					$curl = '';
					$provider_order_id = '1';
					if ($service['rows']['provider_id'] == '1') { 
                       	$post_api = array(
                            	'api_key' => $provider['rows']['api_key'], // api key Anda
                            	'action' => 'order',
                            	'service' => $service['rows']['provider_service_id'], // id layanan
                            	'data' => $_POST['data'],
                            	'quantity' => $_POST['quantity']
                            );
						$curl = post_curl($provider['rows']['api_url_order'], $post_api);
						$result = json_decode($curl, true);
						if (isset($result['status']) AND $result['status'] == true) {
							$provider_order_id = $result['data']['id'];
							$result_api = true;
						}
			     	}
					if ($result_api == false) {
						$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maaf saat ini layanan tidak tersedia, silakan Pakai Layanan Lain nya.');
					} else {
						$input_post = array(
							'user_id' => $login['id'],
							'service_name' => $service['rows']['service_name'],
							'data' => $_POST['data'],
							'quantity' => $_POST['quantity'],
							'price' => $total_price,
							'profit' => $total_profit,
							'remains' => $_POST['quantity'],
							'status' => 'Pending',
							'provider_id' => $service['rows']['provider_id'],
							'provider_order_id' => $provider_order_id,
							'created_at' => date('Y-m-d H:i:s'),
							'api_order_log' => $curl
						);
						$insert = $model->db_insert($db, "orders", $input_post);
						if ($insert == true) {
							$model->db_update($db, "users", array('balance' => $login['balance'] - $total_price), "id = '".$login['id']."'");
							$model->db_insert($db, "balance_logs", array('user_id' => $login['id'], 'type' => 'minus', 'amount' => $total_price, 'note' => 'Membuat Pesanan. ID Pesanan: '.$insert.'.', 'created_at' => date('Y-m-d H:i:s')));
							$_SESSION['result'] = array('alert' => 'success', 'title' => 'Pesanan berhasil dibuat.', 'msg' => '<br />ID Pesanan: '.$insert.'<br />Layanan: '.$input_post['service_name'].'<br />Jumlah Pesan: '.number_format($input_post['quantity'],0,',','.').'<br />Total Harga: Rp '.number_format($total_price,0,',','.').'');
						} else {
							$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesanan gagal dibuat.');
						}
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
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-shopping-cart"></i> Pesan Baru</h4>
<form class="form-horizontal" method="post" id="ajax-result">
	<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
	<div class="form-group">
		<label>Kategori</label>
			<select class="form-control" id="category">
				<option value="0">Pilih...</option>
<?php
$category = $model->db_query($db, "*", "categories", null, "name ASC");
if ($category['count'] == 1) {
	print('<option value="'.$category['rows']['id'].'">'.$category['rows']['name'].'</option>');
} else {
foreach ($category['rows'] as $key) {
	print('<option value="'.$key['id'].'">'.$key['name'].'</option>');
}
}
?>
			</select>
	</div>
	<div class="form-group">
		<label>Layanan</label>
			<select class="form-control" name="service" id="service">
				<option value="0">Pilih Kategori...</option>
			</select>
	</div>
	<div class="form-group">
		<label>Deskripsi</label>
		<div class="form-control" id="description" style="height: auto !important;">Deskripsi layanan.</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-4">
			<label>Harga/K</label>
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text">Rp</span>
				</div>
				<span class="form-control" id="price"></span>
			</div>
		</div>
		<div class="form-group col-md-4">
			<label>Min. Pesan</label>
			<span class="form-control" id="min">0</span>
		</div>
		<div class="form-group col-md-4">
			<label>Maks. Pesan</label>
			<span class="form-control" id="max">0</span>
		</div>
	</div>
	<div class="form-group">
		<label>Data</label>
		<input type="text" class="form-control" name="data" placeholder="Link/username">
	</div>
	<div class="form-group">
		<label>Jumlah Pesan</label>
		<input type="number" class="form-control" name="quantity" placeholder="1000" id="quantity">
	</div>
	<input type="hidden" id="rate">
	<div class="form-group">
		<label>Total Harga</label>
		<input type="text" class="form-control" id="total-price" readonly><small class="text-danger">*Inputkan jumlah pesan</small>
	</div>
	<div class="form-group">
			<button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i> Reset</button>
			<button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Submit</button>
	</div>
</form>
							     </div> 
								</div> 
								<div class="col-lg-4">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-info-circle fa-fw"></i> Informasi</h4>
									<p>
										<div class="card-body">
											<b>Langkah-langkah membuat pesanan baru:</b>
											<ul>
											<li>Pilih salah satu Kategori.</li>
											<li>Pilih salah satu Layanan yang ingin dipesan.</li>
											<li>Masukkan Target pesanan sesuai ketentuan yang diberikan layanan tersebut.</li>
											<li>Masukkan Jumlah Pesanan yang diinginkan.</li>
											<li>Klik Submit untuk membuat pesanan baru.</li>
											</ul>
											<b>Ketentuan membuat pesanan baru:</b>
											<ul>
											<li>Tidak ada reffund jika ada kesalahan order.</li>
											<li>Silahkan membuat pesanan sesuai langkah-langkah diatas.</li>
											<li>Jika ingin membuat pesanan dengan Target yang sama dengan pesanan yang sudah pernah dipesan sebelumnya, mohon menunggu sampai pesanan sebelumnya selesai diproses.</li>
											<li>Jika terjadi kesalahan / mendapatkan pesan gagal yang kurang jelas, silahkan hubungi Admin untuk informasi lebih lanjut.</li>
											</ul>
												<b><?php echo $config['web']['title'] ?></b>
										</div>			
									</p>
								</div> 
							</div>
						</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#category').change(function() {
		var category = $('#category').val();
		$.ajax({
			type: "POST",
			url: "<?php echo $config['web']['base_url'] ?>ajax/order-get-service.php",
			data: "category=" + category,
			dataType: "html",
			success: function(data) {
				$('#service').html(data);
				$('#description').html('Deskripsi layanan.');
				$('#min').html('0');
				$('#max').html('0');
				$('#price').val('0');
			}, error: function() {
				$('#ajax-result').html('<font color="red">Terjadi kesalahan! Silahkan refresh halaman.</font>');
			}
		});
	});
	$('#service').change(function() {
		var service = $('#service').val();
		$.ajax({
			type: "POST",
			url: "<?php echo $config['web']['base_url'] ?>ajax/order-select-service.php",
			data: "service=" + service,
			dataType: "json",
			success: function(data) {
				$('#description').html(data.description);
				$('#min').html(data.min);
				$('#max').html(data.max);
				$('#price').html(data.price);
				$('#rate').val(data.price);
			}, error: function() {
				$('#ajax-result').html('<font color="red">Terjadi kesalahan! Silahkan refresh halaman.</font>');
			}
		});
	});
	$('#quantity').keyup(function() {
		var quantity = $('#quantity').val(), rate = $('#rate').val() / 1000;
		$('#total-price').val(quantity * rate);
	});
});
</script>
<?php
require '../lib/footer.php';
?>