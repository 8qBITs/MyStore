<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../includes/config.php');

if(!isset($_POST["coupon"]) || !strpos($_SERVER["HTTP_REFERER"], "product.php?")) {
	die(http_response_code(400));
}
$coupon = mysqli_query($link, "SELECT * FROM coupons WHERE code='".mysqli_real_escape_string($link,$_POST["coupon"])."' AND (uses>0 OR uses IS NULL)")->fetch_assoc();
if(!isset($coupon)) {
	die(http_response_code(404));
}

$response = (strpos($coupon['discount'], "%") ? "-".$coupon['discount'] : "-".$coupon['discount']."$");

die($response);