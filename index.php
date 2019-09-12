<?php
require('includes/config.php');

	$display = '';
	
	if (isset($_GET['info'])) {
		$info = $_GET['info'];
		
		if ($info == "Success") {
			$display = '<div class="alert alert-success" role="alert">
				  '.$donation_success.'
				</div>';
		} elseif ($info == "Banned") {
			$display = '<div class="alert alert-danger" role="alert">
				  '.$donation_banned.'
				</div>';
		}
		
	} elseif (isset($_GET['err'])) {
		$error = $_GET['err'];
		
		if ($error == "disFeature") {
			$display = '<div class="alert alert-warning" role="alert">
				  This feature has been disabled, Please contact the website administrator.
				</div>';
		}
		
	}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">

    <title><?php echo $navbar_title." - Donation Store"; ?></title>
	<link rel="icon" href="<?php echo $site_favicon; ?>">
  </head>
  <body>

	<nav class="navbar navbar-expand-lg navbar-custom">
	<a class="navbar-brand" href="./index.php"><div class="navbar-custom-text"><?php echo $navbar_title; ?></div></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarText">
		<ul class="navbar-nav mr-auto">
		<li class="nav-item active">
			<a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="./store.php">Store</a>
		</li>
		</ul>
		<span class="navbar-text">
		<a href="./admin/index.php" class="btn btn-outline-primary my-2 my-sm-0">Login</a>
		</span>
	</div>
	</nav>
	
	<div class="jumbotron jumbotron-fluid jumbotron-custom">
		<center><h1 class="display-4"><?php echo $navbar_title; ?></h1></center>
			<center><p class="lead"><?php echo $store_description; ?></p></center>
		<hr class="my-4">
			<center><p><?php echo $store_description2; ?></p></center>
		<center><a class="btn btn-outline-primary btn-lg" href="./store.php" role="button">Visit Store</a></center>
	</div>
	
	<div class="container">
	
	<?php
	
		echo $display;
	
	?>
	
	<div class="card tos-box mb-3">
	<div class="card-body">
		<?php include ('includes/tos.html'); ?>
	</div>
	</div>
	</div>
	
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>