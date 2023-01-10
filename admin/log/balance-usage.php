<?php
require '../../mainconfig.php';
require '../../lib/check_session_admin.php';
// query list for paging
if (isset($_GET['search'])) {
	if (!empty($_GET['search'])) {
		$query_list = "SELECT * FROM balance_logs WHERE note LIKE '%".protect_input($_GET['search'])."%' ORDER BY id DESC"; // edit
	} else {
		$query_list = "SELECT * FROM balance_logs ORDER BY id DESC"; // edit
	}
} else {
	$query_list = "SELECT * FROM balance_logs ORDER BY id DESC"; // edit
}
$records_per_page = 30; // edit

$starting_position = 0;
if(isset($_GET["page"])) {
	$starting_position = ($_GET["page"]-1) * $records_per_page;
}
$new_query = $query_list." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query); 
//
require '../../lib/header_admin.php';
?>
						<div class="row">
							<div class="col-lg-12">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-history"></i> Log Penggunaan Saldo</h4>
										<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
										    <div class="row">
                                        		<div class="form-group col-lg-5">
                                        		</div>
                                        		<div class="form-group col-lg-5">
                                        			<label>Kata Kunci Cari</label>
                                        			<input type="text" class="form-control" name="search" placeholder="Kata Kunci..." value="">
                                        		</div>
                                        		<div class="form-group col-lg-2">
                                        			<label>Submit</label>
                                        			<button type="submit" class="btn btn-block btn-dark">Filter</button>
                                        		</div>
                                        	</div>
								        </form>
										<div class="table-responsive">
										<table class="table table-bordered" id="datatable">
											<thead>
												<tr>
													<th>ID</th>
													<th style="max-width: 100px;">TANGGAL/WAKTU</th>
													<th>PENGGUNA</th>
													<th>JENIS</th>
													<th>JUMLAH SALDO</th>
													<th>KETERANGAN</th>
												</tr>
											</thead>
											<tbody>
											<?php
											while ($data_query = mysqli_fetch_assoc($new_query)) {
											if ($data_query['type'] == 'minus') {
												$label = "danger";
												$balance_type = "KURANG"; 
											} else {
												$label = "success";
												$balance_type = "TAMBAH";
											}
											$user_data = $model->db_query($db, "*", "users",  "id = '".$data_query['user_id']."'");
											?>
												<tr class="table-<?php echo $label ?>">
													<td><?php echo $data_query['id'] ?></td>
													<td><?php echo format_date(substr($data_query['created_at'], 0, -9)).", ".substr($data_query['created_at'], -8) ?></td>
													<td><?php echo $user_data['rows']['username'] ?></td>
													<td><?php echo $balance_type ?></td>
													<td>Rp <?php echo number_format($data_query['amount'],0,',','.') ?></td>
													<td><?php echo $data_query['note'] ?></td>
												</tr>
											<?php
											}
											?>
											</tbody>	
										</table>
										<?php include("../../lib/pagination.php"); ?>
										</table>
									</div>
								</div>
							</div>
						</div>
<script>
$(document).ready(function() {
	$('#datatable').DataTable( {
		"order": [[0, 'desc']],
		"processing": true,
		"serverSide": true,
		"columnDefs": [
			{ "targets": [0], "visible": false, "searchable": false },
		],
		"ajax": "<?php echo $config['web']['base_url'] ?>admin/log/ajax_balance_usage.php"
	});
});
</script>
<?php
require '../../lib/footer.php';
?>