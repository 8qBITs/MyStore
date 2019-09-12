<?php
require('../includes/config.php');
require('includes/login-check.php');

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
                <li class="breadcrumb-item active" aria-current="page">Ban-List</li>
              </ol>
            </nav>

            <div class="pb-3">
              <h1>Ban List</h1>
            </div>

			<?php
			if ($_GET['unban'] && isset($_GET['user'])) {
			  $banned_user = $_GET['user'];
			  
				mysqli_query($link,"DELETE FROM banned_users WHERE user = '".mysqli_real_escape_string($link,$banned_user)."'");
			  
			  echo '
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<strong>Warning!</strong> You unbanned '.$banned_user.' from the store.
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
				  <img src="./img/icons/bookmark.svg"></img>
                    Banned users
                  </div>
                  <div class="card-body">
            <?php
					
					echo '
						<table class="table products-table-top">
							<thead>
							<tr>
							<th scope="col">#</th>
							<th scope="col">Username</th>
							<th scope="col">Date</th>
							<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
						';
						
						$query="SELECT * FROM banned_users ORDER BY id ASC";
						$result=mysqli_query($link , $query);
						if(mysqli_num_rows($result) > 0) {
							while($post=mysqli_fetch_assoc($result)) {
										$id=$post['id'];
										$user=$post['user'];
										$date=$post['date'];
						echo'
							<tr>
							<th scope="row">'.$id.'</th>
								<td>'.$user.'</td>
								<td>'.$date.'</td>
								<td><a href="./ban_list.php?unban=true&user='.$user.'" type="button" class="btn btn-success btn-sm">Unban</a>
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