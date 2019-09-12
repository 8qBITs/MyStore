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
						if ((isset($_POST['name']) && isset($_POST["image"]) && isset($_POST["desc"]) && isset($_POST["category"]) && isset($_POST["price"]) && isset($_POST["featured"]) && isset($_POST["onetime"]) && isset($_POST["command"]) && isset($_POST["servers"])) && ($_POST['name']!="" && $_POST["image"]!="" && $_POST["desc"]!="" && $_POST["category"]!="" && $_POST["price"]!="" && $_POST["featured"]!="" && $_POST["onetime"]!="" && $_POST["command"]!="" && sizeof($_POST["servers"])>0)) {
						$edit = $_GET['name'];
						
						$query="SELECT * FROM items WHERE name = '".mysqli_real_escape_string($link, $edit)."'";
						$result=mysqli_query($link , $query);
						$post=mysqli_fetch_assoc($result);

						$id=mysqli_real_escape_string($link,$post['id']);
						$name=mysqli_real_escape_string($link,$_POST['name']);
						$image=mysqli_real_escape_string($link,$_POST["image"]);
						$desc=mysqli_real_escape_string($link,$_POST['desc']);
						$category=mysqli_real_escape_string($link,$_POST['category']);
						$price=mysqli_real_escape_string($link,$_POST['price']);
						$featured=mysqli_real_escape_string($link,$_POST['featured']);
						$onetime=mysqli_real_escape_string($link,$_POST['onetime']);
						$command=mysqli_real_escape_string($link,json_encode($_POST['command']));
						$servers=mysqli_real_escape_string($link,json_encode($_POST["servers"]));
						
						echo '
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<strong>Success!</strong> Product '.$name.' has been edited.
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						';
						
						$query = "UPDATE items SET name = '".$name."', imageurl = '".$image."', description = '".$desc."', category = '".$category."', price = '".$price."', featured = '".$featured."', onetime = '".$onetime."', command = '".$command."', servers = '".$servers."' WHERE id = '".$id."'";
						$result=mysqli_query($link , $query);
						
						} else {
							if(isset($_POST["edit"]) && ($_GET["name"]!="" || isset($_GET["name"]))) {
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
						$onetime=$post['onetime'];
						$commands=json_decode($post['command']);
						$serverssql=json_decode($post['servers']);
						
						echo '
						<div class="container products-background">
						<label><h1>Edit: '.$name.'</h1></label>
						</br>
						<form action="./edit_product.php?name='.$_GET["name"].'" method="POST">
						<input type="hidden" name="edit" value="true">
						<div class="form-group">
							<label>Product Name</label>
							<input name="name" id="name" class="form-control" value="'.$name.'">
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
									echo '<option data-img-src="../'.$imageu.'" '.($imagenum==sizeof($images)-1 ? 'data-img-class="last"' : "").' data-img-alt="'.basename($imageu).'" value="'.$imageu.'" '.((isset($_POST["image"]) && $_POST["image"]==$imageu) || ($imageu==$image) ? "selected" : "").'>'.basename($imageu).'</option>';
								}
								echo '</select>';
							}
							echo '
							<small class="form-text products-sm-txt">You can upload / delete images in the image library.</small>
						</div>
						<div class="form-group">
							<label>Product description</label>
							<textarea class="form-control" name="desc" id="description" rows="3">'.$desc.'</textarea>
							<small class="form-text products-sm-txt">You can use HTML formatting more info <a href="https://www.w3schools.com/html/html_formatting.asp">here</a></small>
						</div>
						  <div class="form-group">
							<label>Category</label>
							<select name="category" id="category" class="form-control">
							  ';
						  while($catinfo=mysqli_fetch_assoc($catq)) {
							echo '<option value="'.$catinfo["id"].'" '.($category==$catinfo["id"] ? "selected" : "").'>'.$catinfo["name"].'</option>';
						  }
						  echo '
							</select>
						  </div>
						  <div class="form-group">
							<label>Product Price</label>
							<input name="price" id="price" class="form-control" value="'.$price.'">
						  </div>
						  <div class="form-group">
							<label for="featured">Featured</label>
							<select name="featured" id="featured" class="form-control">
							  <option value="1" '.($featured ? 'selected' : "").'>Yes</option>
							  <option value="0" '.($featured ? "" : 'selected').'>No</option>
							</select>
						  </div>
						  <div class="form-group">
		                <label for="onetime">One time</label>
			                <select name="onetime" id="onetime" class="form-control">
			                  <option value="1"'.($onetime ? 'selected' : "").'>Yes</option>
			                  <option value="0"'.($onetime ? "" : 'selected').'>No</option>
			                </select>
			            </div>
						<div class="form-group">
							<label>Command to run after purchase is complete</label>
							<div id="commands">';
								foreach($commands as $key => $command) {
									echo ($key!=0 ? '<br id="command'.($key+1).'"/>' : "").'<input name="command[]" id="command'.($key==0 ? "" : $key+1).'" class="form-control" placeholder="Command '.($key+1).'" value="'.$command.'">';
								}
							echo '</div><br/>
							<button type="button" class="btn btn-info mb-2" id="cmdbtn">Add Command</button>
							<button type="button" class="btn btn-danger mb-2" id="removecmd">Remove Last Command</button><br/>
							<small style="color:red;">Please don\'t use <b><></b> , <b>[]</b> or <b>|</b> in this field.</small>
							<small class="form-text products-sm-txt">Placeholders: %player% = player</small>
						</div>
						<div class="form-group">
						<label>Servers to run command in</label>
						<select name="servers[]" multiple="multiple" id="servers" class="form-control">
							<option value="*" '.(in_array("*",$serverssql) ? "selected" : "").'>All Servers</option>';

							$serverq = mysqli_query($link,"SELECT * FROM servers");
			                while($serverinfo=mysqli_fetch_assoc($serverq)) {
				                echo '<option value="'.$serverinfo["id"].'" '.(in_array($serverinfo["id"],$serverssql) ? "selected" : "").'>'.$serverinfo["name"].'</option>';
				            }

						echo '</select></div>
						<button type="submit" class="btn btn-success">Apply</button>
						<a href="./product_list.php" class="btn btn-danger">Go back</a>
						</form>
						</br>
						</br>
						';
					?>
                  </div>
                </div>
              </div>
            </div>
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

    	<script>
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