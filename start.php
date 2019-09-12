<?php

require('./includes/config.php');
require('./includes/paypal.php');
require('./includes/G2APay.php');
use G2APay\G2APay;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$paypal = new PayPal($config);

$username = $_GET['username'];
$item = $_GET['item'];

$option = (isset($_GET['option']) ? $_GET['option'] : '');
$coupon = (isset($_GET['coupon']) && $_GET["coupon"]!="" ? $_GET['coupon'] : null);
	
$ip = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['HTTP_CLIENT_IP']));

$query="SELECT * FROM items WHERE name = '".mysqli_real_escape_string($link, $item)."'"; 
$result=mysqli_query($link , $query);
$post=mysqli_fetch_assoc($result);
$price=$post['price'];
$servers=json_decode($post['servers']);
$command=json_decode($post['command']);
if($post["onetime"]) {
	$alreadyboughtq = mysqli_query($link,"SELECT * FROM transactions WHERE user='".$username."' AND item='".$item."'");
	if($alreadyboughtq && $alreadyboughtq->fetch_assoc()["user"]==$username) {
		die('<script>window.location.href = "index.php?info=Onetime";</script>');
	}
}
$discount = 0;

// Check if user is banned

$ban = mysqli_query($link,"SELECT * FROM banned_users WHERE user='".mysqli_real_escape_string($link,$username)."'")->fetch_assoc();
if(isset($ban) && isset($ban["id"])) {
	die('<script>window.location.href = "index.php?info=Banned";</script>');
}


// Paypal process

if (isset($_GET['token'])) {
	if(isset(mysqli_query($link,"SELECT * FROM transactions WHERE transaction_id='".mysqli_real_escape_string($link,$_GET['token'])."'")->fetch_assoc()["id"])) {
		die('<script>window.location.href = "./index.php?info=Fail";</script>');
	}
	if(isset($coupon) && $coupon!="") {
		$c = mysqli_query($link, "SELECT * FROM coupons WHERE code='".mysqli_real_escape_string($link,$coupon)."' AND (uses>0 OR uses IS NULL)")->fetch_assoc() or null;
		if(isset($c)) {
			if(isset($c["uses"]) && $c["uses"]!=null) {
				mysqli_query($link,"UPDATE coupons SET `uses`=`uses`-1 WHERE id=".mysqli_real_escape_string($link,$c["id"]));
			}
			if(strpos($c["discount"],"%")) {
				$percentage = intval(substr($c["discount"], 0, -1));
				if($percentage>=100) {
					$discount = $price;
				} else {
					$discount = ($percentage*$price)/100;
				}
			} else {
				$discint = intval($c["discount"]);
				$discount = ($discint>=$price ? $price : $discint);
			}
		}
	}
	
	$result = $paypal->call(array(
	'method'  => 'DoExpressCheckoutPayment',
	'paymentrequest_0_paymentaction' => 'sale',
	'paymentrequest_0_amt'  => $price-$discount,
	'paymentrequest_0_currencycode'  => $currency_code,
	'token'  => $_GET['token'],
	'payerid'  => $_GET['PayerID'],
	));

	if ($result['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {
		
		// mysqli_query($link,"INSERT INTO transactions (item, user, ip, method, date, price) VALUES ('".mysqli_real_escape_string($link, $item)."', '".mysqli_real_escape_string($link, $username)."', '".mysqli_real_escape_string($link,$ip)."', 'PayPal', '".date("Y-m-d")."', ".mysqli_real_escape_string($link, $price-$discount).")");
		mysqli_query($link, "INSERT INTO `transactions`(`item`, `user`, `ip`, `transaction_id`, `payer_id`, `method`, `date`, `price`) VALUES ('".mysqli_real_escape_string($link,$item)."', '".mysqli_real_escape_string($link,$username)."', '".mysqli_real_escape_string($link,$ip)."', '".mysqli_real_escape_string($link,$_GET["token"])."', '".mysqli_real_escape_string($link,$_GET["PayerID"])."', 'PayPal', '".date("Y-m-d")."', ".mysqli_real_escape_string($link,$price-$discount).")");
			
		processCommand($username, $command);
		
		echo '<script>window.location.href = "./index.php?info=Success";</script>';
	} else {
		echo '<script>window.location.href = "./index.php?info=Fail";</script>';
	}
	
}

// Make Payment

if(isset($coupon) && $coupon!="") {
	$c = mysqli_query($link, "SELECT * FROM coupons WHERE code='".mysqli_real_escape_string($link,$coupon)."' AND (uses>0 OR uses IS NULL)")->fetch_assoc() or null;
	if(isset($c)) {
		if(strpos($c["discount"],"%")) {
			$percentage = intval(substr($c["discount"], 0, -1));
			if($percentage>=100) {
				$discount = $price;
			} else {
				$discount = ($percentage*$price)/100;
			}
		} else {
			$discint = intval($c["discount"]);
			$discount = ($discint>=$price ? $price : $discint);
		}
	}
}

if ($price-$discount == 0) {
	mysqli_query($link, "INSERT INTO `transactions`(`item`, `user`, `ip`, `transaction_id`, `payer_id`, `method`, `date`, `price`) VALUES ('".mysqli_real_escape_string($link,$item)."', '".mysqli_real_escape_string($link,$username)."', '".mysqli_real_escape_string($link,$ip)."', 'none', 'none', 'PayPal', '".date("Y-m-d")."', ".mysqli_real_escape_string($link,$price-$discount).")");
			
	processCommand($username, $command);
	
	echo '<script>window.location.href = "./index.php?info=Success";</script>';
}

if ($option == "PayPal") {
	$result = $paypal->call(array(
	'method'  => 'SetExpressCheckout',
	'paymentrequest_0_paymentaction' => 'sale',
	'paymentrequest_0_amt'  => $price-$discount,
	'paymentrequest_0_currencycode'  => $currency_code,
	'returnurl'  => 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/start.php?username='.$username.'&item='.$item.'&ip='.$ip.(isset($coupon) && $coupon!="" ? '&coupon='.$coupon : ''),
	'cancelurl'  => 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/cancel.php',
	));

	if ($result['ACK'] == 'Success') {
		$paypal->redirect($result);
	} else {
		echo 'Handle the payment creation failure.<br>';
	}
} else if ($option == "G2A_Pay") {
	
	// Set required variables
	$hash = $API_Hash; // Get it from G2APay
	$secret = $API_Secret; // Get it from G2APay
	$email = $Merchant_Email; // Your G2APay store email
	$success = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'index.php?info=Success'; // URL for successful callback;
	$fail = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'index.php?info=Fail'; // URL for failed callback;
	$order = rand(1000, 9999999); // Choose your order id or invoice number, can be anything

	// Optional
	$currency = $currency_code; // Pass currency, if no given will use "USD"

	$payment = new G2APay($hash, $secret, $email, $success, $fail, $order, $currency);
	
	$sku = 1; // Item number (In most cases $sku can be same as $id)
	$name = $item;
	$quantity = 1; // Must be integer
	$id = 1; // Your items' identifier
	
	$price = $price-$discount; // Must be float
	$url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'index.php';

	// Optional
	$extra = '';
	$type = '';

	$payment->addItem($sku, $name, $quantity, $id, $price, $url, $extra, $type);
	
	$orderId = 1; // Generate or save in your database
	$extras = []; // Optional extras passed to order (Please refer G2APay docs)
	$response = $payment->createOrder($orderId, $extras);
	$response = $payment->test()->createOrder($orderId, $extras);
	
	if ($response['success']) {
		
		// Payment complete
		
		// mysqli_query($link,"INSERT INTO transactions (item, user, ip, method, date, price) VALUES ('".mysqli_real_escape_string($link, $item)."', '".mysqli_real_escape_string($link, $username)."', '".mysqli_real_escape_string($link,$ip)."', 'G2A', '".date("Y-m-d")."', ".mysqli_real_escape_string($link, $price-$discount).")"); // not working
		mysqli_query($link, "INSERT INTO `transactions`(`item`, `user`, `ip`, `method`, `date`, `price`) VALUES ('".mysqli_real_escape_string($link,$item)."', '".mysqli_real_escape_string($link,$username)."', '".mysqli_real_escape_string($link,$ip)."', 'G2A', '".date("Y-m-d")."', ".mysqli_real_escape_string($link,$price-$discount).")");
		
		processCommand($username, $command);
		
		echo '<script>window.location.href = "./index.php?info=Success";</script>';
		
	} else {
		echo $response['message']; // print out error message
	}
	
} else {
	echo '<script>window.location.href = "./index.php?err=disFeature";</script>';
}

function processCommand($username, $commands) {

	global $item,$servers,$link;
	foreach($servers as $server) {
		if($server=="*") {
			$serverq = mysqli_query($link,"SELECT * FROM servers");
			while($selserver=mysqli_fetch_assoc($serverq)) {
				$command = "<";
				foreach($commands as $cmd) {
					$command.=$cmd."|";
				}
				$command = rtrim($command,"|").">";
				$host = "tcp://".substr($selserver["ip"],0,strpos($selserver["ip"],":"));
				$port = substr($selserver["ip"],strpos($selserver["ip"], ":")+1);
				$password = $selserver["pass"];

				$search = array("%player%","%item%");
				$replace = array($username,$item);
				$command = str_replace($search, $replace, $command);
				$errstr = '';
				$errno = '';
				$data = $password . " " . $username . " [" . $item . "] " . $command;

				if ( ($fp = fsockopen($host, $port, $errno, $errstr, 3) ) === FALSE)
					echo "$errstr ($errno)";
				else {
					echo 'Request completed!</br>';
					fwrite($fp, $data);
					fclose($fp);
				}
				echo $data."<br/>";
				}
			die('<script>window.location.href = "./index.php?info=Success";</script>');
		} else {
			$command = "<";
			foreach($commands as $cmd) {
				$command.=$cmd."|";
			}
			$command = rtrim($command,"|").">";
			$serverinfo = mysqli_query($link,"SELECT * FROM servers WHERE id=".$server)->fetch_assoc();
			$host = "tcp://".substr($serverinfo["ip"],0,strpos($serverinfo["ip"],":"));
			$port = substr($serverinfo["ip"],strpos($serverinfo["ip"], ":")+1);
			$password = $serverinfo["pass"];

			$search = array("%player%","%item%");
			$replace = array($username,$item);
			$command = str_replace($search, $replace, $command);
			$errstr = '';
			$errno = '';
			$data = $password . " " . $username . " [" . $item . "] " . $command;

			if ( ($fp = fsockopen($host, $port, $errno, $errstr, 3) ) === FALSE)
				echo "$errstr ($errno)";
			else {
				echo 'Request completed!</br>';
				fwrite($fp, $data);
				fclose($fp);
			}
			echo $data."<br/>";
		}
	}
	echo '<script>window.location.href = "./index.php?info=Success";</script>';
	die();
}

?>