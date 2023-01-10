<?php
require '../../mainconfig.php';
require '../../lib/check_session_admin.php';
$deposit = $model->db_query($db, "*", "deposits");
$pending = $model->db_query($db, "SUM(amount) AS total", "deposits", "status = 'Pending'");
$pending['total'] = number_format($model->db_query($db, "*", "deposits WHERE status = 'Pending'")['count'],0,',','.');
$success = $model->db_query($db, "SUM(amount) AS total", "deposits", "status = 'Success'");
$success['total'] = number_format($model->db_query($db, "*", "deposits WHERE status = 'Success'")['count'],0,',','.');
$canceled = $model->db_query($db, "SUM(amount) AS total", "deposits", "status = 'Canceled'");
$canceled['total'] = number_format($model->db_query($db, "*", "deposits WHERE status = 'Canceled'")['count'],0,',','.');
if (isset($_GET['start_date']) AND isset($_GET['end_date'])) {
	if (validate_date($_GET['start_date']) == false OR validate_date($_GET['end_date']) == false) {
		exit('Input tidak sesuai.');
	}
	$deposit = $model->db_query($db, "*", "deposits", "DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'");
	$pending = $model->db_query($db, "SUM(amount) AS total", "deposits", "status = 'Pending' AND DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'");
	$pending['total'] = number_format($model->db_query($db, "*", "deposits WHERE status = 'Pending'  AND DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'")['count'],0,',','.');
	$success = $model->db_query($db, "SUM(amount) AS total", "deposits", "status = 'Success' AND DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'");
	$success['total'] = number_format($model->db_query($db, "*", "deposits WHERE status = 'Success'  AND DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'")['count'],0,',','.');
	$canceled = $model->db_query($db, "SUM(amount) AS total", "deposits", "status = 'Canceled' AND DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'");
	$canceled['total'] = number_format($model->db_query($db, "*", "deposits WHERE status = 'Canceled'  AND DATE(created_at) BETWEEN '".mysqli_real_escape_string($db, $_GET['start_date'])."' AND '".mysqli_real_escape_string($db, $_GET['end_date'])."'")['count'],0,',','.');
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
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-info-circle"></i> Menampilkan Informasi: <?php echo (isset($_GET['start_date']) AND isset($_GET['end_date'])) ? 'Tanggal '.format_date($_GET['start_date']).' sampai '.format_date($_GET['end_date']) : 'Seluruh Deposit' ?></h4>
										<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover">
											<tr>
												<th>TOTAL DEPOSIT</th>
												<th>TOTAL DEPOSIT DITAHAN</th>
												<th>TOTAL DEPOSIT BERHASIL</th>
												<th>TOTAL DEPOSIT GAGAL</th>
											</tr>
											<tr>
												<td><?php echo number_format($deposit['count'],0,',','.') ?></td>
												<td>Rp <?php echo number_format($pending['rows']['total'],0,',','.') ?> (<?php echo $pending['total'] ?>)</td>
												<td>Rp <?php echo number_format($success['rows']['total'],0,',','.') ?> (<?php echo $success['total'] ?>)</td>
												<td>Rp <?php echo number_format($canceled['rows']['total'],0,',','.') ?> (<?php echo $canceled['total'] ?>)</td>
											</tr>
										</table>
										</div>
										<div id="deposit-chart"></div>
									</div>
								</div>
							</div>
						</div>
						<?php
if (isset($_GET['start_date']) AND isset($_GET['end_date'])) {
?>
<script type="text/javascript">
Morris.Area({
	element: 'deposit-chart',
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
	$get_deposit = $model->db_query($db, "*", "deposits", "DATE(created_at) = '".$date_list[$i]."'");
	print("{ y: '".format_date($date_list[$i])."', a: ".$get_deposit['count']." }, ");
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