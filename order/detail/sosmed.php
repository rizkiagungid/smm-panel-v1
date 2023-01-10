<?php
require '../../mainconfig.php';
require '../../lib/check_session.php';
	if (isset($_GET['id'])) {
		$data_query = $model->db_query($db, "*", "orders",  "id = '".$_GET['id']."'");
	    if ($data_query['rows']['is_refund'] == 1) {
		    $count_refund = $data_query['rows']['price'] / $data_query['rows']['quantity'];
	        $total_refund = $count_refund * $data_query['rows']['remains'];
		} 
		if($data_query['rows']['status'] == "Pending") {
			$label = "warning";
		} else if($data_query['rows']['status'] == "Processing") {
			$label = "info";
		} else if($data_query['rows']['status'] == "Error") {
			$label = "danger";
		} else if($data_query['rows']['status'] == "Partial") {
			$label = "danger";
		} else if($data_query['rows']['status'] == "Success") {
			$label = "success";
        }
?>
										
		    <div class="row">
		    	<div class="col-md-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content">
                	                    <div class="table-responsive">
                                            <table class="table table-stripped" data-page-size="8" data-filter=#filter>
                                                <tr>
													<td><b>ID</b></td>
													<td><?php echo $data_query['rows']['id'] ?></td>
												<tr>
												<tr>
													<td><b>TANGGAL/WAKTU</b></td>
													<td><?php echo format_date(substr($data_query['rows']['created_at'], 0, -9)).", ".substr($data_query['rows']['created_at'], -8) ?></td>
												</tr>
												<tr>
													<td><b>LAYANAN</b></td>
													<td><?php echo $data_query['rows']['service_name'] ?></td>
												</tr>
												<tr>
													<td><b>DATA</b></td>
													<td><?php echo $data_query['rows']['data'] ?></td>
												</tr>
												<tr>
													<td><b>JUMLAH</b></td>
													<td><?php echo number_format($data_query['rows']['quantity'],0,',','.') ?></td>
												</tr>
												<tr>
													<td><b>HARGA</b></td>
													<td>Rp <?php echo number_format($data_query['rows']['price'],0,',','.') ?></td>
												</tr>
												<tr>
													<td><b>STATUS</b></td>
													<td><span class="badge badge-<?php echo $label ?>"><?php echo $data_query['rows']['status'] ?></span></td>
												</tr>
												<tr>
													<td><b>JUMLAH AWAL</b></td>
													<td><?php echo  number_format($data_query['rows']['start_count']) ?></td>
												</tr>
												<tr>
													<td><b>JUMLAH KURANG</b></td>
													<td><?php echo  number_format($data_query['rows']['remains']) ?></td>
												</tr>
												<tr>
													<td><b>SUMBER</b></td>
													<td><?php echo ($data_query['rows']['is_api'] == 1) ? 'API' : 'WEB' ?></td>
												</tr>
												<tr>
													<td><b>PENGEMBALIAN DANA</b></td>
													<td><?php echo ($data_query['rows']['is_refund'] == 1) ? 'YA ('.$total_refund.')' : 'TIDAK' ?></td>
												</tr>
						                    </table>
					                    </div>
                                    </div>
                    </div>
                </div>
            </div>
<?php
	}
?>