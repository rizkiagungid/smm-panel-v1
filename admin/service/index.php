<?php
require '../../mainconfig.php';
require '../../lib/check_session_admin.php';
require 'lib/ajax_table.php';
require '../../lib/header_admin.php';
?>
						<div class="row">
							<div class="col-lg-12">
								<a href="javascript:;" onclick="modal_open('add', '<?php echo $config['web']['base_url'] ?>admin/service/lib/add.php');" class="btn btn-success" style="margin-bottom: 15px"><i class="fa fa-plus-square"></i> Tambah</a>
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-list"></i> Data Layanan</h4>
										<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
										    <div class="row">
                                        		<div class="form-group col-lg-5">
                                        			<label>Filter Kategori</label>
                                        			<select class="form-control" name="category">
														<option value="">Semua</option>
														<?php
														$check_cat = mysqli_query($db, "SELECT * FROM categories ORDER BY name ASC");
														while ($data_cat = mysqli_fetch_assoc($check_cat)) {
														?>
														<option value="<?php echo $data_cat['id']; ?>"><?php echo $data_cat['name']; ?></option>
														<?php
														}
														?>		
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
													<th>KATEGORI</th>
													<th>NAMA LAYANAN</th>
													<th>HARGA/K</th>
													<th>KEUNTUNGAN/K</th>
													<th>PROVIDER</th>
													<th>ID LAYANAN API</th>
													<th>STATUS</th>
													<th>AKSI</th>
												</tr>
											</thead>
											<tbody>
											<?php
											while ($data_query = mysqli_fetch_assoc($new_query)) {
											$label = ($data_query['status'] == 1) ? 'success' : 'danger';
											$category_data = $model->db_query($db, "*", "categories",  "id = '".$data_query['category_id']."'");
											$provider_data = $model->db_query($db, "*", "provider",  "id = '".$data_query['provider_id']."'");
											?>
												<tr>
													<td><?php echo $data_query['id'] ?></td>
													<td><?php echo $category_data['rows']['name'] ?></td>
													<td><?php echo $data_query['service_name'] ?></td>
													<td>Rp <?php echo number_format($data_query['price'],0,',','.') ?></td>
													<td>Rp <?php echo number_format($data_query['profit'],0,',','.') ?></td>
													<td><?php echo $provider_data['rows']['name'] ?></td>
													<td><?php echo $data_query['provider_service_id'] ?></td>
													<td><div class="btn-group mb-2">
														<button data-toggle="dropdown" class="btn btn-<?php echo $label ?> btn-sm dropdown-toggle" aria-haspopup="true" aria-expanded="false"><?php echo $data_query['status'] == "1" ? 'Active' : 'Nonactive' ?> </button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="javascript:;" onclick="update_data('<?php echo $config['web']['base_url'] ?>admin/service/lib/status.php?id=<?php echo $data_query['id'] ?>&status=1')">Active</a>
															<a class="dropdown-item" href="javascript:;" onclick="update_data('<?php echo $config['web']['base_url'] ?>admin/service/lib/status.php?id=<?php echo $data_query['id'] ?>&status=0')">Nonactive</a>
														</div>
													</div></td>
													<td><a href="javascript:;" onclick="modal_open('edit', '<?php echo $config['web']['base_url'] ?>admin/service/lib/edit.php?id=<?php echo $data_query['id'] ?>')" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a> <a href="javascript:;" onclick="modal_open('delete', '<?php echo $config['web']['base_url'] ?>admin/service/lib/delete.php?id=<?php echo $data_query['id'] ?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
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