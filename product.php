<?php
require('includes/config.php');
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
	<a class="navbar-brand" href="./index.php"><?php echo $navbar_title; ?></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarText">
		<ul class="navbar-nav mr-auto">
		<li class="nav-item">
			<a class="nav-link" href="./index.php">Home</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active" href="./store.php">Store <span class="sr-only">(current)</span></a>
		</li>
		</ul>
		<span class="navbar-text">
		<a href="./admin/index.php" class="btn btn-outline-primary my-2 my-sm-0">Login</a>
		</span>
	</div>
	</nav>
	
	</br>
	<div class="container">

	<?php
	
	if (isset($_GET['item'])) {
	
	$item = $_GET['item'];
	
	$query="SELECT * FROM items WHERE name = '".mysqli_real_escape_string($link, $item)."'";
	$result=mysqli_query($link , $query);
	$post=mysqli_fetch_assoc($result);

	$image=$post['imageurl'];
	$text=$post['description'];
	$price=$post['price'];
	
	echo '
	<center>
	<div class="card card-custom" style="width: 550px;">
	<img class="card-img-top" src="'.$image.'" alt="Product image could not be obtained.">
	<div class="card-body">
	  <h5 class="card-title">'.$item.'</h5>
	  <p class="card-text">'.$text.'</p>
	  <div class="product-hr"> <hr> </div>
	 
	  <h5>Price: <b class="float-center" style="color: green" id="origprice">'.$price.' '.$currency_simbol.'</b><b class="float-center" style="color: green" id="discprice"></b></h5>
	 
	  </br>
	  <form class="form-inline" action="./start.php">
		<div class="form-group mb-2">
			<input type="hidden" name="item" value="'.$item.'" />
			<select class="form-control" name="option">
				<option>PayPal</option>';
			if ($g2a_enabled == true) {
				echo '
					<option>G2A_Pay</option>
				';
			}
	echo '
			</select>
		</div>
		<div class="form-group mx-sm-3 mb-2">
			<input type="text" class="form-control" name="username" placeholder="In game name" required>
		</div>
		<button type="submit" class="btn btn-success mb-2">Purchase</button>
		<br/>
		<div class="form-group mx-sm-3 mb-2">
			<input type="text" class="form-control" name="coupon" id="coupon" placeholder="Coupon">
		</div>
		<button type="button" class="btn btn-info mb-2" id="couponbtn">Check coupon</button>
	</form>
	<div class="product-brand">
	 <img hspace="10" src="img/paypal.png" alt="PayPal Secure" height="50" width="150">
	  <img hspace="10" src="img/psc.png" alt="PaySafeCard Secure" height="50" width="150">
	</div>
	</div>
	</center>
	</br>
	';
	} else {
		echo '<script>window.location.href = "./index.php";</script>';
	}
	
	?>

	</div>

	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<!-- Required JavaScript -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<!-- Optional JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<!-- Required JavaScript -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>

	<script type="text/javascript">
		$("#couponbtn").click(function(){
			var text = $('#coupon').val();
			$.ajax({
				type: "POST",
				url: "admin/couponcheck.php",
				data: { coupon: text},
				statusCode: {
					400: function() {
						alert("No coupon code provided!");
					},
					404: function() {
						alert("Coupon code not found.");
					}
				},
				success: function(result) { 
					alert(result);
				}  
			});
		});
	</script>
  </body>
</html>