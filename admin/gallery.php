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
                <li class="breadcrumb-item active" aria-current="page">Manage <small>></small> Image-Gallery</li>
              </ol>
            </nav>

            <div class="pb-3">
              <h1>Image Gallery</h1>
            </div>

			<?php
				if(isset($_FILES["upload"]) && getimagesize($_FILES["upload"]["tmp_name"])) {
					if($_FILES["upload"]["size"]>250000000) {
						echo '<div class="alert alert-danger" role="alert">
							  File size is over 250mb!
							</div>';
					} else {
						if(!file_exists("img/upload/".basename($_FILES["upload"]["name"]))) {
							//success
							if (move_uploaded_file($_FILES["upload"]["tmp_name"], "../img/upload/".basename($_FILES["upload"]["name"]))) {
						        echo '<div class="alert alert-success" role="alert">
									  File sucessfuly uploaded!
									</div>';
						    } else {
						        echo '<div class="alert alert-danger" role="alert">
									  There was an error uploading the file!
									</div>';
						    }
						} else {
							echo '<div class="alert alert-danger" role="alert">
								  File with this name already exists!
								</div>';
						}
					}
				} elseif(isset($_FILES["upload"]) && !getimagesize($_FILES["upload"]["tmp_name"])) {
					echo '<div class="alert alert-danger" role="alert">
						  This file is not an image!
						</div>';
				}
				$images = glob("../img/upload/*.{png,jpg,jpeg,gif}",GLOB_BRACE);
				if(isset($_GET["delete"]) && $_GET["delete"]!="") {
					foreach($images as $image)
					{
						if(isset($_GET["delete"]) && $image==$_GET["delete"]) {
							unlink($image);
							print_r(error_get_last());
							echo '<script>window.location.href = "gallery.php";</script>';
						}
					}
				}

				echo '<form action="gallery.php" method="post" enctype="multipart/form-data">
				<div class="input-group">
				  <div class="input-group-prepend">
				    <input class="input-group-text" value="Upload Image" type="submit"></input>
				  </div>
				  <div class="custom-file">
				    <input type="file" class="custom-file-input" name="upload" id="upload" accept="image/png, image/jpeg, image/gif" aria-describedby="inputGroupFileAddon01">
				    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
				  </div>
				</div><br/></form>';

				if(sizeof($images)<1) {
					echo 'No images have been uploaded yet!';
				} else {
					echo '<div class="card-columns">';
					foreach($images as $image)
					{
						if(isset($_GET["delete"]) && $image==$_GET["delete"]) {
							unlink($image);
							unset($_GET["delete"]);
							continue;
						}
						echo '<div class="card card-custom" style="height:auto;width:250px;">
							<img class="card-img-top" src="'.$image.'">
							<div class="card-body">
								<center>'.basename($image).'</center>
							</div>
							<div class="card-footer">
								<center><a href="?delete='.$image.'" type="submit" class="btn btn-outline-primary my-2 my-sm-0">Delete</a></center>
							</div>
						</div>';
					}
					echo '</div>';
				}
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