<?php
// query list for paging
if (isset($_GET['search']) AND isset($_GET['category'])) {
	if (!empty($_GET['search']) AND !empty($_GET['category'])) {
		$query_list = "SELECT * FROM services WHERE service_name LIKE '%".protect_input($_GET['search'])."%' AND category_id LIKE '%".protect_input($_GET['category'])."%' ORDER BY id DESC"; // edit
	} else if (!empty($_GET['search'])) {
		$query_list = "SELECT * FROM services WHERE service_name LIKE '%".protect_input($_GET['search'])."%' ORDER BY id DESC"; // edit
	} else if (!empty($_GET['category'])) {
		$query_list = "SELECT * FROM services WHERE category_id LIKE '%".protect_input($_GET['category'])."%' ORDER BY id DESC"; // edit		
	} else {
		$query_list = "SELECT * FROM services ORDER BY id DESC"; // edit
	}
} else {
	$query_list = "SELECT * FROM services ORDER BY id DESC"; // edit
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
	$input_name = array('category_id', 'service_name', 'price', 'profit', 'min', 'max', 'provider_id', 'provider_service_id', 'note');
	if (check_input($_POST, $input_name) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	} else {
		$empty = array(
			'category_id' => $_POST['category_id'],
			'service_name' => $_POST['service_name'],
			'provider_id' => $_POST['provider_id'],
			'provider_service_id' => $_POST['provider_service_id'],
		);
		if (check_empty($empty) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
		} else {
			$input_post = array(
				'category_id' => $_POST['category_id'],
				'service_name' => $_POST['service_name'],
				'price' => $_POST['price'],
				'profit' => $_POST['profit'],
				'min' => $_POST['min'],
				'max' => $_POST['max'],
				'provider_id' => $_POST['provider_id'],
				'provider_service_id' => $_POST['provider_service_id'],
				'note' => $_POST['note'],
			);
			if ($model->db_query($db, "service_name", "services", "BINARY service_name = '".$input_post['service_name']."'")['count'] > 0) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Nama Layanan sudah terdaftar.');
			} else {
				if ($model->db_insert($db, "services", $input_post) == true) {
					$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Tambah data berhasil.');
				} else {
					die(mysqli_error($db));
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Tambah data gagal.');
				}
			}
		}
	}
} else if (isset($_POST['edit'])) {
	$input_name = array('category_id', 'service_name', 'price', 'profit', 'min', 'max', 'provider_id', 'provider_service_id', 'note');
	$check_data = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_POST['id'])."'");
	if (check_input($_POST, $input_name) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
	} else if ($check_data['count'] == 0) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
	} else {
		$empty = array(
			'category_id' => $_POST['category_id'],
			'service_name' => $_POST['service_name'],
			'provider_id' => $_POST['provider_id'],
			'provider_service_id' => $_POST['provider_service_id'],
		);
		if (check_empty($empty) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
		} else {
			$input_post = array(
				'category_id' => $_POST['category_id'],
				'service_name' => $_POST['service_name'],
				'price' => $_POST['price'],
				'profit' => $_POST['profit'],
				'min' => $_POST['min'],
				'max' => $_POST['max'],
				'provider_id' => $_POST['provider_id'],
				'provider_service_id' => $_POST['provider_service_id'],
				'note' => $_POST['note'],
			);
			if ($input_post['service_name'] <> $check_data['rows']['service_name'] AND $model->db_query($db, "service_name", "services", "BINARY service_name = '".$input_post['service_name']."'")['count'] > 0) {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Nama Layanan sudah terdaftar.');
			} else {
				if ($model->db_update($db, "services", $input_post, "id = '".$_POST['id']."'") == true) {
					$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Ubah data berhasil.');
				} else {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Ubah data gagal.');
				}
			}
		}
	}
} else if (isset($_POST['delete'])) {
	$check_data = $model->db_query($db, "*", "services", "id = '".$_POST['id']."'");
	if ($check_data['count'] == 0) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
	} else {
		if ($model->db_delete($db, "services", "id = '".$_POST['id']."'") == true) {
			$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pengguna berhasil dihapus.');
		} else {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Hapus data gagal.');
		}
	}
}