<?php
require '../../mainconfig.php';
require '../../lib/check_session_admin.php';
$order = $model->db_query($db, "*", "orders");
$price = $model->db_query($db, "SUM(price) AS total", "orders");
$profit = $model->db_query($db, "SUM(profit) AS total", "orders");
if (isset($_GET['start_date']) AND isset($_GET['end_date'])) {
	if (validate_date($_GET['start_date']) == false OR validate_date($_GET['end_date']) == false) {
		exit('Input tidak sesuai.');
	}
	$order = $model->db_query($db, "*", "orders", "DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'");
	$price = $model->db_query($db, "SUM(price) AS total", "orders", "DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'");
	$profit = $model->db_query($db, "SUM(profit) AS total", "orders", "DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'");
}
require '../../lib/header_admin.php';
?>						
						<div class="row">
							<div class="col-lg-12">
								<div class="card-box">
									<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
										<div class="row">
											<div class="form-group col-md-5">
												<label for="inputCity" class="col-form-label">Mulai</label>
												<input type="date" class="form-control" name="start_date" value="<?php echo (isset($_GET['start_date'])) ? $_GET['start_date'] : date('Y-m-d') ?>">
											</div>
                                        	<div class="form-group col-md-5">
												<label for="inputState" class="col-form-label">Akhir</label>
												<input type="date" class="form-control" name="end_date" value="<?php echo (isset($_GET['end_date'])) ? $_GET['end_date'] : date('Y-m-d') ?>">
											</div>
                                        	<div class="form-group col-lg-2">
                                        		<label>Submit</label>
                                        		<button type="submit" class="btn btn-block btn-dark">Filter</button>
                                        	</div>
                                        </div>
								    </form>
								</div>
							</div>
						</div>	
						<div class="row">
							<div class="col-lg-12">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-info-circle"></i> Menampilkan Informasi: <?php echo (isset($_GET['start_date']) AND isset($_GET['end_date'])) ? 'Tanggal '.format_date($_GET['start_date']).' sampai '.format_date($_GET['end_date']) : 'Seluruh Pesanan' ?></h4>
										<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover">
											<tr>
												<th>TOTAL PESANAN</th>
												<th>TOTAL PENGHASILAN KOTOR</th>
												<th>TOTAL PENGHASILAN BERSIH</th>
											</tr>
											<tr>
												<td><?php echo number_format($order['count'],0,',','.') ?></td>
												<td>Rp <?php echo number_format($price['rows']['total'],0,',','.') ?></td>
												<td>Rp <?php echo number_format($profit['rows']['total'],0,',','.') ?></td>
											</tr>
										</table>
										</div>
										<div id="order-chart"></div>
									</div>
								</div>
							</div>
						</div>
<?php
if (isset($_GET['start_date']) AND isset($_GET['end_date'])) {
?>
<script type="text/javascript">
Morris.Area({
	element: 'order-chart',
	data: [
<?php
$end_date = new DateTime($_GET['end_date']);
$end_date->add(new DateInterval('P1D'));
$period = new DatePeriod(new DateTime($_GET['start_date']), new DateInterval('P1D'), new DateTime($end_date->format('Y-m-d')));
$date_list = array();
foreach ($period as $key => $value) {
	$date_list[] = $value->format('Y-m-d');
}
if (count($date_list) == 0) {
	$date_list[0] = $_GET['start_date'];
}
for ($i = 0; $i < count($date_list); $i++) {
	$get_order = $model->db_query($db, "*", "orders", "DATE(created_at) = '".$date_list[$i]."'");
	print("{ y: '".format_date($date_list[$i])."', a: ".$get_order['count']." }, ");
}
?>
	],
	xkey: 'y',
	ykeys: ['a'],
	labels: ['Pesanan'],
	lineColors: ['#02c0ce'],
	gridLineColor: '#eef0f2',
	pointSize: 0,
	lineWidth: 0,
	resize: true,
	parseTime: false
});
</script>
<?php
}
?>
<?php
require '../../lib/footer.php';
?>