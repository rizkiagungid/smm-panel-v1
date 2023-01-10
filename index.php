<?php
require 'mainconfig.php';
require 'lib/header.php';
if (!isset($_SESSION['login'])) {
?>
				<div class="row">
					<div class="col-lg-12">
		                <div class="card-box">
                                <center>
                                    <i class="fa fa-instagram text-green" style="font-size: 60px;"></i>
            	                    <h1 class="text-uppercase">WEB KAMU</h1>
            					    <p><?php echo $config['web']['meta']['description'] ?> </p>
            						<a href="<?php echo $config['web']['base_url'] ?>auth/login.php" class="btn btn-success"><i class="fa fa-sign-in fa-fw"></i> Masuk</a> 
            						<a href="<?php echo $config['web']['register_url'] ?>" class="btn btn-warning"><i class="fa fa-user-plus fa-fw"></i> Daftar</a>
            				    </center>	
            			</div>
            		</div>
            		<div class="col-lg-12">
						<div class="card-box">
							<div class="facts-box text-center">
								<div class="row">
									<div class="col-lg-4">
										<h2><?php echo number_format($model->db_query($db, "*", "services")['count'],0,',','.') ?>+</h2>
										<p class="text-muted">Total Layanan</p>
									</div>
									<div class="col-lg-4">
										<h2><?php echo number_format($model->db_query($db, "*", "users")['count'],0,',','.') ?>+</h2>
										<p class="text-muted">Total Pengguna</p>
									</div>
									<div class="col-lg-4">
										<h2><?php echo number_format($model->db_query($db, "SUM(price) as total", "orders")['rows']['total'],0,',','.') ?>+</h2>
										<p class="text-muted">Total Pesanan</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
                        <div class="card-box">
                            <center>
                        	<i class="fa fa-thumbs-up text-green" style="font-size: 40px;"></i>
                        	<h4 class="text-uppercase">Layanan Terbaik</h4>
                        	<small>Kami menyediakan berbagai layanan terbaik untuk kebutuhan sosial media Anda.</small>
                        	</center>
                        </div>
                    </div>
					
					<div class="col-lg-4">
                        <div class="card-box">
                            <center>
                            	<i class="fa fa-support text-green" style="font-size: 40px;"></i>
                            	<h4 class="text-uppercase">Pelayanan Bantuan</h4>
                            	<small>Kami selalu siap membantu jika Anda membutuhkan kami dalam penggunaan layanan kami.</small>
                            </center>
                        </div>
                    </div>
					
					<div class="col-lg-4">
                        <div class="card-box">
                            <center>
                            	<i class="fa fa-desktop" style="font-size: 40px;"></i>
                            	<h4 class="text-uppercase">Desain Responsif</h4>
                            	<small>Kami menggunakan desain website yang dapat diakses dari berbagai <i>device</i>, baik smartphone maupun PC.</small>
                            </center>
                        </div>
                    </div>
				</div>
				<div class="card-box">
				<div class="row">
                    <div class="col-lg-5 offset-lg-1">
                        <div>
                            <div class="question-q-box">Q.</div>
                            <h4 class="question" data-wow-delay=".1s">Apa itu <?php echo $config['web']['title'] ?>?</h4>
                            <p class="answer"><?php echo $config['web']['title'] ?> adalah sebuah platform bisnis yang menyediakan berbagai layanan social media marketing yang bergerak terutama di Indonesia. Dengan bergabung bersama kami, Anda dapat menjadi penyedia jasa social media atau reseller social media seperti jasa penambah Followers, Likes, dll.</p>
                        </div>
                        <div>
                            <div class="question-q-box">Q.</div>
                            <h4 class="question">Bagaimana cara mendaftar di <?php echo $config['web']['title'] ?></h4>
                            <p class="answer">Anda dapat langsung mendaftar di website <?php echo $config['web']['title'] ?> pada halaman Daftar (<a href="<?php echo $config['web']['base_url'] ?>auth/register.php">Klik di sini</a>).</p>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div>
                            <div class="question-q-box">Q.</div>
                            <h4 class="question">Bagaimana cara membuat pesanan?</h4>
                            <p class="answer">Untuk membuat pesanan sangatlah mudah, Anda hanya perlu masuk terlebih dahulu ke akun Anda dan menuju halaman pemesanan dengan mengklik menu yang sudah tersedia. Selain itu Anda juga dapat melakukan pemesanan melalui request API.</p>
                        </div>
                        <div>
                            <div class="question-q-box">Q.</div>
                            <h4 class="question">Bagaimana cara melakukan deposit/isi saldo?</h4>
                            <p class="answer">Untuk melakukan deposit/isi saldo, Anda hanya perlu masuk terlebih dahulu ke akun Anda dan menuju halaman deposit dengan mengklik menu yang sudah tersedia. Kami menyediakan deposit melalui bank dan pulsa.</p>
                        </div>
                    </div>
                </div>
				</div>

<?php
} else {
?>
						<div class="row">
							<div class="col-lg-12 text-center" style="margin: 15px 0;">
								<h3 class="text-uppercase"><i class="fa fa-user-circle-o fa-fw"></i> Informasi Akun</h3>
							</div>
							<div class="col-lg-8">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-area-chart"></i> Grafik Pesanan & Deposit 7 Hari Terakhir</h4>
										<div id="last-order-chart" style="height: 200px;"></div>
								</div>
							</div>
							<div class="col-lg-4 text-center">
								<div class="card-box widget-flat border-custom bg-custom text-white">
									<h3 class="m-b-10">Rp <?php echo number_format($model->db_query($db, "SUM(price) as total", "orders WHERE user_id = '".$_SESSION['login']."'")['rows']['total'],0,',','.') ?> (<?php echo number_format($model->db_query($db, "*", "orders WHERE user_id = '".$_SESSION['login']."'")['count'],0,',','.') ?>)</h3>
									<p class="text-uppercase m-b-5 font-13 font-600"><i class="mdi mdi-cart-outline"></i> Pesanan Saya</p>
								</div>
								<div class="card-box widget-flat border-custom bg-custom text-white">
									<i class="mdi mdi-cash-multiple"></i>
									<h3 class="m-b-10">Rp <?php echo number_format($model->db_query($db, "SUM(amount) as total", "deposits WHERE user_id = '".$_SESSION['login']."'")['rows']['total'],0,',','.') ?> (<?php echo number_format($model->db_query($db, "*", "deposits WHERE user_id = '".$_SESSION['login']."'")['count'],0,',','.') ?>)</h3>
									<p class="text-uppercase m-b-5 font-13 font-600">Deposit Saya</p>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="profile-user-box card-box bg-custom">
									<div class="row">
										<div class="col-lg-6">
											<span class="pull-left mr-3"><img src="<?php echo $config['web']['base_url'] ?>assets/images/profile.png" alt="Profile" class="thumb-lg rounded-circle"></span>
											<div class="media-body text-white">
												<h4 class="mt-1 mb-1 font-18"><?php echo $login['full_name'] ?></h4>
												<p class="font-13 text-light"><?php echo $login['full_name'] ?> terdaftar sejak <?php echo format_date(substr($login['created_at'], 0, -9)).", ".substr($login['created_at'], -8) ?>.</p>
												<p class="text-light mb-0">Sisa Saldo: Rp <?php echo number_format($login['balance'],0,',','.'); ?></p>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="text-right">
												<a class="btn btn-light" href="<?php echo $config['web']['base_url'] ?>user/settings.php"><i class="fa fa-gear fa-spin fa-fw"></i> Pengaturan Akun</a>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-12 text-center" style="margin: 15px 0;">
								<h3 class="text-uppercase"><i class="fa fa-bullhorn fa-fw"></i> Informasi Webiste</h3>
							</div>
							<div class="col-lg-8">
								<div class="card-box">
								<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-info-circle"></i> 5 Informasi Terbaru</h4>
									<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th style="width: 250px;">TANGGAL/WAKTU</th>
												<th>KONTEN</th>
											</tr>
										</thead>
										<tbody>

<?php
$news = $model->db_query($db, "*", "news", null, "id DESC", "LIMIT 5");
if ($news['count'] == 1) { ?>
	<tr>
		<td><?php echo format_date(substr($news['rows']['created_at'], 0, -9)).", ".substr($news['rows']['created_at'], -8) ?></td>
		<td><?php echo nl2br($news['rows']['content']) ?></td>
	</tr>
<?php
} else {
	foreach ($news['rows'] as $key => $value) {
?>
<tr>
	<td><?php echo format_date(substr($value['created_at'], 0, -9)).", ".substr($value['created_at'], -8) ?></td>
	<td><?php echo nl2br($value['content']) ?></td>
</tr>
<?php
	}
}
if ($news['count'] >= 5) { ?>
											<tr>
												<td colspan="3" align="center">
													<a href="<?php echo $config['web']['base_url'] ?>news.php">Lihat semua...</a>
												</td>
											</tr>

<?php
}
?>
										</tbody>
									</table>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-link fa-fw"></i> Sitemap</h4>
									<ul>
										<li><a href="<?php echo $config['web']['base_url'] ?>page/contact">Kontak</a></li>
										<li><a href="<?php echo $config['web']['base_url'] ?>page/tos">Ketentuan Layanan</a></li>
										<li><a href="<?php echo $config['web']['base_url'] ?>page/faq">Pertanyaan Umum</a></li>
									</ul>
								</div>
							</div>
						</div>
<script type="text/javascript">
Morris.Area({
	element: 'last-order-chart',
	data: [
<?php
$date_list = array();
for ($i = 6; $i > -1; $i--) {
	$date_list[] = date('Y-m-d', strtotime('-'.$i.' days'));
}

for ($i = 0; $i < count($date_list); $i++) {
	$get_order = $model->db_query($db, "*", "orders", "user_id = '".$login['id']."' AND DATE(created_at) = '".$date_list[$i]."'");
	$get_deposit = $model->db_query($db, "*", "deposits", "user_id = '".$login['id']."' AND DATE(created_at) = '".$date_list[$i]."' AND status = 'Success'");
	print("{ y: '".format_date($date_list[$i])."', a: ".$get_order['count'].", b: ".$get_deposit['count']." }, ");
}
?>
	],
	xkey: 'y',
	ykeys: ['a','b'],
	labels: ['Pesanan','Deposit'],
	lineColors: ['#02c0ce','#53c68c'],
	gridLineColor: '#eef0f2',
	pointSize: 0,
	lineWidth: 0,
	resize: true,
	parseTime: false
});
</script>
<?php
}
require 'lib/footer.php';
?>