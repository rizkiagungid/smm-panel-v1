<?php
require("../mainconfig.php");

$check_order = mysqli_query($db, "SELECT * FROM orders WHERE status IN ('','Pending','Processing') ORDER BY rand() LIMIT 20");

if (mysqli_num_rows($check_order) == 0) {
  die("Pesanan Bersatatus Pending Tidak Ditemukan.");
} else {
  while($data_order = mysqli_fetch_assoc($check_order)) {
    $o_oid = $data_order['id'];
    $o_poid = $data_order['provider_order_id'];
    $o_provider = $data_order['provider_id'];
  if ($o_provider == "MANUAL") {
    echo "Pesanan Manual<br />";
  } else {
    
    $check_provider = mysqli_query($db, "SELECT * FROM provider WHERE name = 'JAGOSOSMED'");
    $data_provider = mysqli_fetch_assoc($check_provider);
    
    $p_apikey = $data_provider['api_key'];
    
    if ($o_provider !== "MANUAL") {
      $api_postdata = "api_key=$p_apikey&action=status&id=$o_poid";
    } else {
      die("System error!");
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $data_provider['api_url_status']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $chresult = curl_exec($ch);
    curl_close($ch);
    $json_result = json_decode($chresult, true);
    //print_r($chresult);
        $status = $json_result['status'];
        if ($json_result['data']['status'] == "Success") {
		    $status = "Success";
	    } elseif ($json_result['data']['status'] == "Error") {
			$status = "Error";
		} elseif ($json_result['data']['status'] == "Partial") {
			$status = "Partial";
		} elseif ($json_result['data']['status'] == "Processing") {
			$status = "Processing";
		} else {
			$status = "Pending";
		}
        $start_count = $json_result['data']['start_count'];
	    $remains = $json_result['data']['remains'];
    
    $update_order = mysqli_query($db, "UPDATE orders SET status = '$status', start_count = '$start_count', remains = '$remains', api_status_log = '".$chresult."', updated_at = '".date('Y-m-d H:i:s')."' WHERE id = '$o_oid'");
    if ($update_order == TRUE) {
      echo "$o_poid status $status | start $start_count | remains $remains<br />";
    } else {
      echo "Error database.";
    }
  }
  }
}