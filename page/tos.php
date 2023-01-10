<?php
require '../mainconfig.php';
require '../lib/header.php';
?>
						<div class="row">
							<div class="col-lg-12">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-file"></i> Ketentuan Layanan</h4>
<?php
$page = $model->db_query($db, "*", "pages", "id = '2'");
echo ($page['count'] == 1) ? nl2br($page['rows']['content']) : '';
?>
								</div>
							</div>
						</div>
<?php
require '../lib/footer.php';
?>