<?php
require '../../../mainconfig.php';
require '../../../lib/check_session_admin.php';
?>
<div id="modal-result" class="row"></div>
<form class="form-horizontal" method="POST" id="form-add">
	<div class="form-group">
		<label>Nama Provider</label>
		<input type="text" class="form-control" name="name">
	</div>
	<div class="form-group">
		<label>API Key</label>
		<input type="text" class="form-control" name="api_key">
	</div>
	<div class="form-group">
		<label>API Url (Order)</label>
		<input type="text" class="form-control" name="api_url_order">
	</div>
	<div class="form-group">
		<label>API Url (Status)</label>
		<input type="text" class="form-control" name="api_url_status">
	</div>
	<div class="form-group text-right">
		<button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i> Reset</button>
		<button class="btn btn-success" name="add" type="submit"><i class="fa fa-check"></i> Submit</button>
	</div>
</form>