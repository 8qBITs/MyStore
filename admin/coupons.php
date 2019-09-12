<?php
require('../includes/config.php');
require('includes/login-check.php');

mysqli_query($link,"DELETE FROM coupons WHERE uses=0");

?>	
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo $navbar_title." - Donation Store"; ?></title>
	<link rel="icon" href="../<?php echo $site_favicon; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<script src="../js/Chart.js"></script>
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
		<?php loginbtn() ?>
      </nav>

      <!-- expand-hover push -->
      <!-- Sidebar -->
      <?php include('includes/admin-nav.php'); ?>
      <!-- Sidebar End -->

      <!-- adminx-content-aside -->
      <div class="adminx-content">
        <!-- <div class="adminx-aside">

        </div> -->

        <div class="adminx-main-content">
          <div class="container-fluid">
            <!-- BreadCrumb -->
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb adminx-page-breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Coupons</li>
              </ol>
            </nav>

            <div class="pb-3">
              <h1>Coupon List</h1>
            </div>

			<?php
			
				if(isset($_GET["remove"]) && isset($_GET["id"])) {
					$remove = $_GET['id'];
					
					$query="DELETE FROM coupons WHERE id = ".mysqli_real_escape_string($link,$remove); 
					$result=mysqli_query($link , $query);
					
					echo '
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<strong>Warning!</strong> You removed coupon '.$remove.' from the database.
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					';
				}
				if(isset($_GET["add"]) && isset($_POST["code"]) && isset($_POST["discount"])) {
					$code = $_POST["code"];
					$discount = $_POST["discount"];
					$uses = (isset($_POST["uses"]) && $_POST["uses"]!="" ? $_POST["uses"] : null);
					$query = (isset($uses) ? "INSERT INTO `coupons`(`code`, `discount`, `uses`) VALUES ('".mysqli_real_escape_string($link,$code)."','".mysqli_real_escape_string($link,$discount)."',".mysqli_real_escape_string($link,intval($uses)).")" : "INSERT INTO `coupons`(`code`, `discount`) VALUES ('".mysqli_real_escape_string($link,$code)."','".mysqli_real_escape_string($link,$discount)."')");
					mysqli_query($link , $query);
					echo '
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong>Success!</strong> You added coupon '.$code.' to the database.
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					';
				}
				echo '<div class="row">
			  <div class="col-lg-8">
                <div class="card">
                  <div class="card-header">
                  <div class="card-body">
					<form class="form-inline" action="./coupons.php?add=true" method="post">
						<div class="form-group mb-2">
							<div class="row">
							<div class="col">
							  <input type="text" class="form-control" name="code" placeholder="Code">
							</div>
							<div class="col">
							  <input type="text" class="form-control" name="discount" placeholder="10% or 10">
							</div>
							<div class="col">
							  <input type="text" class="form-control" name="uses" placeholder="Max uses">
							</div>
							<div class="col">
							  <button type="submit" class="btn btn-success mb-2">Add Coupon</button>
							</div>				
						  </div>
					  	</div>
					</form></br>
				  
					<table class="table products-table-top">
						<thead>
						<tr>
						<th scope="col">#</th>
						<th scope="col">Code</th>
						<th scope="col">Discount</th>
						<th scope="col">Uses left</th>
						<th scope="col">Manage</th>
						</tr>
					</thead>
					<tbody>
					';
					$result=mysqli_query($link , "SELECT * FROM coupons ORDER BY id ASC");
					if(mysqli_num_rows($result) > 0) {
						while($post=mysqli_fetch_assoc($result)) {
							$id=$post['id'];
							$code=$post['code'];
							$discount=(strpos($post['discount'],"%") ? $post['discount'] : '$'.$post['discount']);
							$uses=(isset($post['uses']) ? $post['uses'] : "∞");
							echo'
								<tr>
								<th scope="row">'.$id.'</th>
									<td>'.$code.'</td>
									<td>'.$discount.'</td>
									<td>'.$uses.'</td>
									<td><a href="./coupons.php?remove=true&id='.$id.'" type="button" class="btn btn-danger btn-sm">Remove</a>
								</tr>
							';
						}
					}
					echo '</tbody>
				  </div>
				  </div>
				</div>
			  </div>';
			?>
			
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