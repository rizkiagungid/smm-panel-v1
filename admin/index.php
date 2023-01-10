<?php
/**
 * Penulis Kode - SMM Panel script
 * Domain: http://penuliskode.com/
 * Documentation: http://penuliskode.com/smm/script/version-n1/documentation.html
 *
 */

require '../mainconfig.php';
require '../lib/check_session_admin.php';
require '../lib/header_admin.php';
?>
						<div class="row">
<div class="col-lg-4 text-center">
	<div class="card-box widget-flat border-custom bg-custom text-white">
		<i class="fa fa-users"></i>
		<h3 class="m-b-10">Rp <?php echo number_format($model->db_query($db, "SUM(balance) as total", "users")['rows']['total'],0,',','.') ?> (<?php echo number_format($model->db_query($db, "*", "users")['count'],0,',','.') ?>)</h3>
		<p class="text-uppercase m-b-5 font-13 font-600">Total Saldo Pengguna</p>
	</div>
</div>
<div class="col-lg-4 text-center">
	<div class="card-box widget-flat border-custom bg-custom text-white">
		<i class="mdi mdi-cash-multiple"></i>
		<h3 class="m-b-10">Rp <?php echo number_format($model->db_query($db, "SUM(amount) as total", "deposits")['rows']['total'],0,',','.') ?> (<?php echo number_format($model->db_query($db, "*", "deposits")['count'],0,',','.') ?>)</h3>
		<p class="text-uppercase m-b-5 font-13 font-600">Total Deposit</p>
	</div>
</div>
<div class="col-lg-4 text-center">
	<div class="card-box widget-flat border-custom bg-custom text-white">
		<i class="fa fa-shopping-cart"></i>
		<h3 class="m-b-10">Rp <?php echo number_format($model->db_query($db, "SUM(price) as total", "orders")['rows']['total'],0,',','.') ?> (<?php echo number_format($model->db_query($db, "*", "orders")['count'],0,',','.') ?>)</h3>
		<p class="text-uppercase m-b-5 font-13 font-600">Total Pesanan</p>
	</div>
</div>
						</div>
<?php
require '../lib/footer.php';
?>