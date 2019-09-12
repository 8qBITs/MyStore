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
                <li class="breadcrumb-item active" aria-current="page">Sales</li>
              </ol>
            </nav>

            <div class="pb-3">
              <h1>Sales Graph</h1>
            </div>

            <div class="row">
			  <div class="col-lg-8">
                <div class="card">
                  <div class="card-header">
				  <img src="./img/icons/bookmark.svg"></img>
                    Donations this week.
                  </div>
                  <div class="card-body">
                    <canvas id="myChart" width="400" height="200"></canvas>

          <?php
            $donations = array();
            $dates = array(date("Y-m-d", strtotime("monday this week")),date("Y-m-d", strtotime("tuesday this week")),date("Y-m-d", strtotime("wednesday this week")),date("Y-m-d", strtotime("thursday this week")),date("Y-m-d", strtotime("friday this week")),date("Y-m-d", strtotime("saturday this week")),date("Y-m-d", strtotime("sunday this week")));
            foreach($dates as $date) {
              $donosq = mysqli_query($link,"SELECT `id` FROM transactions WHERE `date`='".$date."'");
              $donos = ($donosq ? mysqli_num_rows($donosq) : 0);
              array_push($donations, $donos);
            }

            $donationsmonth = array();
            $monthlabels = array();
            $colorsmonth = array();
            $bordercolorsmonth = array();
            $daysinmonth = intval(date("t"));
            for($i = 1; $i<=$daysinmonth; $i++) {
              $donosq = mysqli_query($link,"SELECT `id` FROM transactions WHERE `date`='".date("Y-m-".$i)."'");
              $donos = ($donosq ? mysqli_num_rows($donosq) : 0);
              array_push($donationsmonth,$donos);
              array_push($monthlabels, "'".date("Y-m-".$i)."'");
              $rgbColor = array();
              foreach(array('r', 'g', 'b') as $color){
                $rgbColor[$color] = mt_rand(0, 255);
              }
              array_push($colorsmonth, "'rgba(".implode($rgbColor, ",").", 0.2)'");
              array_push($bordercolorsmonth, "'rgba(".implode($rgbColor, ",").", 1)'");
            }
          ?>
          <script>
					var ctx = document.getElementById("myChart").getContext('2d');
						var myChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],
							datasets: [{
								label: '# of Donations',
								data: [<?php echo implode($donations,","); ?>],
						backgroundColor: [
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)'
								],
								borderColor: [
									'rgba(255,99,132,1)',
									'rgba(54, 162, 235, 1)',
								'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)'
								],
							borderWidth: 1
							}]
							},
						options: {
							scales: {
							yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							}
						}
						});
					</script>
                  </div>
                  </div><br/>
                  <div class="card">
                    <div class="card-header">
                      <img src="./img/icons/bookmark.svg"></img>
                      Donations this month.
                    </div>
                  <div class="card-body">
                    <canvas id="myChart1" width="400" height="200"></canvas>
                  </div>
                  <script type="text/javascript">
                    var ctx1 = document.getElementById("myChart1").getContext('2d');
                    var myChart1 = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                      labels: [<?php echo implode($monthlabels,",") ?>],
                      datasets: [{
                        label: '# of Donations',
                        data: [<?php echo implode($donationsmonth,","); ?>],
                    backgroundColor: [<?php echo implode($colorsmonth,","); ?>],
                        borderColor: [<?php echo implode($bordercolorsmonth,","); ?>],
                      borderWidth: 1
                      }]
                      },
                    options: {
                      scales: {
                      yAxes: [{
                          ticks: {
                            beginAtZero:true
                          }
                        }]
                      }
                    }
                    });
                  </script>
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