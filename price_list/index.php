<?php
require("../mainconfig.php");
require("../lib/header.php");
// query list for paging
if (isset($_GET['search']) AND isset($_GET['category'])) {
	if (!empty($_GET['search']) AND !empty($_GET['category'])) {
		$query_list = "SELECT * FROM services WHERE service_name LIKE '%".protect_input($_GET['search'])."%' AND category_id LIKE '%".protect_input($_GET['category'])."%' AND status = '1' ORDER BY id DESC"; // edit
	} else if (!empty($_GET['search'])) {
		$query_list = "SELECT * FROM services WHERE service_name LIKE '%".protect_input($_GET['search'])."%' AND status = '1' ORDER BY id DESC"; // edit
	} else if (!empty($_GET['category'])) {
		$query_list = "SELECT * FROM services WHERE category_id LIKE '%".protect_input($_GET['category'])."%' AND status = '1' ORDER BY id DESC"; // edit		
	} else {
		$query_list = "SELECT * FROM services WHERE status = '1' ORDER BY id DESC"; // edit
	}
} else {
	$query_list = "SELECT * FROM services WHERE status = '1' ORDER BY id DESC"; // edit
}
$records_per_page = 30; // edit

$starting_position = 0;
if(isset($_GET["page"])) {
	$starting_position = ($_GET["page"]-1) * $records_per_page;
}
$new_query = $query_list." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query); 
//     
?>
			<div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-tags"></i> Daftar Harga Layanan Media Sosial</h4>
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
                                            <table class="table table-bordered dt-responsive nowrap">
                                                <thead>
													<tr>
														<th>ID</th>
														<th>KATEGORI</th>
														<th>LAYANAN</th>
														<th>HARGA/K</th>
														<th>MIN. PESAN</th>
														<th>MAKS. PESAN</th>
													</tr>
												</thead>
												<tbody>
												<?php
												while ($data_query = mysqli_fetch_assoc($new_query)) {
												$cat_id = $data_query['category_id'];
											    $check_cat = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM categories
                                                WHERE id = '".$data_query['category_id']."'"));
												?>
													<tr>
														<td><?php echo $data_query['id']; ?></td>
														<td><?php echo $check_cat['name']; ?></td>
														<td><?php echo $data_query['service_name']; ?></td>
														<td>Rp <?php echo number_format($data_query['price'],0,',','.'); ?></td>
														<td><?php echo number_format($data_query['min'],0,',','.'); ?></td>
														<td><?php echo number_format($data_query['max'],0,',','.'); ?></td>
														</tr>
												<?php
												}
												?>
										</tbody>	
										</table>
										<?php include("../lib/pagination.php"); ?>
										</div>
										</div>
									</div>
							</div>
						<!-- end row -->
<?php
require("../lib/footer.php");
?>