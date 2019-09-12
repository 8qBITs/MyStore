<?php
// Version control DO NOT CHANGE THIS!!

$version = "4.3";
$db_version = 1.3;

// PayPal settings

$config = array(
	// Request API access here https://www.paypal.com/businessprofile/mytools/apiaccess/firstparty
	// No business account needed! :)
  "environment" => "live", // sandbox or live
  "user"  => "my.email@email.com",
  "pwd"  => "password",
  "signature"  => "paypal signature",
  "version"  => 113
);


// G2A Pay settings

$g2a_enabled = true;
$API_Hash = "";
$Merchant_Email = "";
$API_Secret = "";

// Currency settings

$currency_simbol = "€";
$currency_code = "EUR"; // USD or EUR or GBP etc..
$donation_goal = 50;

// Google captcha
// Please enable this for sake of you security!
// Get a key at https://www.google.com/recaptcha/admin/create

$captcha_enabled = false;
$captcha_site_key = "";
$captcha_secret_key = "";

// Website settings

// https://website.me <--- example DO NOT PUT / BEHIND!
$website_ip_or_domain_name = "localhost";
$navbar_title = "My Store";
$company_name = "EpicServer";
$store_description = "Help us keep server alive by donating and be one of the COOL boys!";
$store_description2 = "Donate for awesome rewards & perks, Click button bellow for more info!";
// 32x32px .ico image
$site_favicon = "img/favicon.ico";
// 1080p image
$site_background = "img/background.jpg";

$copyright_text = "Copyright &copy; ".$company_name." ".date("Y")." All rights reserved.";
$copyright_link = "./index.php";

$donation_success = "Thank you very much for your donation!";
$donation_banned = "We are sorry but you are banned from our store, Please contact an admin if you think this is an error";

// Admin login details

$admin_user = "operator";
$admin_pass = "asd";

// MySql settings

$sql_host = "localhost";
$sql_username = "root";
$sql_password = "password";
$sql_database = "mystore";

// Do not mess with this!

$link = mysqli_connect($sql_host, $sql_username, $sql_password, $sql_database);
        
        if (mysqli_connect_error()) {      
            die ("Database connection error please contact website admin!");     
        }

?>