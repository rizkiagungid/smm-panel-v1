<?php
require '../mainconfig.php';
require '../lib/check_session.php';
require '../lib/header.php';
?>
						<div class="row">
							<div class="col-lg-12">
								<div class="card-box">
									<h4 class="m-t-0 m-b-30 header-title"><i class="fa fa-newspaper-o fa-fw"></i> Berita & Informasi</h4>
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover" id="datatable">
											<thead>
												<tr>
													<th style="max-width: 150px;">TANGGAL/WAKTU</th>
													<th>KONTEN</th>
												</tr>
											</thead>
											<tbody>

<?php
$news = $model->db_query($db, "*", "news", null, "id DESC");
if ($news['count'] == 1) { ?>
	<tr>
		<td><?php echo format_date(substr($news['rows']['created_at'], 0, -9)).", ".substr($news['rows']['created_at'], -8) ?></td>
		<td><?php echo nl2br($news['rows']['content']) ?></td>
	</tr>
<?php
} else {
	foreach ($news['rows'] as $key => $value) {
?>
<tr>
	<td><?php echo format_date(substr($value['created_at'], 0, -9)).", ".substr($value['created_at'], -8) ?></td>
	<td><?php echo nl2br($value['content']) ?></td>
</tr>
<?php
	}
}
?>
										</tbody>
										</table>
										Total Data: <?php echo $news['count'] ?>
									</div>
								</div>
							</div>
						</div>
<?php
require '../lib/footer.php';
?>