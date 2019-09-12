<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../includes/config.php');
require('includes/login-check.php');
require('includes/dbtemplate.php');

$dbinfo = mysqli_query($link,"SELECT * FROM database_info");
$curr_db_ver = ($dbinfo ? $dbinfo->fetch_assoc()["version"] : null);

?>	
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo $navbar_title." - Donation Store"; ?></title>
	<link rel="icon" href="../<?php echo $site_favicon; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<script src="../js/Chart.js"></script>
    <link rel="stylesheet" type="text/css" href="css/adminx.css" media="screen" />

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
                <li class="breadcrumb-item"><b>System Script</b></li>
                <li class="breadcrumb-item active" aria-current="page">Database Update</li>
              </ol>
            </nav>

            <div class="pb-3">
              <h1><b>Database Update</b> <small><?php echo (isset($curr_db_ver) ? $curr_db_ver." -> ".$db_version : "unknown -> ".$db_version); ?></small></h1>
            </div>

            <?php
              if(isset($_GET["info"])) {
                switch ($_GET["info"]) {
                  case 'needed':
                    echo '<div class="alert alert-danger" role="alert">
                      Database update needed. Most core features won\'t work without a database update. (All data will be imported)
                    </div>';
                    break;
                }
              }

              function isJson($string) {
               json_decode($string);
               return (json_last_error() == JSON_ERROR_NONE);
              }

              if(isset($_POST["confirm"])) {
                if(!isset($curr_db_ver) || $db_version>$curr_db_ver) {
                  echo '<h4>Executing...</h4>';

                  $tables = mysqli_query($link,"SHOW TABLES FROM ".$sql_database);
                  $tablecount = mysqli_num_rows($tables);
                  $templatetablecount = sizeof($dbtemplate);

                  echo 'DB table count: '.$tablecount.' | Template DB table count: '.$templatetablecount."<br/><br/>";

                  $remaketables = array();
                  $currtables = array();

                  echo '<span id="log"><div class="container-fluid"><div class="row">';
                  $i=0;
                  while($table = mysqli_fetch_row($tables)) {
                    $table = $table[0];
                    if(!in_array($table, $dbtemplate_table_names)) {
                      continue;
                    }
                    $i++;
                    array_push($currtables, $table);
                    $ttable = $dbtemplate[$table];
                    $columns = mysqli_query($link,"DESCRIBE ".$table);
                    $columnscount = mysqli_num_rows($columns);
                    $templatecolumnscount = sizeof($ttable);
                    echo ($i%5==0 ? '</div><br/><div class="row">' : '');
                    echo '<div class="col-3"><div><div class="card"><div class="card-head"><center><b>';
                    if($templatecolumnscount!=$columnscount) {
                      echo 'Table: '.$table.' &#10060;';
                      if(!in_array($table, $remaketables))
                        array_push($remaketables, $table);
                    } else {
                      echo 'Table: '.$table;
                    }
                    echo '</b></center></div><div class="card-body"><br/>';
                    while($column = mysqli_fetch_row($columns)) {
                      echo 'Column: '.$column[0];
                      if(isset($ttable[$column[0]]) && $column==$ttable[$column[0]]) {
                        echo " &#9989;<br/>";
                      } else {
                        echo " &#10060;<br/>";
                        if(!in_array($table, $remaketables))
                          array_push($remaketables, $table);
                      }
                    }
                    echo ($templatecolumnscount!=$columnscount ? "<small>One or more columns missing...</small><br/>" : "").'</div></div></div></div><br/><br/>';

                  }
                  echo '</div></div></span><br/>';
                  $addtables = array_diff($dbtemplate_table_names, $currtables);

                  if(sizeof($remaketables)>0) {
                    echo '<h2>Remaking '.sizeof($remaketables).' table'.(sizeof($remaketables)==1 ? "" : "s").'</h2><br/>';
                  } else {
                    echo '<h2>Not remaking any tables</h2><br/>';
                  }

                  if(sizeof($addtables)>0) {
                    echo '<h2>Adding '.sizeof($addtables).' table'.(sizeof($addtables)==1 ? "" : "s").'</h2>';
                  } else {
                    echo '<h2>Not adding any tables</h2>';
                  }

                  echo '<br/><span id="debug">DEBUG INFO:<br/><small>';

                  foreach ($remaketables as $table) {
                    if(!in_array($table, $dbtemplate_table_names)) {
                      continue;
                    }
                    mysqli_query($link,"RENAME TABLE `".$table."` TO `".$table."1`"); // rename old table
                    // [[create new table
                    $tableinfo = $dbtemplate[$table];
                    $query = "CREATE TABLE `".$sql_database."`.`".$table."` (";
                    $primary = null;
                    $unique = null;
                    foreach($tableinfo as $colname => $colinfo) {
                      $query.="`".$colname."` ".$colinfo[1]." ".($colinfo[2]=="NO" ? "NOT " : "")."NULL".($colinfo[4]=="" ? "" : " DEFAULT '".$colinfo[4]."'").($colinfo[5]=="auto_increment" ? " AUTO_INCREMENT" : "")." ,";
                      if($colinfo[3]=="PRI") {
                        $primary = $colname;
                      } elseif($colinfo[3]=="UNI") {
                        $unique = $colname;
                      }
                    }
                    $query .= (isset($primary) ? " PRIMARY KEY (`".$primary."`)," : "");
                    $query .= (isset($unique) ? " UNIQUE (`".$unique."`)," : "");
                    $query = rtrim($query,",").") ENGINE = InnoDB;";
                    mysqli_query($link,$query);
                    echo $query."<br/>";
                    if($table=="database_info") {
                      mysqli_query($link,"INSERT INTO database_info(`version`) VALUES(".$db_version.")");
                      mysqli_query($link,"DROP TABLE `database_info1`");
                      continue;
                    }
                    //]] [[(try to) insert old data
                    $resq = mysqli_query($link,"SELECT * FROM `".$table."1`");
                    while($row = mysqli_fetch_assoc($resq)) {
                      $query = "INSERT INTO `".$table."`";
                      $keys = array();
                      $values = array();
                      foreach($row as $col => $value) {
                        array_push($keys,mysqli_real_escape_string($link,$col));
                        if(isset($dbtemplate[$table][$col]) && in_array($table.".".$col, $dbtemplate_json_columns)) {
                          array_push($values, (isJson($value) ? $value : "[".json_encode($value)."]"));
                        } else {
                          array_push($values, mysqli_real_escape_string($link,$value));
                        }
                      }
                      $query.="(";
                      foreach($keys as $key) {
                        $query.="`".$key."`,";
                      }
                      $query = rtrim($query,",").")";
                      $query.=" VALUES (";
                      foreach($values as $val) {
                        $query.="'".$val."',";
                      }
                      $query = rtrim($query,",").");";
                      mysqli_query($link,$query)."<br/>";
                      echo $query."<br/>";
                    }
                    //]]
                    mysqli_query($link,"DROP TABLE `".$table."1`"); // delete old table
                  }

                  foreach($addtables as $table) {
                    $tableinfo = $dbtemplate[$table];
                    $query = "CREATE TABLE `".$sql_database."`.`".$table."` (";
                    $primary = null;
                    $unique = null;
                    foreach($tableinfo as $colname => $colinfo) {
                      $query.="`".$colname."` ".$colinfo[1]." ".($colinfo[2]=="NO" ? "NOT " : "")."NULL".($colinfo[4]=="" ? "" : " DEFAULT '".$colinfo[4]."'").($colinfo[5]=="auto_increment" ? " AUTO_INCREMENT" : "")." ,";
                      if($colinfo[3]=="PRI") {
                        $primary = $colname;
                      } elseif($colinfo[3]=="UNI") {
                        $unique = $colname;
                      }
                    }
                    $query .= (isset($primary) ? " PRIMARY KEY (`".$primary."`)," : "");
                    $query .= (isset($unique) ? " UNIQUE (`".$unique."`)," : "");
                    $query = rtrim($query,",").") ENGINE = InnoDB;";
                    mysqli_query($link,$query)."<br/>";
                    if($table=="database_info") {
                      mysqli_query($link,"INSERT INTO database_info(`version`) VALUES(".$db_version.")");
                    }
                  }

                  echo '</small></span>';

                  mysqli_query($link,"UPDATE `database_info` SET `version`=".$db_version);
                } else {
                  echo '<h3>Your database is already up to date.</h3>';
                }
              } else {
            ?>

            <h4>Are you sure you want to update your database to version <?=$db_version?>?</h4>
            <form action="./dbupdate.php" method="post" class="form-inline">
              <input type="hidden" name="confirm" value="true">
              <div class="row">
              <div class="col">
                <button type="submit" class="btn btn-success">Yes</button>
              </div><br/>
              <div class="col">
                <a href="./index.php"><button type="button" class="btn btn-danger" <?=(isset($_GET["info"]) && $_GET["info"]=="needed" ? "disabled" : "")?> >No</button></a>
              </div>
              </div>
            </form>

            <?php } ?>

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
    </div>
  </body>
</html>