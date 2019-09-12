<?php
require('../includes/config.php');
require('includes/login-check.php');

session_start();

$loggedin = false;

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
                <li class="breadcrumb-item active" aria-current="page">Manage <small>></small> Category-List</li>
              </ol>
            </nav>

            <div class="pb-3">
              <h1>Category List</h1>
            </div>

			<?php
			if (isset($_GET['delete']) && isset($_GET['item'])) {
			  $del_item = $_GET['item'];
			  
				$query="DELETE FROM categories WHERE id = ".$del_item; 
				$result=mysqli_query($link , $query);
			  
			  echo '
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>Success!</strong> Category '.$del_item.' has been deleted from the database.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				';
			}

			echo '
            <div class="row">
			  <div class="col-lg-8">
                <div class="card">
                  <div class="card-header">
				  <img src="./img/icons/bookmark.svg"></img>
                    Products
                  </div>
                  <div class="card-body">
				  ';
					
					
			echo '
				<table class="table products-table-top">
					<thead>
					<tr>
					<th scope="col">#</th>
					<th scope="col">Name</th>
					<th scope="col">Manage</th>
					</tr>
				</thead>
				<tbody>
				';
						
			$query="SELECT * FROM categories ORDER BY id ASC";
			$result=mysqli_query($link , $query);
			if(mysqli_num_rows($result) > 0) {
				while($post=mysqli_fetch_assoc($result)) {
					$name=$post['name'];
					$id=$post['id'];
							
					echo'
						<tr>
						<th scope="row">'.$id.'</th>
							<td>'.$name.'</td>
							<td><a href="./category_list.php?delete=a&item='.$id.'" type="button" class="btn btn-danger btn-sm">Delete</a>
							<a href="./edit_category.php?name='.$name.'" type="button" class="btn btn-success btn-sm">Edit</a></td>
						</tr>
					';
				}
			}
				
			echo '	  
					</tbody>
					</table>
                  </div>
                </div>
              </div>
			  <div class="col-lg-4">
                <div class="card">
                  <div class="card-header">
				  <img src="./img/icons/link.svg"></img>
                    Quick links:
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">Jump to:</h4>
                    <p class="card-text">New product: <a href="./new_product.php">link</a></p>
					</br>
                  </div>
                </div>
            </div>
			';
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