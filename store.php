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
		<li class="nav-item active">
			<a class="nav-link" href="./store.php">Store <span class="sr-only">(current)</span></a>
		</li>
		</ul>
		<span class="navbar-text">
		<a href="./admin/index.php" class="btn btn-outline-primary my-2 my-sm-0">Login</a>
		</span>
	</div>
	</nav>
	
	</br>
	<div class="container">
		<div class="card">
			<div class="card-body cat-header">
				<ul class="nav nav-tabs">
				<?php 
					$catsq = mysqli_query($link,"SELECT * FROM categories");
					echo '
					<li class="nav-item">
						<a class="nav-link'.($_GET["cat"]=="featured" || $_GET["cat"]=="" ? " active" : "").'" href="?cat=featured">Featured</a>
					</li>';
					while($catinfo=mysqli_fetch_assoc($catsq)) {
						echo '
						<li class="nav-item">
							<a class="nav-link'.($_GET["cat"]==$catinfo["id"] ? " active" : "").'" href="?cat='.$catinfo["id"].'">'.$catinfo["name"].'</a>
						</li>';
					}
				?>
				</ul>
			</div>
		</div>
		<br/>
	<div class="card-columns">
		<br/>
	<?php
	if(isset($_GET["cat"]) && $_GET["cat"]!="featured" && $_GET["cat"]!="") {
		$result=mysqli_query($link , "SELECT * FROM items WHERE category=".mysqli_real_escape_string($link,intval($_GET["cat"]))." ORDER BY id ASC");
		if(mysqli_num_rows($result) > 0) {
			while($post=mysqli_fetch_assoc($result)) {
				$image=$post['imageurl'];
				$name=$post['name'];
				$text=$post['description'];
				$price=$post['price'];
					
				// cut description down into 15 words for the store page
				// and display will description in about page instead.
				$small_desc = implode(' ', array_slice(explode(' ', $text), 0, 15))."..";
				$it = exif_imagetype($image);
					
				echo'
				<div class="card card-custom">
					<img class="card-img-top" src="'.(isset($image) && $image!="" && ($it == 1 || $it == 3 || $it == IMG_JPEG) ? $image : "img/404.jpg").'" style="width:350px; height:350px;">
						<div class="card-header">'.$name.' <b class="float-right" style="color: green">'.$price." ".$currency_simbol.'</b></div>
							<div class="card-body">
								<p class="card-text">'.$small_desc.' <hr></p>
								<center><a href="./product.php?item='.$name.'" type="submit" class="btn btn-outline-primary my-2 my-sm-0">More Info</a></center>
							</div>
				</div>';
			}
		} else {
			echo'
			<div class="card">
				<img class="card-img-top" src="img/MyStore.png">
				<div class="card-header">Placeholder Item <b class="float-right" style="color: green">0 '.$currency_simbol.'</b></div>
				<div class="card-body">
					<p class="card-text">This is the placeholder item, this item is here because no items exist in current category.<hr></p>
						<form class="" action="/start.php">
							<div class="form-group mx-sm-3 mb-2">
							<input type="text" class="form-control" name="username" placeholder="In game name">
							</div>
							<center><button type="submit" class="btn btn-outline-primary my-2 my-sm-0 disabled">Purchase</button></center>
						</form>
				</div>
			</div>';
		}
	} else {
		$result=mysqli_query($link , "SELECT * FROM items WHERE featured=1 ORDER BY id ASC");
		while($post=mysqli_fetch_assoc($result)) {
			$image=$post['imageurl'];
			$name=$post['name'];
			$text=$post['description'];
			$price=$post['price'];
				
			// cut description down into 15 words for the store page
			// and display will description in about page instead.
			$small_desc = implode(' ', array_slice(explode(' ', $text), 0, 15))."..";
			$it = exif_imagetype($image);
				
			echo'
			<div class="card card-custom">
				<img class="card-img-top" src="'.(isset($image) && $image!="" && ($it == 1 || $it == 3 || $it == IMG_JPEG) ? $image : "img/404.jpg").'">
					<div class="card-header">'.$name.' <b class="float-right" style="color: green">'.$price." ".$currency_simbol.'</b></div>
						<div class="card-body">
							<p class="card-text">'.$small_desc.' <hr></p>
							<center><a href="./product.php?item='.$name.'" type="submit" class="btn btn-outline-primary my-2 my-sm-0">More Info</a></center>
						</div>
			</div>';
		}
	}
	
	?>

	</div> 
	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>