<?php
require('../includes/config.php');
include('includes/login-check.php');
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
      <?php include("includes/admin-nav.php"); ?>
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
                <li class="breadcrumb-item active" aria-current="page">Manage <small>></small> Product-New</li>
              </ol>
            </nav>

            <div class="pb-3">
              <h1>Add new products</h1>
            </div>
			
			<?php

			if ((isset($_POST['name']) && isset($_POST["image"]) && isset($_POST["description"]) && isset($_POST["category"]) && isset($_POST["price"]) && isset($_POST["featured"]) && isset($_POST["onetime"]) && isset($_POST["command"]) && isset($_POST["servers"])) && ($_POST['name']!="" && $_POST["image"]!="" && $_POST["description"]!="" && $_POST["category"]!="" && $_POST["price"]!="" && $_POST["featured"]!="" && $_POST["onetime"]!="" && sizeof($_POST["command"])>0 && sizeof($_POST["servers"])>0)) {
			$name=mysqli_real_escape_string($link,$_POST['name']);
			$image=mysqli_real_escape_string($link,$_POST["image"]);
			$desc=mysqli_real_escape_string($link,$_POST['description']);
			$category=mysqli_real_escape_string($link,$_POST['category']);
			$price=mysqli_real_escape_string($link,$_POST['price']);
			$featured=mysqli_real_escape_string($link,$_POST['featured']);
			$onetime=mysqli_real_escape_string($link,$_POST['onetime']);
			$commands=mysqli_real_escape_string($link,json_encode($_POST["command"]));
			$servers=mysqli_real_escape_string($link,json_encode($_POST["servers"]));
			
			$query="SELECT MAX(id) AS ammount FROM items";
			$result=mysqli_query($link , $query);
			$post=mysqli_fetch_assoc($result);
			
			$id = $post['ammount'] + 1;
			
			echo '
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Success!</strong> Product '.$name.' has been added to the database.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			';
			
			$query = "INSERT INTO items (id, name, imageurl, description, category, price, featured, onetime, command, servers) VALUES (".$id.", '".$name."', '".$image."', 
			'".$desc."', ".$category.", '".$price."', ".$featured.", ".$onetime.", '".$commands."', '".$servers."')";
			$result=mysqli_query($link , $query);
			
			} else {
				if(isset($_GET["added"])) {
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

			$catq=mysqli_query($link, "SELECT * FROM categories");

			echo '	
            <div class="row">
			  <div class="col-lg-8">
                <div class="card">
                  <div class="card-header">
				  <img src="./img/icons/bookmark.svg"></img>
                    Create a new product
                  </div>
                  <div class="card-body">
					<div class="container products-background">
					<form action="./new_product.php?added" method="POST">
					<div class="form-group">
						<label>Product Name</label>
						<input name="name" id="name" class="form-control" placeholder="Example Product">
						<small style="color:red;">Please don\'t use <b><></b> or <b>[]</b> in this field.</small>
					</div>
					<div class="form-group">
						<label>Image</label>';
						$images = glob("../img/upload/*.{png,jpg,jpeg,gif}",GLOB_BRACE);
						if(sizeof($images)<1) {
							echo '<br/><label><b>We\'ve come up empty! Upload some images in the image library.</b></label>';
						} else {
							echo '<select class="image-picker show-html" name="image" id="image">
								<option data-img-class="first" value="">';
							foreach($images as $imagenum => $imageu) {
								$imageu = str_replace("../","",$imageu);
								echo '<option data-img-src="../'.$imageu.'" '.($imagenum==sizeof($images)-1 ? 'data-img-class="last"' : "").' data-img-alt="'.basename($imageu).'" value="'.$imageu.'" '.((isset($_POST["image"]) && $_POST["image"]==$imageu) || (isset($image) && $imageu==$image) ? "selected" : "").'>'.basename($imageu).'</option>';
							}
							echo '</select>';
						}
						echo '
						<small class="form-text products-sm-txt">Click to select an image.</small>
						<small class="form-text products-sm-txt">You can upload / delete images in the image library.</small>
						</div>
					<div class="form-group">
						<label>Product description</label>
						<textarea class="form-control" name="description" id="description" placeholder="This product has this and that you get also this and that.." rows="3"></textarea>
						<small class="form-text products-sm-txt">You can use HTML formatting more info here: <a href="https://www.w3schools.com/html/html_formatting.asp">click me</a></small>
					</div>
					<div class="form-group">
		                <label>Category</label>
		                <select name="category" id="category" class="form-control"">';

		                while($catinfo=mysqli_fetch_assoc($catq)) {
			                echo '<option value="'.$catinfo["id"].'">'.$catinfo["name"].'</option>';
			            }

					echo '</select></div>
						<div class="form-group">
						<label>Product Price</label>
						<input name="price" id="price" class="form-control" placeholder="10">
					</div>
					<div class="form-group">
		                <label for="featured">Featured</label>
		                <select name="featured" id="featured" class="form-control">
		                  <option value="1">Yes</option>
		                  <option value="0">No</option>
		                </select>
		            </div>
		            <div class="form-group">
		                <label for="onetime">One time</label>
		                <select name="onetime" id="onetime" class="form-control">
		                  <option value="1">Yes</option>
		                  <option value="0" selected>No</option>
		                </select>
		            </div>
					<div class="form-group">
						<label>Command to run after purchase is complete</label>
						<div id="commands">
							<input name="command[]" id="command" class="form-control" placeholder="pex user %player% group set vip">
						</div><br/>
						<button type="button" class="btn btn-info mb-2" id="cmdbtn">Add Command</button>
						<button type="button" class="btn btn-danger mb-2" id="removecmd">Remove Last Command</button><br/>
						<small style="color:red;">Please don\'t use <b><></b> , <b>[]</b> or <b>|</b> in this field.</small>
						<small class="form-text products-sm-txt">Placeholders: %player% = player</small>
					</div>
					<div class="form-group">
						<label>Servers to run command in (Hold CTRL to select multiple)</label>
						<select name="servers[]" multiple="multiple" id="servers" class="form-control"">
							<option value="*">All Servers</option>';

							$serverq = mysqli_query($link,"SELECT * FROM servers");
			                while($serverinfo=mysqli_fetch_assoc($serverq)) {
				                echo '<option value="'.$serverinfo["id"].'">'.$serverinfo["name"].'</option>';
				            }

					echo '</select></div>
						<button type="submit" class="btn btn-primary">Add product</button>
					</form>
					</br>
					</br>
                  </div>
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
                    <p class="card-text">Product list: <a href="./product_list.php">link</a></p>
					</br>
                  </div>
                </div>
            </div>';
			
			?>
			
          </div>
        </div>
      </div>
    </div>

    <link rel="stylesheet" type="text/css" href="../css/image-picker.css">

    <!-- If you prefer jQuery these are the required scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
    <script src="./dist/js/vendor.js"></script>
    <script src="./dist/js/adminx.js"></script>
    <script src="../js/image-picker.js"></script>

    <!-- If you prefer vanilla JS these are the only required scripts -->
    <!-- script src="./dist/js/vendor.js"></script>
    <script src="./dist/js/adminx.vanilla.js"></script-->

	<script id="script">
		$("#image").imagepicker();
		$("#cmdbtn").click(function(){
			$("#commands").append('<br id="command'+($("input[id^='command']").length+1)+'"/><input name="command[]" id="command'+($("input[id^='command']").length+1)+'" class="form-control" placeholder="Command '+($("input[id^='command']").length+1)+'">');
		});
		$("#removecmd").click(function(){
			console.log("#command"+($("input[id^='command']").length));
			$("br#command"+($("input[id^='command']").length)).remove();
			$("input#command"+($("input[id^='command']").length)).remove();
		});
	</script>
  </body>
</html>