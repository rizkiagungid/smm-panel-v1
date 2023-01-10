<?php
require '../../../mainconfig.php';
require '../../../lib/check_session_admin.php';
?>
<div id="modal-result" class="row"></div>
<form class="form-horizontal" method="POST" id="form">
	<div class="form-group">
		<label>Hak Akses</label>
		<select class="form-control" name="level">
			<option value="0">Pilih...</option>
			<option value="Member" selected>Member</option>
			<option value="Reseller">Reseller</option>
			<option value="Admin">Admin</option>
		</select>
	</div>
	<div class="form-group">
		<label>Username</label>
		<input type="text" class="form-control" name="username">
	</div>
	<div class="form-group">
		<label>Password</label>
		<input type="password" class="form-control" name="password">
	</div>
	<div class="form-group">
		<label>Nama Lengkap</label>
		<input type="text" class="form-control" name="full_name">
	</div>
	<div class="form-group">
		<label>Saldo</label>
		<input type="number" class="form-control" name="balance" value="0">
	</div>
	<div class="form-group text-right">
			<button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i> Reset</button>
			<button class="btn btn-success" name="add" type="submit"><i class="fa fa-check"></i> Submit</button>
	</div>
</form>