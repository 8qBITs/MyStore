<?php
require('../includes/config.php');
require('includes/login-check.php');
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
				<li class="breadcrumb-item active" aria-current="page">Manage <small>></small> Server-List</li>
			  </ol>
			</nav>

			<div class="pb-3">
			  <h1>Server List</h1>
			</div>

			<?php

				if(isset($_POST["name"]) && isset($_POST["ip"]) && isset($_POST["pass"]) && !($_POST["name"]=="" || $_POST["ip"]=="" || $_POST["pass"]=="") && isset($_GET["add"])) {
					mysqli_query($link,"INSERT INTO `servers`(`name`, `ip`, `pass`) VALUES ('".mysqli_real_escape_string($link,$_POST["name"])."','".mysqli_real_escape_string($link,$_POST["ip"])."','".mysqli_real_escape_string($link,$_POST["pass"])."')");

					echo '
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong>Success!</strong> Server '.$_POST["name"].' has been added.
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					';
				} elseif(($_POST["name"]=="" || $_POST["ip"]=="" || $_POST["pass"]=="") && isset($_GET["add"])) {
					echo '
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Error!</strong> No fields can be left empty!
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					';
				}

				if(isset($_GET["id"]) && isset($_GET["remove"])) {
					mysqli_query($link,"DELETE FROM `servers` WHERE id=".mysqli_real_escape_string($link,$_GET["id"]));

					echo '
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong>Success!</strong> Server ID '.$_GET["id"].' has been removed.
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					';
				}

			?>

			<div class="row">
			  <div class="col-lg-8">
				<div class="card">
				  <div class="card-header">
				  <div class="card-body">	

					<form class="form-inline" action="./server_list.php?add=true" method="post">
						<div class="form-group mb-2">
							<div class="row">
							<div class="col">
							  <input type="text" class="form-control" name="name" maxlength="255" placeholder="Server name">
							</div>
							<div class="col">
							  <input type="text" class="form-control" name="ip" maxlength="255" placeholder="127.0.0.1:6666">
							</div>
							<div class="col">
							  <input type="text" class="form-control" name="pass" maxlength="255" placeholder="Socket Password">
							</div>
							<div class="col">
							  <button type="submit" class="btn btn-success mb-2">Add Server</button>
							</div>				
						  </div>
					  	</div>
					</form></br>
				  
					<table class="table products-table-top">
						<thead>
						<tr>
						<th scope="col">#</th>
						<th scope="col">Name</th>
						<th scope="col">IP</th>
						<th scope="col">Password</th>
						<th scope="col">Manage</th>
						</tr>
					</thead>
					<tbody>
					
					<?php
						$result=mysqli_query($link , "SELECT * FROM servers");
						if(mysqli_num_rows($result) > 0) {
							while($post=mysqli_fetch_assoc($result)) {
								$id=$post['id'];
								$name=$post['name'];
								$ip=$post['ip'];
								$pass=$post['pass'];
								echo'<tr>
									<th scope="row">'.$id.'</th>
										<td>'.$name.'</td>
										<td>'.$ip.'</td>
										<td>'.$pass.'</td>
										<td><a href="./server_list.php?remove=true&id='.$id.'" type="button" class="btn btn-danger btn-sm">Remove</a>
									</tr>
								';
							}
						}
					?>
					</tbody>
					</table>
				  </div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>

	<link rel="stylesheet" type="text/css" href="./css/image-picker.css">

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