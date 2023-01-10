<?php
require '../../../mainconfig.php';
require '../../../lib/check_session_admin.php';

if (!isset($_GET['id'])) {
	$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan tidak diterima.');
} else {
	$data_target = $model->db_query($db, "*", "users", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
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
		<label>Hak Akses</label>
		<select class="form-control" name="level">
			<option value="0">Pilih...</option>
			<option value="Member" <?php echo ($data_target['rows']['level'] == 'Member') ? 'selected' : '' ?>>Member</option>
			<option value="Reseller" <?php echo ($data_target['rows']['level'] == 'Reseller') ? 'selected' : '' ?>>Reseller</option>
			<option value="Admin" <?php echo ($data_target['rows']['level'] == 'Admin') ? 'selected' : '' ?>>Admin</option>
		</select>
	</div>
	<div class="form-group">
		<label>Username</label>
		<input type="text" class="form-control" value="<?php echo $data_target['rows']['username'] ?>" readonly>
	</div>
	<div class="form-group">
		<label>Password <small class="text-danger">*Kosongkan jika tidak diubah.</small></label>
		<input type="password" class="form-control" name="password">
	</div>
	<div class="form-group">
		<label>Nama Lengkap</label>
		<input type="text" class="form-control" name="full_name" value="<?php echo $data_target['rows']['full_name'] ?>">
	</div>
	<div class="form-group">
		<label>Saldo</label>
		<input type="number" class="form-control" name="balance" value="<?php echo $data_target['rows']['balance'] ?>">
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