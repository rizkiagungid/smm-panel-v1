<?php
/**
 * Penulis Kode - SMM Panel script
 * Domain: http://penuliskode.com/
 * Documentation: http://penuliskode.com/smm/script/version-n1/documentation.html
 *
 */

require '../mainconfig.php';
header('Content-Type: application/json');
if ($config['web']['maintenance'] == 1) {
	$result = array('status' => false, 'data' => array('msg' => 'Maintenance'));
	exit(json_encode($result, JSON_PRETTY_PRINT));
}
if ($_POST) {
	if (check_input($_POST, array('api_key', 'action')) == false) {
		$result = array('status' => false, 'data' => array('msg' => 'Permintaan tidak sesuai'));
	} else {
		$user = $model->db_query($db, "*", "users", "BINARY api_key = '".mysqli_real_escape_string($db, $_POST['api_key'])."'");
		if ($user['count'] <> 1) {
			$result = array('status' => false, 'data' => array('msg' => 'API Key salah'));
		} else {
			if ($_POST['action'] == 'order') {
				if (check_input($_POST, array('service', 'data', 'quantity')) == false) {
					$result = array('status' => false, 'data' => array('msg' => 'Permintaan tidak sesuai'));
				} else {
					$service = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_POST['service'])."' AND status = '1'");
					if ($service['count'] == 0) {
						$result = array('status' => false, 'data' => array('msg' => 'Layanan tidak ditemukan'));
					} else {
						$total_price = ($service['rows']['price'] / 1000) * $_POST['quantity'];
						$total_profit = ($service['rows']['profit'] / 1000) * $_POST['quantity'];
						$provider = $model->db_query($db, "*", "provider", "id = '".$service['rows']['provider_id']."'");
						if ($provider['count'] == 0) {
							$result = array('status' => false, 'data' => array('msg' => 'Layanan tidak tersedia'));
						} elseif ($_POST['quantity'] < $service['rows']['min']) {
							$result = array('status' => false, 'data' => array('msg' => 'Jumlah pesan tidak sesuai'));
						} elseif ($_POST['quantity'] > $service['rows']['max']) {
							$result = array('status' => false, 'data' => array('msg' => 'Jumlah pesan tidak sesuai'));
						} elseif ($user['rows']['balance'] < $total_price) {
							$result = array('status' => false, 'data' => array('msg' => 'Saldo tidak cukup'));
						} else {
							$result_api = false;
							$curl = '';
							$provider_order_id = '3';
							if ($service['rows']['provider_id'] == '3') { // MANUAL
								$result_api = '3';
							} elseif ($service['rows']['provider_id'] == '1') { // MEDAN
								$post_api = array(
									'api_key' => $provider['rows']['api_key'], // api key Anda
                                 	'sosial' => 'order',
                            	    'service' => $service['rows']['provider_service_id'], // id layanan
                            	    'target' => $_POST['data'],
                            	    'quantity' => $_POST['quantity']
								);
								$curl = post_curl($provider['rows']['api_url_order'], $post_api);
								$result = json_decode($curl, true);
								if (isset($result['status']) AND $result['status'] == true) {
									$provider_order_id = $result['data']['trx'];
									$result_api = true;
								}
							}
							if ($result_api == false) {
								$result = array('status' => false, 'data' => array('msg' => 'Layanan tidak tersedia'));
							} else {
								$input_post = array(
									'user_id' => $user['rows']['id'],
									'service_name' => $service['rows']['service_name'],
									'data' => $_POST['data'],
									'quantity' => $_POST['quantity'],
									'price' => $total_price,
									'profit' => $total_profit,
									'remains' => $_POST['quantity'],
									'status' => 'Pending',
									'provider_id' => $service['rows']['provider_id'],
									'provider_order_id' => $provider_order_id,
									'created_at' => date('Y-m-d H:i:s'),
									'api_order_log' => $curl,
									'is_api' => 1
								);
								$insert = $model->db_insert($db, "orders", $input_post);
								$model->db_update($db, "users", array('balance' => $user['rows']['balance'] - $total_price), "id = '".$user['rows']['id']."'");
								$model->db_insert($db, "balance_logs", array('user_id' => $user['rows']['id'], 'type' => 'minus', 'amount' => $total_price, 'note' => 'Membuat Pesanan melalui API. ID Pesanan: '.$insert.'.', 'created_at' => date('Y-m-d H:i:s')));
								$result = array('status' => true, 'data' => array('id' => $insert));
							}
						}
					}
				}
			} elseif ($_POST['action'] == 'status') {
				if (check_input($_POST, array('id')) == false) {
					$result = array('status' => false, 'data' => array('msg' => 'Permintaan tidak sesuai'));
				} else {
					$order = $model->db_query($db, "*", "orders", "id = '".mysqli_real_escape_string($db, $_POST['id'])."' AND user_id = '".$user['rows']['id']."'");
					if ($order['count'] == 0) {
						$result = array('status' => false, 'data' => array('msg' => 'Pesanan tidak ditemukan'));
					} else {
						$result = array('status' => true, 'data' => array('status' => $order['rows']['status'], 'start_count' => $order['rows']['start_count'], 'remains' => $order['rows']['remains']));
					}
				}
			} elseif ($_POST['action'] == 'services') {
				$service = mysqli_query($db, "SELECT services.id, service_name AS name, price, min, max, note, categories.name AS category FROM services JOIN categories ON categories.id = services.category_id WHERE status = '1'");
				$services = array();
				while ($data = mysqli_fetch_assoc($service)) {
					$services[] = $data;
				}
				$result = array('status' => true, 'data' => $services);
			} else {
				$result = array('status' => false, 'data' => array('msg' => 'Permintaan tidak sesuai'));
			}
		}
	}
} else {
	$result = array('status' => false, 'data' => array('msg' => 'Permintaan tidak sesuai'));
}
print(json_encode($result, JSON_PRETTY_PRINT));