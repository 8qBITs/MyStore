<?php
require('../includes/config.php');
require('includes/login-check.php');

session_start();

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
                <li class="breadcrumb-item active" aria-current="page">Manage <small>></small> Product-List</li>
              </ol>
            </nav>

            <div class="pb-3">
              <h1>Product List</h1>
            </div>

            <div class="row">
			  <div class="col-lg-8">
                <div class="card">
                  <div class="card-header">
				  <img src="./img/icons/bookmark.svg"></img>
                    Products
                  </div>
                  <div class="card-body">
                	<?php
						if (isset($_POST['name']) && $_POST['name']!="") {
						$edit = $_GET['name'];
						
						$query="SELECT * FROM categories WHERE name = '".mysqli_real_escape_string($link, $edit)."'";
						$result=mysqli_query($link , $query);
						$post=mysqli_fetch_assoc($result);

						$id=mysqli_real_escape_string($link,$post['id']);
						$name=mysqli_real_escape_string($link,$_POST['name']);
						
						echo '
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<strong>Success!</strong> Category '.$name.' has been edited.
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						';
						
						$query = "UPDATE categories SET name = '".$name."' WHERE id = '".$id."'";
						$result=mysqli_query($link , $query);
						
						} else {
							if($_GET["name"]=="" || !isset($_GET["name"])) {
								echo '
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<strong>Error!</strong> No field can be left empty!
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								';
							}
						}
						$name = $_GET['name'];
							
						$query="SELECT * FROM items WHERE name = '".mysqli_real_escape_string($link, $name)."'";
						$result=mysqli_query($link , $query);
						$catq=mysqli_query($link, "SELECT * FROM categories");
						$post=mysqli_fetch_assoc($result);

						$id=$post['id'];
						$image=$post['imageurl'];
						$desc=$post['description'];
						$category=$post['category'];
						$price=$post['price'];
						$featured=$post['featured'];
						$command=$post['command'];
						
						echo '
						<div class="container products-background">
						<label><h1>Edit: '.$name.'</h1></label>
						</br>
						<form action="./edit_category.php?name='.$_GET["name"].'" method="POST">
						<input type="hidden" name="edit" value="true">
						<div class="form-group">
							<label>Category Name</label>
							<input name="name" id="name" class="form-control" value="'.$name.'">
						</div>
						<button type="submit" class="btn btn-success">Apply</button>
						<a href="./category_list.php" class="btn btn-danger">Go back</a>
						</form>';
						
					?>
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
    <script src="./js/image-picker.js"></script>

    <!-- If you prefer vanilla JS these are the only required scripts -->
    <!-- script src="./dist/js/vendor.js"></script>
    <script src="./dist/js/adminx.vanilla.js"></script-->

    	<script>
			$("#image").imagepicker();
		</script>
  </body>
</html>