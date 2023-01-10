<?php
require '../mainconfig.php';
require '../lib/check_session.php';
require '../lib/header.php';
?>
						<div class="row">
							<div class="col-lg-12">
                    			<div class="card-box">
                        		<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-area-chart"></i> Grafik Pemesanan Bulan Ini</h4>
                                    <div id="order-chart" style="height: 300px;"></div>
                                </div>
							</div>
                        </div>
<script type="text/javascript">
Morris.Area({
	element: 'order-chart',
	data: [
<?php
$date_list = array();
$year = date('Y'); //Mengambil tahun saat ini
$month = date('m'); //Mengambil bulan saat ini
$date = cal_days_in_month(CAL_GREGORIAN, $month, $year);
for ($i=1; $i < $date+1; $i++) {
   $date_list[] = date('Y-m-'.$i.'');
}

for ($i = 0; $i < count($date_list); $i++) {
	$get_order = $model->db_query($db, "*", "orders", "user_id = '".$login['id']."' AND DATE(created_at) = '".$date_list[$i]."'");
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
require '../lib/footer.php';
?>