<?php
require '../../mainconfig.php';
require '../../lib/check_session_admin.php';
require 'lib/ajax_table.php';
require '../../lib/header_admin.php';
?>
						<div class="row">
							<div class="col-lg-12">
							<a href="javascript:;" onclick="modal_open('add', '<?php echo $config['web']['base_url'] ?>admin/provider/lib/add.php');" class="btn btn-success" style="margin-bottom: 15px"><i class="fa fa-plus-square"></i> Tambah</a>
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-list"></i> Data API</h4>
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
										<table class="table table-striped table-bordered table-hover" id="datatable">
											<thead>
												<tr>
													<th>ID</th>
													<th>NAMA PROVIDER</th>
													<th>API KEY</th>
													<th>API URL (ORDER)</th>
													<th>API URL (STATUS)</th>
													<th>AKSI</th>
												</tr>
											</thead>
											<tbody>
											<?php
											while ($data_query = mysqli_fetch_assoc($new_query)) {
											?>
												<tr>
													<td><?php echo $data_query['id'] ?></td>
													<td><?php echo $data_query['name'] ?></td>
													<td><?php echo $data_query['api_key'] ?></td>
													<td><?php echo $data_query['api_url_order'] ?></td>
													<td><?php echo $data_query['api_url_status'] ?></td>
													<td><a href="javascript:;" onclick="modal_open('edit', '<?php echo $config['web']['base_url'] ?>admin/provider/lib/edit.php?id=<?php echo $data_query['id'] ?>')" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a> <a href="javascript:;" onclick="modal_open('delete', '<?php echo $config['web']['base_url'] ?>admin/provider/lib/delete.php?id=<?php echo $data_query['id'] ?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
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
						</div>
<?php
require '../../lib/footer.php';
?>