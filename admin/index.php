<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('../includes/config.php');

$doesloginsexist = mysqli_query($link,"SELECT * FROM logins");
if(!$doesloginsexist && basename($_SERVER["SCRIPT_NAME"])!="dbupdate.php") {
	echo '<script>alert("Your database is outdated. Please click ok to update. The site cannot function without an update. (All data will be saved)");</script>';
	die('<script>window.location.href = "./dbupdate.php?info=needed";</script>');
} elseif(!$doesloginsexist && basename($_SERVER["SCRIPT_NAME"])=="dbupdate.php") {
	return;
}

$loggedin = false;

$id = 0;
$error = false;
if (isset($_COOKIE['loggedin'])){
	mysqli_query($link,"DELETE FROM `logins` WHERE `expire`<NOW()");
	$hash = mysqli_query($link, "SELECT * FROM logins WHERE hash='".mysqli_real_escape_string($link,$_COOKIE["loggedin"])."'")->fetch_assoc();
	if(isset($hash["id"])) {
		mysqli_query($link, "UPDATE `logins` SET `expire`=NOW() + INTERVAL 1 HOUR WHERE id=".$hash["id"]);
		setcookie("loggedin", $hash["hash"], time()+3600);
		$loggedin = true;
	} else {
		setcookie("loggedin","");
		$loggedin = false;
	}
}
	
if (isset($_POST['username']) && isset($_POST['password']) && !$loggedin) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	if($captcha_enabled) {
		if(isset($_POST['g-recaptcha-response'])) {
			$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
			$recaptcha_response = $_POST['g-recaptcha-response'];

			$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $captcha_secret_key . '&response=' . $recaptcha_response);
			$recaptcha = json_decode($recaptcha);

			if ($recaptcha->success) {
				if ($username == $admin_user && $password == $admin_pass) {
					$loginhash = mysqli_real_escape_string($link, bin2hex(random_bytes(22)));
					mysqli_query($link, "INSERT INTO `logins`(`hash`, `ip`, `expire`) VALUES ('".$loginhash."', '".mysqli_real_escape_string($link, $_SERVER["REMOTE_ADDR"])."', NOW() + INTERVAL 1 HOUR)");
					setcookie("loggedin", $loginhash, time()+3600);
					$loggedin = true;
				}
			} else {
				$captchaerror = true;
				$loggedin = false;
			}
		} else {

		}
	} else {
		if ($username == $admin_user && $password == $admin_pass) {
			$loginhash = mysqli_real_escape_string($link, bin2hex(random_bytes(22)));
			mysqli_query($link, "INSERT INTO `logins`(`hash`, `ip`, `expire`) VALUES ('".$loginhash."', '".mysqli_real_escape_string($link, $_SERVER["REMOTE_ADDR"])."', NOW() + INTERVAL 1 HOUR)");
			setcookie("loggedin", $loginhash, time()+3600);
			$loggedin = true;
		}
	}
	
}

?>	
<!DOCTYPE html>
<html lang="en">
  <head>
	<title><?php echo $navbar_title." - Donation Store"; ?></title>
	<link rel="icon" href="../<?php echo $site_favicon; ?>">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<script src="../js/Chart.js"></script>
	<?php if($captcha_enabled) { echo '<script src="https://www.google.com/recaptcha/api.js?onload=onload&render=explicit" async defer></script>';} ?>
	<link rel="stylesheet" type="text/css" href="../css/adminx.css" media="screen" />

	<!--
	  # Optional Resources
	  Feel free to delete these if you don't need them in your project
	-->
  </head>
  <body>
	  <div class="adminx-container">
	  <nav class="navbar navbar-expand justify-content-between fixed-top">
		<a class="navbar-brand mb-0 h1 d-none d-md-block" href="./index.php">
		  <img src="./img/icons/shopping-cart.svg" class="navbar-brand-image d-inline-block align-top mr-2" alt="">
		  MyStore <small>v<?=$version ?></small> - The perfect donation store solution.
		</a>
	  <!-- </nav> -->
	  
	  <?php	

		if ($loggedin) {
			echo '
			<a style="margin-right: 10px" href="./logout.php" class="btn btn-outline-danger my-2 my-sm-0 d-none d-md-block" type="submit">Logout</a>';
		}
		echo '</nav>';
		
		if ($loggedin == false) {
		if($captcha_enabled) {
			echo '
			<script>
				var onload = function() {
					grecaptcha.render("captcha", {
					  "sitekey" : "'.$captcha_site_key.'"
					});
				};
			</script>';
		}
		echo '
		<div class="container py-5">
		<div class="row">
			<div class="col-md-12">
				<h2 class="text-center login-form-title mb-4">'.$navbar_title.' Admin Login</h2>
				<div class="row">
					<div class="col-md-6 mx-auto">
						<div class="card rounded-0 transparent">
							<div class="card-header login-form">
								<h3 class="mb-0">Login</h3>
							</div>
							<div class="card-body login-form-body">
								<form class="form" action="./index.php" method="POST">
									<div class="form-group">
										<label for="uname1">Username</label>
										<input type="text" class="form-control form-control-lg rounded-0" name="username">
										<div class="invalid-feedback">Oops, you missed this one.</div>
									</div>
									<div class="form-group">
										<label>Password</label>
										<input type="password" class="form-control form-control-lg rounded-0" name="password">
										<div class="invalid-feedback">Enter your password too!</div>
									</div>';
									if(isset($captchaerror)) {
										echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
											<strong>Error!</strong> Captcha not solved
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>';
									}
									if($captcha_enabled) { echo '<div class="g-recaptcha" data-sitekey="'.$captcha_site_key.'" id="captcha"></div>'; }
									echo '<button type="submit" class="btn btn-success btn-lg float-right" id="btnLogin">Login</button>
								</form>
							</div>
						</div>

					</div>


				</div>

			</div>
		</div>
		</div>
		</br></br></br>
		</br></br></br>
		</br></br></br>
		';
		} else {

		  include('includes/admin-nav.php');
		  echo '<!-- adminx-content-aside -->
	  <div class="adminx-content">
		<!-- <div class="adminx-aside">

		</div> -->

		<div class="adminx-main-content">
		  <div class="container-fluid">
			<!-- BreadCrumb -->
			<nav aria-label="breadcrumb" role="navigation">
			  <ol class="breadcrumb adminx-page-breadcrumb">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
			  </ol>
			</nav>

			<div class="pb-3">
			  <h1>Dashboard</h1>
			</div>';
			
			$user_latest_query = mysqli_query($link,"SELECT * FROM transactions WHERE id=(SELECT MAX(id) FROM transactions)")->fetch_assoc();
			$user_latest = $user_latest_query["user"];
				
			$total = 0;
			
			$totalquery = mysqli_query($link, "SELECT SUM(price) FROM `transactions`")->fetch_assoc();
			$total = $totalquery["SUM(price)"];
			
				
			$month = 0;
			
			$monthquery=mysqli_query($link,"SELECT SUM(price) FROM `transactions` WHERE `date` BETWEEN date_sub(CAST(CURRENT_TIMESTAMP AS DATE),INTERVAL 1 MONTH) AND CAST(CURRENT_TIMESTAMP AS DATE);")->fetch_assoc();
			$month = $monthquery["SUM(price)"];
				
			$percent = 0;
			
			$percent = ($month*100)/$donation_goal;
			$goal = round($percent, 2);
			
			echo '

			<div class="row">
			  <div class="col-md-6 col-lg-3 d-flex">
				<div class="card border-0 bg-success text-white text-center mb-grid w-100">
				  <div class="d-flex flex-row align-items-center h-100">
					<div class="card-icon d-flex align-items-center h-100 justify-content-center">
					  <img src="./img/icons/check-circle.svg"></img>
					</div>
					<div class="card-body">
					  <div class="card-info-title">Monthly donation goal</div>
					  <h3 class="card-title mb-0">
						<div class="progress" style="margin-top: 17px">
							<div class="progress-bar" role="progressbar" style="width: '.$goal.'%;color:black !important;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">'.$goal.'%</div>
						</div>
					  </h3>
					</div>
				  </div>
				</div>
			  </div>

			  <div class="col-md-6 col-lg-3 d-flex">
				<div class="card border-0 bg-warning text-white text-center mb-grid w-100">
				  <div class="d-flex flex-row align-items-center h-100">
					<div class="card-icon d-flex align-items-center h-100 justify-content-center">
					  <img src="./img/icons/award.svg"></img>
					</div>
					<div class="card-body">
					  <div class="card-info-title">Latest donator</div>
					  <h3 class="card-title mb-0">
						'.(isset($user_latest) ? $user_latest : "No donations yet").'
					  </h3>
					</div>
				  </div>
				</div>
			  </div>

			  <div class="col-md-6 col-lg-3 d-flex">
				<div class="card border-0 bg-primary text-white text-center mb-grid w-100">
				  <div class="d-flex flex-row align-items-center h-100">
					<div class="card-icon d-flex align-items-center h-100 justify-content-center">
					  <img src="./img/icons/dollar-sign.svg"></img>
					</div>
					<div class="card-body">
					  <div class="card-info-title">Donations (total)</div>
					  <h3 class="card-title mb-0">
						'.sprintf('%0.2f', $total).' '.$currency_simbol.'
					  </h3>
					</div>
				  </div>
				</div>
			  </div>

			  <div class="col-md-6 col-lg-3 d-flex">
				<div class="card border-0 bg-info text-white text-center mb-grid w-100">
				  <div class="d-flex flex-row align-items-center h-100">
					<div class="card-icon d-flex align-items-center h-100 justify-content-center">
					  <img src="./img/icons/dollar-sign.svg"></img>
					</div>
					<div class="card-body">
					  <div class="card-info-title">Donations (This month)</div>
					  <h3 class="card-title mb-0">
						'.sprintf('%0.2f', $month).' '.$currency_simbol.'
					  </h3>
					</div>
				  </div>
				</div>
			  </div>
			</div>
			
			';
		
	?>
			
			<div class="row">
			  <div class="col-lg-8">
			  
			  <div class="card">
					  <div class="card-header">
					  <img src="./img/icons/bookmark.svg"></img>
						Developer announcments & Alerts
					  </div>
					  <div class="card-body">
						<p class="card-text">
			  
			  <?php
			  
			  $content = file_get_contents("http://8qbit.me/static/mst/get_info.json");

				$cont = json_decode($content);
				
				if ($version != $cont->version) {
					echo '
					
					<div class="alert alert-danger" role="alert">
					  Your version of MyStore > '.$version.' is outdated, Please download new version > '.$cont->version.' from <a href="https://www.mc-market.org/resources/8260/">here</a>.
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>
					
					</br>
					
					';
					
				}

				$dbinfo = mysqli_query($link,"SELECT * FROM database_info");
				$curr_db_version = ($dbinfo ? $dbinfo->fetch_assoc()["version"] : null);
				if($db_version>$curr_db_version)
				{
					echo '
					
					<div class="alert alert-danger" role="alert">
					  Your version of database > '.(isset($curr_db_version) ? $curr_db_version : "unknown").' is outdated, Please upgrade your database version > '.$db_version.' using this <a href="./dbupdate.php">script</a> (All data will be imported).
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>
					
					</br>
					
					';
				}
				
				foreach($cont->posts[0] as $post) { 
					echo '
					<div class="card">
					  <div class="card-header">
						<img src="./img/icons/bookmark.svg"></img>
						'.$post->title.'
					  </div>
					  <div class="card-body">
						<p class="card-text">
						'.$post->content.'
						</p>
					  </div>
					</div>
					
					</br>
					
					';
					
				}
				}
			  
			  ?>
				
						</p>
					  </div>
					</div>
				
			  </div>
			  <div class="col-lg-4">
				<div class="card">
				  <div class="card-header">
				  <img src="./img/icons/box.svg"></img>
					Resources & Info:
				  </div>
				  <div class="card-body">
					<h4 class="card-title">Plugin Downloads:</h4>
					
					<?php
					
					foreach($cont->resources[0] as $resource) { 
					echo '
					<a type="button" href="'.$resource->href.'" class="btn btn-'.$resource->type.'">'.$resource->name.'</a>
					';
					}
					
					?>
					
					<hr>
					
					<h4 class="card-title">Live Support:</h4>
					
					<a type="button" href="https://discord.gg/PGXYBuq" class="btn btn-primary">Discord Server</a>
					
					<hr>
					
					</br>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-- If you prefer jQuery these are the required scripts -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
	<script src="./dist/js/vendor.js"></script>
	<script src="./dist/js/adminx.js"></script>

	<!-- If you prefer vanilla JS these are the only required scripts -->
	<!-- script src="./dist/js/vendor.js"></script>
	<script src="./dist/js/adminx.vanilla.js"></script-->
  </body>
</html>