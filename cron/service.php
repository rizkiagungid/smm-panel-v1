<?php

error_reporting(0);

require '../mainconfig.php';

$provider = mysqli_query($db, "SELECT * FROM provider WHERE id = '1'");
$provider = mysqli_fetch_assoc($provider);

$curl = post_curl('https://rasxmedia.com/api/social-media', array('api_key' => $provider['api_key'], 'action' => 'services'));
$result = json_decode($curl, true);

if ($result['status'] == false) exit("Curl gagal! ".$curl."");

foreach($result['data'] as $service) {
    $post_cat = $service['category'];
	$check_cat = mysqli_query($db, "SELECT * FROM categories WHERE name = '$post_cat'");
    $data_cat = mysqli_fetch_assoc($check_cat);
    if (mysqli_num_rows($check_cat) == 0) {
        $insert_cat = mysqli_query($db, "INSERT INTO categories (name) VALUES ('$post_cat')");
        if ($insert_cat == TRUE) {
            $check_data = mysqli_query($db, "SELECT * FROM categories WHERE name = '$post_cat'");
            $get_data = mysqli_fetch_assoc($check_data);
            $service['category'] = $get_data['id'];
        } else {
            $service['category'] = $post_cat;
        }
    } else {
        $service['category'] = $data_cat['id'];
    }
	$cek_id = mysqli_query($db, "SELECT * FROM services WHERE provider_service_id = '".$service['id']."'");
	if (mysqli_num_rows($cek_id) > 0) {
		echo 'Layanan sudah ada didatabase.<br />';
	} else {
		$service_name = strtr($service['name'], array(
			' RASXMEDIA' => ' EDIT', // EDIT
		));
		$baru = $service['price']*2;
		$total_profit = $baru-$service['price'];
		$insert = mysqli_query($db, "INSERT INTO `services`(`category_id`, `service_name`, `note`, `price`, `profit`, `min`, `max`, `status`, `provider_id`, `provider_service_id`) VALUES ('".$service['category']."','".$service_name."','".$service['note']."','$baru','$total_profit','".$service['min']."','".$service['max']."','1','1','".$service['id']."')");
		echo ($insert == true) ? 'sukses<br />' : mysqli_error($db).'<br />';
	}
}