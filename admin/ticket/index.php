<?php
require '../../mainconfig.php';
require '../../lib/check_session_admin.php';
require 'lib/ajax_table.php';
require '../../lib/header_admin.php';
?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                <h4 class="header-title m-t-0 m-b-30"><i class="fa fa-envelope"></i> Daftar Tiket</h4>
                                    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                            <div class="row">
                                                <div class="form-group col-lg-5">
                                                    <label>Filter Status</label>
                                                    <select class="form-control" name="status">
                                                        <option value="">Semua</option>
                                                        <option value="Waiting" >Waiting</option>
                                                        <option value="Closed" >Closed</option>
                                                        <option value="Responded" >Responded</option>
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
                                            <table class="table table-striped table-bordered nowrap m-0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>TANGGAL/WAKTU</th>
                                                    <th>UPDATE TERAKHIR</th>
                                                    <th>PENGGUNA</th>
                                                    <th>SUBJEK</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <?php	
											while ($data_query = mysqli_fetch_assoc($new_query)) {
											if($data_query['status'] == "Waiting") {
												$label = "warning";
											} else if($data_query['status'] == "Closed") {
												$label = "danger disabled";
											} else if($data_query['status'] == "Responded") {
												$label = "success disabled";
											}
                                            $user_data = $model->db_query($db, "*", "users",  "id = '".$data_query['user_id']."'");
											?>
											<tbody>
												<tr>
													<td><?php echo $data_query['id']; ?></td>
													<td><?php echo format_date(substr($data_query['created_at'], 0, -9)).", ".substr($data_query['created_at'], -8) ?></td>
													<td><?php echo format_date(substr($data_query['updated_at'], 0, -9)).", ".substr($data_query['updated_at'], -8) ?></td>
                                                    <td><?php echo $user_data['rows']['username'] ?></td>
                                                    <td><?php echo $data_query['subject'] ?></td>
                                                    <td><div class="btn-group mb-2">
														<button data-toggle="dropdown" class="btn btn-<?php echo $label ?> btn-sm dropdown-toggle" aria-haspopup="true" aria-expanded="false"><?php echo $data_query['status'] ?> </button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="javascript:;" onclick="update_data('<?php echo $config['web']['base_url'] ?>admin/ticket/lib/status.php?id=<?php echo $data_query['id'] ?>&status=Responded')">Responded</a>
															<a class="dropdown-item" href="javascript:;" onclick="update_data('<?php echo $config['web']['base_url'] ?>admin/ticket/lib/status.php?id=<?php echo $data_query['id'] ?>&status=Closed')">Closed</a>
															<a class="dropdown-item" href="javascript:;" onclick="update_data('<?php echo $config['web']['base_url'] ?>admin/ticket/lib/status.php?id=<?php echo $data_query['id'] ?>&status=Waiting')">Waiting</a>
														</div>
                                                    </div></td>
                                                    <td><a href="javascript:;" onclick="modal_open('edit', '<?php echo $config['web']['base_url'] ?>admin/ticket/lib/edit.php?id=<?php echo $data_query['id'] ?>')" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a> <a href="javascript:;" onclick="modal_open('delete', '<?php echo $config['web']['base_url'] ?>admin/ticket/lib/delete.php?id=<?php echo $data_query['id'] ?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
												</tr>
                                            </tbody>
											<?php
											}
											?>
                                        </table>
                                        <br />
										<?php
                                        require '../../lib/pagination.php';
                                        ?>
                            </div>
                        </div>
                      </div>
                   </div>
                </div>
<?php
require '../../lib/footer.php';
?>           