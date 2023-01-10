<?php
require '../../mainconfig.php';
require '../../lib/check_session_admin.php';
require 'lib/ajax_table.php';
require '../../lib/header_admin.php';
?>
						<div class="row">
							<div class="col-lg-12">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-list"></i> Data Permintaan Deposit</h4>
										<div class="alert alert-info">Deposit yang dapat dihapus hanya yang berstatus <i>Canceled</i>.</div>
										<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
										    <div class="row">
                                        		<div class="form-group col-lg-5">
                                        			<label>Filter Status</label>
                                        			<select class="form-control" name="status">
														<option value="">Semua</option>
														<option value="Pending">Pending</option>
														<option value="Canceled">Canceled</option>
														<option value="Success">Success</option>
													</select>
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
										<table class="table table-striped table-bordered table-hover" id="datatable">
											<thead>
												<tr>
													<th>ID</th>
													<th>TANGGAL/WAKTU</th>
													<th>PENGGUNA</th>
													<th>PEMBAYARAN</th>
													<th>TIPE</th>
													<th>NAMA METODE</th>
													<th>JUMLAH BAYAR</th>
													<th>SALDO DIDAPAT</th>
													<th>KETERANGAN</th>
													<th>NOMOR PENGIRIM</th>
													<th>STATUS</th>
													<th>AKSI</th>
												</tr>
											</thead>
											<tbody>
											<?php
											while ($data_query = mysqli_fetch_assoc($new_query)) {
											if($data_query['status'] == "Pending") {
												$label = "warning";
											} else if($data_query['status'] == "Canceled") {
												$label = "danger disabled";
											} else if($data_query['status'] == "Success") {
												$label = "success disabled";
											}
											$user_data = $model->db_query($db, "*", "users",  "id = '".$data_query['user_id']."'");
											?>
												<tr>
													<td><?php echo $data_query['id'] ?></td>
													<td><?php echo format_date(substr($data_query['created_at'], 0, -9)).", ".substr($data_query['created_at'], -8) ?></td>
													<td><?php echo $user_data['rows']['username'] ?></td>
													<td><?php echo $data_query['payment'] == "bank" ? 'Transfer Bank' : 'Transfer Pulsa' ?></td>
													<td><?php echo $data_query['type'] == "auto" ? 'Otomatis' : 'Manual' ?></td>
													<td><?php echo $data_query['method_name'] ?></td>
													<td>Rp <?php echo number_format($data_query['post_amount'],0,',','.') ?></td>
													<td>Rp <?php echo number_format($data_query['amount'],0,',','.') ?></td>
													<td><?php echo $data_query['note'] ?></td>
													<td><?php echo $data_query['payment'] == "pulsa" ? ''.$data_query['phone'].'' : '<i>NULL</i>' ?></td>
													<td><div class="btn-group mb-2">
														<button data-toggle="dropdown" class="btn btn-<?php echo $label ?> btn-sm dropdown-toggle" aria-haspopup="true" aria-expanded="false"><?php echo $data_query['status'] ?> </button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="javascript:;" onclick="update_data('<?php echo $config['web']['base_url'] ?>admin/deposit/lib/status.php?id=<?php echo $data_query['id'] ?>&status=Success')">Success</a>
															<a class="dropdown-item" href="javascript:;" onclick="update_data('<?php echo $config['web']['base_url'] ?>admin/deposit/lib/status.php?id=<?php echo $data_query['id'] ?>&status=Canceled')">Canceled</a>
															<a class="dropdown-item" href="javascript:;" onclick="update_data('<?php echo $config['web']['base_url'] ?>admin/deposit/lib/status.php?id=<?php echo $data_query['id'] ?>&status=Pending')">Pending</a>
														</div>
													</div></td>
													<td><a href="javascript:;" onclick="modal_open('delete', '<?php echo $config['web']['base_url'] ?>admin/deposit/lib/delete.php?id=<?php echo $data_query['id'] ?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
												</tr>
											<?php
											}
											?>
											</tbody>	
										</table>
										<?php include("../../lib/pagination.php"); ?>
									</div>
								</div>
							</div>
						</div>
<?php
require '../../lib/footer.php';
?>