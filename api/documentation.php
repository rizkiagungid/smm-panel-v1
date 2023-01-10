<?php
require '../mainconfig.php';
require '../lib/check_session.php';
require '../lib/header.php';
?>
						<div class="row">
							<div class="col-lg-12">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-book"></i> Dokumentasi API</h4>
<div class="table-responsive">
	<table class="table table-bordered">
		<tr>
			<td width="50%">HTTP METHOD</td>
			<td>POST</td>
		</tr>
		<tr>
			<td>FORMAT RESPON</td>
			<td>JSON</td>
		</tr>
		<tr>
			<td>API URL</td>
			<td><?php echo $config['web']['base_url'] ?>api/json.php</td>
		</tr>
		<tr>
			<td>API KEY</td>
			<td><?php echo $login['api_key'] ?> <a href="<?php echo $config['web']['base_url'] ?>user/post-settings.php?action=api_key" class="btn btn-xs btn-custom" title="Buat ulang"><i class="fa fa-random"></i></a></td>
		</tr>
		<tr>
			<td>CONTOH CLASS</td>
			<td><a href="<?php echo $config['web']['base_url'] ?>api/example.api.txt" target="_blank">CONTOH API</a></td>
		</tr>
	</table>
</div>
<h3>1. Menampilkan Daftar Layanan</h3>
<div class="table-responsive">
	<table class="table table-bordered">
		<tr>
			<th width="50%">Parameter</th>
			<th>Keterangan</th>
		</tr>
		<tr>
			<td>api_key</td>
			<td>API Key Anda.</td>
		</tr>
		<tr>
			<td>action</td>
			<td>services</td>
		</tr>
	</table>
</div>
<h4>Contoh Respon Yang Ditampilkan</h4>
<div class="table-responsive">
	<table class="table table-bordered">
		<tr>
			<th width="50%">Resppon Sukses</th>
			<th>Respon Gagal</th>
		</tr>
		<tr>
			<td>
<pre>{
	"status": true,
	"data": [
		{
			"id": 1,
			"name": "Instagram Followers S1",
			"price": 10000,
			"min": 100,
			"max": 10000,
			"note": "Super Fast, Input Username",
			"category": "Instagram Followers"
		},
		{
			"id": 2,
			"name": "Instagram Likes S1",
			"price": 5000,
			"min": 100,
			"max": 10000,
			"note": "Super Fast, Input Post Url",
			"category": "Instagram Likes"
		},
	]
}
</pre>
			</td>
			<td>
<pre>{
	"status": false,
	"data": {
		"msg": "API Key salah"
	}
}
</pre>
<b>Kemungkinan pesan yang ditampilkan:</b>
<ul>
	<li>Permintaan tidak sesuai</li>
	<li>API Key salah</li>
</ul>
			</td>
		</tr>
	</table>
</div>
<h3>2. Membuat Pesanan</h3>
<div class="table-responsive">
	<table class="table table-bordered">
		<tr>
			<th width="50%">Parameter</th>
			<th>Keterangan</th>
		</tr>
		<tr>
			<td>api_key</td>
			<td>API Key Anda.</td>
		</tr>
		<tr>
			<td>action</td>
			<td>order</td>
		</tr>
		<tr>
			<td>service</td>
			<td>ID Layanan, dapat dilihat di <a href="<?php echo $config['web']['base_url'] ?>services.php">Daftar Layanan</a>.</td>
		</tr>
		<tr>
			<td>data</td>
			<td>Data yang dibutuhkan sesuai layanan, seperti url/username target pesanan.</td>
		</tr>
		<tr>
			<td>quantity</td>
			<td>Jumlah pesan.</td>
		</tr>
	</table>
</div>
<h4>Contoh Respon Yang Ditampilkan</h4>
<div class="table-responsive">
	<table class="table table-bordered">
		<tr>
			<th width="50%">Resppon Sukses</th>
			<th>Respon Gagal</th>
		</tr>
		<tr>
			<td>
<pre>{
	"status": true,
	"data": {
		"id": "123"
	}
}
</pre>
			</td>
			<td>
<pre>{
	"status": false,
	"data": {
		"msg": "Saldo tidak cukup"
	}
}
</pre>
<b>Kemungkinan pesan yang ditampilkan:</b>
<ul>
	<li>Permintaan tidak sesuai</li>
	<li>API Key salah</li>
	<li>Layanan tidak ditemukan</li>
	<li>Jumlah pesan tidak sesuai</li>
	<li>Saldo tidak cukup</li>
	<li>Layanan tidak tersedia</li>
</ul>
			</td>
		</tr>
	</table>
</div>
<h3>3. Mengecek Status Pesanan</h3>
<div class="table-responsive">
	<table class="table table-bordered">
		<tr>
			<th width="50%">Parameter</th>
			<th>Keterangan</th>
		</tr>
		<tr>
			<td>api_key</td>
			<td>API Key Anda.</td>
		</tr>
		<tr>
			<td>action</td>
			<td>status</td>
		</tr>
		<tr>
			<td>id</td>
			<td>ID Pesanan.</td>
		</tr>
	</table>
</div>
<h4>Contoh Respon Yang Ditampilkan</h4>
<div class="table-responsive">
	<table class="table table-bordered">
		<tr>
			<th width="50%">Resppon Sukses</th>
			<th>Respon Gagal</th>
		</tr>
		<tr>
			<td>
<pre>{
	"status": true,
	"data": {
		"status": "Processing",
		"start_count": 199,
		"remains": 0
	}
}
</pre>
<b>Kemungkinan status yang ditampilkan:</b>
<ul>
	<li>Pending</li>
	<li>Processing</li>
	<li>Partial</li>
	<li>Error</li>
	<li>Success</li>
</ul>
			</td>
			<td>
<pre>{
	"status": false,
	"data": {
		"msg": "Pesanan tidak ditemukan"
	}
}
</pre>
<b>Kemungkinan pesan yang ditampilkan:</b>
<ul>
	<li>Permintaan tidak sesuai</li>
	<li>API Key salah</li>
	<li>Pesanan tidak ditemukan</li>
</ul>
			</td>
		</tr>
	</table>
</div>
							</div>
						</div>
					</div>
<?php
require '../lib/footer.php';
?>