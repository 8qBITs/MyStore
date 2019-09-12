<?php
require('../includes/config.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// $loggedin = false;

// if(isset($_COOKIE["loggedin"])) {
// 	mysqli_query($link,"DELETE FROM `logins` WHERE `expire`<NOW()");
// 	$hash = mysqli_query($link, "SELECT * FROM logins WHERE hash='".mysqli_real_escape_string($link,$_COOKIE["loggedin"])."'")->fetch_assoc();
// 	if(isset($hash["id"])) {
// 		mysqli_query($link, "UPDATE `logins` SET `expire`=NOW() + INTERVAL 1 HOUR WHERE id=".$hash["id"]);
// 		setcookie("loggedin", $hash["hash"], time()+3600);
// 		$loggedin = true;
// 	} else {
// 		die('<script>window.location.href = "index.php";</script>');
// 	}
// } else {
// 	die('<script>window.location.href = "index.php";</script>');
// }

function loginbtn() {
	global $link;
	$doesloginsexist = mysqli_query($link,"SELECT * FROM logins");
	if(!$doesloginsexist && basename($_SERVER["SCRIPT_NAME"])!="dbupdate.php") {
		echo '<script>alert("Your database is outdated. Please click ok to update. The site cannot function without an update. (All data will be saved)");</script>';
		die('<script>window.location.href = "./dbupdate.php?info=needed";</script>');
	} elseif(!$doesloginsexist && basename($_SERVER["SCRIPT_NAME"])=="dbupdate.php") {
		return;
	}
	$loggedin = false;
	if(isset($_COOKIE["loggedin"])) {
		mysqli_query($link,"DELETE FROM `logins` WHERE `expire`<NOW()");
		$hash = mysqli_query($link, "SELECT * FROM logins WHERE hash='".mysqli_real_escape_string($link,$_COOKIE["loggedin"])."'")->fetch_assoc();
		if(isset($hash["id"])) {
			mysqli_query($link, "UPDATE `logins` SET `expire`=NOW() + INTERVAL 1 HOUR WHERE id=".$hash["id"]);
			setcookie("loggedin", $hash["hash"], time()+3600);
			$loggedin = true;
		} else {
			die('<script>window.location.href = "index.php";</script>');
		}	} else {
		die('<script>window.location.href = "index.php";</script>');
	}
	if ($loggedin) {
		echo '
		<a style="margin-right: 10px" href="./logout.php" class="btn btn-outline-danger my-2 my-sm-0 d-none d-md-block" type="submit">Logout</a>';
	} else {
		echo '<script>window.location.href = "./index.php";</script>';
		echo '
		<button style="margin-right: 10px" class="btn btn-outline-primary my-2 my-sm-0 d-none d-md-block" type="submit">Login</button>
		';
	}
}