<?php

$file = basename($_SERVER["SCRIPT_NAME"]);
echo "<script>console.log('Navbar called from: ".$file."');</script>";
echo '<div class="adminx-sidebar expand-hover push">
        <ul class="sidebar-nav">
          <li class="sidebar-nav-item">
            <a href="./index.php" class="sidebar-nav-link '.($file=="index.php" ? "active" : "").'">
              <span class="sidebar-nav-icon">
                <img src="./img/icons/home.svg"></i>
              </span>
              <span class="sidebar-nav-name">
                Dashboard
              </span>
              <span class="sidebar-nav-end">

              </span>
            </a>
          </li>

          <li class="sidebar-nav-item">
            <a class="sidebar-nav-link '.(count(array_intersect(explode(' ', $file),array('product_list.php','category_list.php','edit_product.php','new_product.php','edit_category.php','new_category.php','gallery.php','server_list.php','email_templates.php'))) ? "active" : "").'" data-toggle="collapse" href="#products" aria-expanded="false" aria-controls="example">
              <span class="sidebar-nav-icon">
                <img src="./img/icons/settings.svg"></i>
              </span>
              <span class="sidebar-nav-name">
                Manage
              </span>
              <span class="sidebar-nav-end">
              </span>
            </a>
			
			     <ul class="sidebar-sub-nav collapse" id="products">
              <li class="sidebar-nav-item">
                <a href="./product_list.php" class="sidebar-nav-link '.($file=="product_list.php" ? "active" : "").'">
                  <span class="sidebar-nav-abbr">
                    Pr
                  </span>
                  <span class="sidebar-nav-name">
                    Product List
                  </span>
                </a>
              </li>

              <li class="sidebar-nav-item">
                <a href="./new_product.php" class="sidebar-nav-link '.($file=="new_product.php" ? "active" : "").'">
                  <span class="sidebar-nav-abbr">
                    Ne
                  </span>
                  <span class="sidebar-nav-name">
                    New Product
                  </span>
                </a>
              </li>

              <li class="sidebar-nav-item">
                <a href="./category_list.php" class="sidebar-nav-link '.($file=="category_list.php" ? "active" : "").'">
                  <span class="sidebar-nav-abbr">
                    Ca
                  </span>
                  <span class="sidebar-nav-name">
                    Category List
                  </span>
                </a>
              </li>

              <li class="sidebar-nav-item">
                <a href="./new_category.php" class="sidebar-nav-link '.($file=="new_category.php" ? "active" : "").'">
                  <span class="sidebar-nav-abbr">
                    Ne
                  </span>
                  <span class="sidebar-nav-name">
                    New Category
                  </span>
                </a>
              </li>

              <li class="sidebar-nav-item">
                <a href="./server_list.php" class="sidebar-nav-link '.($file=="server_list.php" ? "active" : "").'">
                  <span class="sidebar-nav-abbr">
                    Se
                  </span>
                  <span class="sidebar-nav-name">
                    Server List
                  </span>
                </a>
              </li>

              <li class="sidebar-nav-item">
                <a href="./gallery.php" class="sidebar-nav-link '.($file=="gallery.php" ? "active" : "").'">
                  <span class="sidebar-nav-abbr">
                    Im
                  </span>
                  <span class="sidebar-nav-name">
                    Image Gallery
                  </span>
                </a>
              </li>
            </ul>
			
          </li>

          <li class="sidebar-nav-item">
            <a href="./sales.php" class="sidebar-nav-link '.($file=="sales.php" ? "active" : "").'">
              <span class="sidebar-nav-icon">
                <i data-feather="pie-chart"></i>
				<img src="./img/icons/pie-chart.svg"></i>
              </span>
              <span class="sidebar-nav-name">
                Sales Graph
              </span>
              <span class="sidebar-nav-end">
                <i data-feather="chevron-right" class="nav-collapse-icon"></i>
              </span>
            </a>
          </li>

          <li class="sidebar-nav-item">
            <a href="./coupons.php" class="sidebar-nav-link '.($file=="coupons.php" ? "active" : "").'">
              <span class="sidebar-nav-icon">
				<img src="./img/icons/credit-card.svg"></img>
              </span>
              <span class="sidebar-nav-name">
                Coupon codes
              </span>
              <span class="sidebar-nav-end">
                <i data-feather="chevron-right" class="nav-collapse-icon"></i>
              </span>
            </a>
          </li>

          <li class="sidebar-nav-item">
            <a class="sidebar-nav-link '.($file=="transactions.php" ? "active" : "").'" href="./transactions.php">
              <span class="sidebar-nav-icon">
				<img src="./img/icons/file-text.svg"></img>
              </span>
              <span class="sidebar-nav-name">
                Transaction Log
              </span>
              <span class="sidebar-nav-end">
                <i data-feather="chevron-right" class="nav-collapse-icon"></i>
              </span>
            </a>
          </li>
		  
		  <li class="sidebar-nav-item">
            <a class="sidebar-nav-link '.($file=="ban_list.php" ? "active" : "").'" href="./ban_list.php">
              <span class="sidebar-nav-icon">
				<img src="./img/icons/slash.svg"></img>
              </span>
              <span class="sidebar-nav-name">
                Ban List
              </span>
              <span class="sidebar-nav-end">
                <i data-feather="chevron-right" class="nav-collapse-icon"></i>
              </span>
            </a>
          </li>
        </ul>
      </div>';