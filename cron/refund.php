<?php
/**
 * Pencipta kode - SMM Panel script
 * Domain: http://medan-smm.co.id/ *
 */
 
// REFUND ORDER ERROR & PARTIAL

error_reporting(0);

require '../mainconfig.php';

$orders = mysqli_query($db, "SELECT * FROM orders WHERE status IN ('Partial', 'Error') AND is_refund = '0' ORDER BY id ASC LIMIT 50");
while ($order = mysqli_fetch_array($orders)) {
	$user = mysqli_query($db, "SELECT * FROM users WHERE id = '".$order['user_id']."'");
	$user = mysqli_fetch_assoc($user);
	$remains = ($order['remains'] > $order['quantity']) ? $order['quantity'] : $order['remains'];
	$min_price = ($order['price'] / $order['quantity']) * $remains; // refund amount
	$min_profit = ($order['profit'] / $order['quantity']) * $remains;
	if ($order['remains'] ==  0) {
		$min_price = $order['price'];
		$min_profit = $order['profit'];
	}
		
	if($order['remains'] > $order['quantity']) {
		$min_price = $order['price'];
		$min_profit = $order['profit'];
	}
	$a = "UPDATE orders SET price = '".($order['price'] - $min_price)."', profit = '".($order['profit'] - $min_profit)."', is_refund = '1' WHERE id = '".$order['id']."'";
	mysqli_query($db, $a);
	$b = "UPDATE users SET balance = '".($user['balance'] + $min_price)."' WHERE id = '".$order['user_id']."'";
	mysqli_query($db, $b);
	$c = "INSERT INTO balance_logs (user_id, type, amount, note, created_at) VALUES ('".$order['user_id']."', 'plus', '".$min_price."', 'Pengembalian dana. ID Pesanan: ".$order['id'].".', '".date('Y-m-d H:i:s')."')";
	mysqli_query($db, $c);
	print('ID: '.$order['id'].', USER: '.$user['username'].', AMOUNT: '.$min_price);
}