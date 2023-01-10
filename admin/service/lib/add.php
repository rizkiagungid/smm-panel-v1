<?php
require '../../../mainconfig.php';
require '../../../lib/check_session_admin.php';
?>
<div id="modal-result" class="row"></div>
<form class="form-horizontal" method="POST" id="form-add">
	<div class="form-group">
		<label>Kategori</label>
		<select class="form-control" name="category_id">
			<option value="0">Pilih...</option>
<?php
$category = $model->db_query($db, "*", "categories");
if ($category['count'] == 1) {
	print('<option value="'.$category['rows']['id'].'">'.$category['rows']['name'].'</option>');
} else {
foreach ($category['rows'] as $key) {
	print('<option value="'.$key['id'].'">'.$key['name'].'</option>');
}
}
?>
		</select>
	</div>
	<div class="form-group">
		<label>Nama Layanan</label>
		<input type="text" class="form-control" name="service_name">
	</div>
	<div class="form-group">
		<label>Keterangan</label>
		<textarea class="form-control" name="note"></textarea>
	</div>
	<div class="form-group">
		<label>Harga/K</label>
		<input type="number" class="form-control" name="price">
	</div>
	<div class="form-group">
		<label>Keuntungan/K</label>
		<input type="number" class="form-control" name="profit">
	</div>
	<div class="form-group">
		<label>Min. Pesan</label>
		<input type="number" class="form-control" name="min">
	</div>
	<div class="form-group">
		<label>Max. Pesan</label>
		<input type="number" class="form-control" name="max">
	</div>
	<div class="form-group">
		<label>API</label>
		<select class="form-control" name="provider_id">
			<option value="0">Pilih...</option>
<?php
$provider = $model->db_query($db, "*", "provider");
if ($provider['count'] == 1) {
	print('<option value="'.$provider['rows']['id'].'">'.$provider['rows']['name'].'</option>');
} else {
foreach ($provider['rows'] as $key) {
	print('<option value="'.$key['id'].'">'.$key['name'].'</option>');
}
}
?>
		</select>
	</div>
	<div class="form-group">
		<label>ID Layanan API</label>
		<input type="number" class="form-control" name="provider_service_id">
	</div>
	<div class="form-group text-right">
		<button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i> Reset</button>
		<button class="btn btn-success" name="add" type="submit"><i class="fa fa-check"></i> Submit</button>
	</div>
</form>