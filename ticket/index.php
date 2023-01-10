<?php
require '../mainconfig.php';
require '../lib/check_session.php';
// query list for paging
if (isset($_GET['search']) AND isset($_GET['status'])) {
	if (!empty($_GET['search']) AND !empty($_GET['status'])) {
		$query_list = "SELECT * FROM tickets WHERE user_id = '".$_SESSION['login']."' AND msg LIKE '%".protect_input($_GET['search'])."%' AND status LIKE '%".protect_input($_GET['status'])."%' ORDER BY id DESC"; // edit
	} else if (!empty($_GET['search'])) {
		$query_list = "SELECT * FROM tickets WHERE user_id = '".$_SESSION['login']."' AND msg LIKE '%".protect_input($_GET['search'])."%' ORDER BY id DESC"; // edit
	} else if (!empty($_GET['status'])) {
		$query_list = "SELECT * FROM tickets WHERE user_id = '".$_SESSION['login']."' AND status LIKE '%".protect_input($_GET['status'])."%' ORDER BY id DESC"; // edit		
	} else {
		$query_list = "SELECT * FROM tickets WHERE user_id = '".$_SESSION['login']."' ORDER BY id DESC"; // edit
	}
} else {
	$query_list = "SELECT * FROM tickets WHERE user_id = '".$_SESSION['login']."' ORDER BY id DESC"; // edit
}
$records_per_page = 30; // edit

$starting_position = 0;
if(isset($_GET["page"])) {
	$starting_position = ($_GET["page"]-1) * $records_per_page;
}
$new_query = $query_list." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query); 
// 
require '../lib/header.php';
?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                <h4 class="header-title m-t-0 m-b-30"><i class="fa fa-envelope"></i> Daftar Tiket</h4>
                            	<td align="center"><a href="<?php echo $config['web']['base_url']; ?>ticket/submit.php" class="btn btn-sm btn-info" ><i class="fa fa-submit"></i>Buka Tiket</a></td>
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
                                                    <th>SUBJEK</th>
                                                    <th>STATUS</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <?php	
											while ($data_query = mysqli_fetch_assoc($new_query)) {
											if($data_query['status'] == "Waiting") {
												$label = "warning";
											} else if($data_query['status'] == "Closed") {
												$label = "danger";
											} else if($data_query['status'] == "Responded") {
												$label = "success";
											}
											?>
											<tbody>
												<tr>
													<td><?php echo $data_query['id']; ?></td>
													<td><?php echo format_date(substr($data_query['created_at'], 0, -9)).", ".substr($data_query['created_at'], -8) ?></td>
													<td><?php echo format_date(substr($data_query['updated_at'], 0, -9)).", ".substr($data_query['updated_at'], -8) ?></td>
                                                    <td><?php echo $data_query['subject'] ?></td>
                                                    <td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_query['status'] ?></span></td>
													<td align="center"><a href="<?php echo $config['web']['base_url']; ?>ticket/reply.php?id=<?php echo $data_query['id']; ?>" class="btn btn-sm btn-info" ><i class="fa fa-reply"></i> Balas</a></td>
												</tr>
                                            </tbody>
											<?php
											}
											?>
                                        </table>
                                        <br />
										<?php
                                        require '../lib/pagination.php';
                                        ?>
                            </div>
                        </div>
                      </div>
                   </div>
                </div>
<?php 
include '../lib/footer.php';
?>             