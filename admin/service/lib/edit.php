<?php
require '../../../mainconfig.php';
require '../../../lib/check_session_admin.php';

if (!isset($_GET['id'])) {
	$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan tidak diterima.');
} else {
	$data_target = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
	if ($data_target['count'] == 0) {
		$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
	} else {
?>
<div id="modal-result" class="row"></div>
<form class="form-horizontal" method="POST" id="form">
	<div class="form-group">
		<input type="hidden" class="form-control" name="id" value="<?php echo $data_target['rows']['id'] ?>" readonly>
	</div>
	<div class="form-group">
		<label>Kategori</label>
		<select class="form-control" name="category_id">
			<option value="0">Pilih...</option>
<?php
$category = $model->db_query($db, "*", "categories");
if ($category['count'] == 1) {
	$selected = ($data_target['rows']['category_id'] == $category['rows']['id']) ? 'selected' : '';
	print('<option value="'.$category['rows']['id'].'" '.$selected.'>'.$category['rows']['name'].'</option>');
} else {
foreach ($category['rows'] as $key) {
	$selected = ($data_target['rows']['category_id'] == $key['id']) ? 'selected' : '';
	print('<option value="'.$key['id'].'" '.$selected.'>'.$key['name'].'</option>');
}
}
?>
		</select>
	</div>
	<div class="form-group">
		<label>Nama Layanan</label>
		<input type="text" class="form-control" name="service_name" value="<?php echo $data_target['rows']['service_name'] ?>">
	</div>
	<div class="form-group">
		<label>Keterangan</label>
		<textarea class="form-control" name="note"><?php echo $data_target['rows']['note'] ?></textarea>
	</div>
	<div class="form-group">
		<label>Harga/K</label>
		<input type="number" class="form-control" name="price" value="<?php echo $data_target['rows']['price'] ?>">
	</div>
	<div class="form-group">
		<label>Keuntungan/K</label>
		<input type="number" class="form-control" name="profit" value="<?php echo $data_target['rows']['profit'] ?>">
	</div>
	<div class="form-group">
		<label>Min. Pesan</label>
		<input type="number" class="form-control" name="min" value="<?php echo $data_target['rows']['min'] ?>">
	</div>
	<div class="form-group">
		<label>Max. Pesan</label>
		<input type="number" class="form-control" name="max" value="<?php echo $data_target['rows']['max'] ?>">
	</div>
	<div class="form-group">
		<label>API</label>
		<select class="form-control" name="provider_id">
			<option value="0">Pilih...</option>
<?php
$provider = $model->db_query($db, "*", "provider");
if ($provider['count'] == 1) {
	$selected = ($data_target['rows']['provider_id'] == $provider['rows']['id']) ? 'selected' : '';
	print('<option value="'.$provider['rows']['id'].'" '.$selected.'>'.$provider['rows']['name'].'</option>');
} else {
foreach ($provider['rows'] as $key) {
	$selected = ($data_target['rows']['provider_id'] == $key['id']) ? 'selected' : '';
	print('<option value="'.$key['id'].'" '.$selected.'>'.$key['name'].'</option>');
}
}
?>
		</select>
	</div>
	<div class="form-group">
		<label>ID Layanan API</label>
		<input type="number" class="form-control" name="provider_service_id" value="<?php echo $data_target['rows']['provider_service_id'] ?>">
	</div>
	<div class="form-group text-right">
			<button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i> Reset</button>
			<button class="btn btn-success" name="edit" type="submit"><i class="fa fa-check"></i> Submit</button>
	</div>
</form>
<?php
	}
}
require '../../../lib/result.php';