<?php
require '../../../mainconfig.php';
require '../../../lib/check_session_admin.php';

if (!isset($_GET['id'])) {
	$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan tidak diterima.');
} else {
	$data_target = $model->db_query($db, "*", "tickets", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
	if ($data_target['count'] == 0) {
		$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
	} else {
    $user_data = $model->db_query($db, "*", "users",  "id = '".$data_target['rows']['user_id']."'");									
?>
<div class="card-box" style="max-height: 400px; overflow: auto;">
<div class="alert alert-primary" style="color: #000">
	<span class="pull-right"><?php echo format_date(substr($data_target['rows']['created_at'], 0, -9)).", ".substr($data_target['rows']['created_at'], -8) ?></span>
    <b><?php echo $user_data['rows']['username']; ?></b><p style="margin: 5px 0 0 0; max-height: 400px; overflow: auto;"><?php echo $data_target['rows']['msg'] ?></p>
</div>
<?php
$data_ticket = mysqli_query($db, "SELECT * FROM ticket_replies WHERE ticket_id = '".$_GET['id']."'");
while ($data_query = mysqli_fetch_assoc($data_ticket)) {				
	if ($data_query['is_admin'] == "1") {
		$label = "success";
		$text = "";
		$sender = "Admin";
	} else {
		$label = "info";
		$text = "text-right";
		$sender = $user_data['rows']['username'];
	}
?>
<div class="alert alert-<?php echo $label; ?>" style="color: #000">
	<span class="pull-right"><?php echo format_date(substr($data_query['created_at'], 0, -9)).", ".substr($data_query['created_at'], -8) ?></span>
	<b><?php echo $sender; ?></b><p style="margin: 5px 0 0 0; max-height: 400px; overflow: auto;"><?php echo $data_query['msg'] ?></p>
</div>									
<?php
}
?>
</div>
<div id="modal-result" class="row"></div>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>"/>
	<div class="form-group">
        <label>Pesan</label>
        <input type="hidden" class="form-control" name="id" value="<?php echo $data_target['rows']['id'] ?>" readonly>
	    <textarea class="form-control" name="msg"></textarea>
    </div>
    <div class="form-group text-right">
        <button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i> Reset</button>
        <button class="btn btn-success" name="edit" type="submit"><i class="fa fa-check"></i> Submit</button>
    <div class="form-group">
</form>
<?php
	}
}
require '../../../lib/result.php';