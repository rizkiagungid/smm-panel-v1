<?php
function format_date($date) {
	$split = explode("-", $date);
	$month = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	if (in_array($split[1], array_keys($month)) == false) {
		return "Something went wrong.";
	}
	return $split[2].' '.$month[$split[1]].' '.$split[0];
}

function protect_input($input) {
	global $db;
	$result = mysqli_real_escape_string($db, $input);
	return $result;
}

function check_input($input, $data) {
	$input = array_keys($input);
	$false = 0;
	foreach ($data as $key) {
		if (in_array($key, $input) == false) {
			$false++;
		}
	}
	if ($false == 0) {
		return true;
	} else {
		return false;
	}
}

function check_empty($input) {
	$result = true;
	foreach ($input as $key => $value) {
		$result = false;
		if (empty($value) == true) {
			$result = true;
			break;
		}
	}
	return $result;
}

function str_rand($length = 15) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function get_client_ip() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP')) {
		$ipaddress = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('HTTP_X_FORWARDED')) {
		$ipaddress = getenv('HTTP_X_FORWARDED');
	} elseif (getenv('HTTP_FORWARDED_FOR')) {
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	} elseif (getenv('HTTP_FORWARDED')) {
		$ipaddress = getenv('HTTP_FORWARDED');
	} elseif (getenv('REMOTE_ADDR')) {
		$ipaddress = getenv('REMOTE_ADDR');
	} else {
		$ipaddress = 'UNKNOWN';
	}
	return $ipaddress;
}

function post_curl($end_point, $post) {
	$_post = array();
	if (is_array($post)) {
		foreach ($post as $name => $value) {
			$_post[] = $name.'='.urlencode($value);
		}
	}
	$ch = curl_init($end_point);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	if (is_array($post)) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
	}
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	$result = curl_exec($ch);
	if (curl_errno($ch) != 0 && empty($result)) {
		$result = false;
	}
	curl_close($ch);
	return $result;
}

function validate_date($date) {
	$d = DateTime::createFromFormat('Y-m-d', $date);
	return $d && $d->format('Y-m-d') == $date;
}