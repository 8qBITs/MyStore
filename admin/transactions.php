<?php
require('../includes/config.php');
require('includes/login-check.php');
if(!isset($_GET["page"])) {
	echo '<script>window.location.href = "?page=0";</script>';
}

$loggedin = false;

$transactions_per_page = 20;

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
                <li class="breadcrumb-item active" aria-current="page">Transaction-Log</li>
              </ol>
            </nav>

            <div class="pb-3">
              <h1>Transaction Log</h1>
            </div>

			<?php
			if (isset($_GET['ban']) && isset($_GET['user'])) {
			  $ban_user = $_GET['user'];
				if(!mysqli_query($link , "INSERT INTO banned_users (user, date) VALUES ('".mysqli_real_escape_string($link,$ban_user)."', '".date("Y-m-d")."')")) {
					echo '
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Warning!</strong> Error banning user '.$ban_user.'. Maybe user is already banned?
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					';
				} else {
					echo '
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<strong>Warning!</strong> You banned '.$ban_user.' from the store.
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					';
				}
			} else if (isset($_GET['remove']) && isset($_GET['id'])) {
				
				$remove = $_GET['id'];
				
				$query="DELETE FROM transactions WHERE id = ".mysqli_real_escape_string($link,$remove); 
				$result=mysqli_query($link , $query);
				
			echo '
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<strong>Warning!</strong> You removed transaction '.$remove.' from the database.
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
                    Latest Transactions
                  </div>
                  <div class="card-body">				
					
						<table class="table products-table-top">
							<thead>
							<tr>
							<th scope="col">#</th>
							<th scope="col">Item</th>
							<th scope="col">Username</th>
							<th scope="col">Method</th>
							<th scope="col">Date</th>
							<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
						';
						
			echo '<nav aria-label="Pages">
			  <ul class="pagination">
			    <li class="page-item"><a class="page-link" href="?page='.($_GET["page"]>0 ? $_GET["page"]-1 : 0).'">Previous</a></li>
			    <li class="page-item"><a class="page-link" href="?page=0">1</a></li>';
			
			$count = mysqli_query($link, "SELECT COUNT(*) FROM transactions;")->fetch_assoc();
			$totalpages = 0;

			for ($pagenum=1; $pagenum < floor($count["COUNT(*)"]/$transactions_per_page)+($count["COUNT(*)"]%$transactions_per_page>0 ? 1 : 0); $pagenum++) { 
				echo '<li class="page-item"><a class="page-link" href="?page='.$pagenum.'">'.($pagenum+1).'</a></li>';
				$totalpages = $pagenum;
			}

			echo '<li class="page-item"><a class="page-link" href="?page='.($totalpages>$_GET["page"] ? $_GET["page"]+1 : $_GET["page"]) .'">Next</a></li>
			  </ul>
			</nav>';

			$result=mysqli_query($link , "SELECT * FROM transactions ORDER BY id DESC LIMIT ".$transactions_per_page." OFFSET ".mysqli_real_escape_string($link,$_GET["page"]*$transactions_per_page));
			if(mysqli_num_rows($result) > 0) {
				while($post=mysqli_fetch_assoc($result)) {
					$id=$post['id'];
					$item=$post['item'];
					$user=$post['user'];
					$method=$post['method'];
					$date=$post['date'];
					echo'
					<tr>
					<th scope="row">'.$id.'</th>
						<td>'.$item.'</td>
						<td>'.$user.'</td>
						<td>'.$method.'</td>
						<td>'.$date.'</td>
						<td><a href="./transactions.php?page='.$_GET["page"].'&ban=true&user='.$user.'" type="button" class="btn btn-warning btn-sm">Ban user</a> <a href="./transactions.php?remove=true&id='.$id.'" type="button" class="btn btn-danger btn-sm">Remove</a>
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