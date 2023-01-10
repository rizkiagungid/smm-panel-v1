<?php
// query list for paging
if (isset($_GET['search']) AND isset($_GET['status'])) {
	if (!empty($_GET['search']) AND !empty($_GET['status'])) {
		$query_list = "SELECT * FROM vouchers WHERE voucher_code LIKE '%".protect_input($_GET['search'])."%' AND status LIKE '%".protect_input($_GET['status'])."%' ORDER BY id DESC"; // edit
	} else if (!empty($_GET['search'])) {
		$query_list = "SELECT * FROM vouchers WHERE voucher_code LIKE '%".protect_input($_GET['search'])."%' ORDER BY id DESC"; // edit
	} else if (!empty($_GET['status'])) {
		$query_list = "SELECT * FROM vouchers WHERE status LIKE '%".protect_input($_GET['status'])."%' ORDER BY id DESC"; // edit		
	} else {
		$query_list = "SELECT * FROM vouchers ORDER BY id DESC"; // edit
	}
} else {
	$query_list = "SELECT * FROM vouchers ORDER BY id DESC"; // edit
}
$records_per_page = 30; // edit

$starting_position = 0;
if(isset($_GET["page"])) {
	$starting_position = ($_GET["page"]-1) * $records_per_page;
}
$new_query = $query_list." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query); 
// 
if (isset($_POST['add'])) {
	$input_name = array('amount');
	if (check_input($_POST, $input_name) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	} else {
		$empty = array(
			'amount' => $_POST['amount'],
		);
		if (check_empty($empty) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
		} else {
			$voucher_code = str_rand();
			$input_post = array(
				'user_id' => 0,
				'amount' => $_POST['amount'],
				'voucher_code' => $voucher_code,
				'status' => 1,
				'created_at' => date('Y-m-d H:i:s')
			);
			if ($model->db_query($db, "voucher_code", "vouchers", "voucher_code = '".$input_post['voucher_code']."'")['count'] > 0) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Kode voucher sudah terdaftar.');
			} else {
				if ($model->db_insert($db, "vouchers", $input_post) == true) {
					$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Tambah data berhasil.');
				} else {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Tambah data gagal.');
				}
			}
		}
	}
} else if (isset($_GET['id']) AND isset($_GET['status'])) {
	$validation = array(
		'id' => $_GET['id'],
		'status' => $_GET['status'],
	);
	if (check_empty($validation) == true) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
	} else if (in_array($_GET['status'], array('Pending','Processing','Partial','Error','Success')) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai. '.mysqli_error($db).'');
	
	} else {
		$input_post = array(
			'status' => $_GET['status']
		);
		if ($model->db_update($db, "vouchers", $input_post, "id = '".$_GET['id']."'") == true) {
			$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Ubah data berhasil.');
		} else {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Ubah data gagal.');
		}
	}
} else if (isset($_POST['delete'])) {
	$check_data = $model->db_query($db, "*", "vouchers", "id = '".$_POST['id']."'");
	if ($check_data['count'] == 0) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
	} else {
		if ($model->db_delete($db, "vouchers", "id = '".$_POST['id']."'") == true) {
			$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Hapus data berhasil.');
		} else {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Hapus data gagal.');
		}
	}	
}