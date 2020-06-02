<?php
  $privileges = $_SESSION["privileges"];
?>
<div class="left_col scroll-view">
  <div class="navbar nav_title" style="border: 0;">
    <a href="index.php" class="site_title"><i class="fa fa-truck"></i> <span>DOT - SEMS</span></a>
  </div>

  <div class="clearfix"></div>

  <!-- menu profile quick info -->
  <div class="profile clearfix">
    <div class="profile_pic">
      <img src="images/user.png" alt="..." class="img-circle profile_img">
    </div>
    <div class="profile_info">
      <span>Welcome,</span>
      <h2>
        <?php
          echo $_SESSION["fname"];
        ?>
      </h2>
    </div>
    <div class="clearfix"></div>
  </div>
  <!-- /menu profile quick info -->

  <br />

  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3>General</h3>
      <ul class="nav side-menu">
        <li><a href="index.php"><i class="fa fa-home"></i> Dashboard</a></li>
      </ul>
    </div>
    <?php
      if(strpos($privileges, 'reports') !== false){
    ?>
    <div class="menu_section">
      <h3>Data Entry</h3>
      <ul class="nav side-menu">
        <li><a href="data_entry.php"><i class="fa fa-suitcase"></i> Item / Equipment</a></li> 
      </ul>
    </div>
    <?php
      }
    ?>
    <div class="menu_section">
      <h3>Supply and Equipment</h3>
      <ul class="nav side-menu">
        <?php
          if(strpos($privileges, 'purchase_order') !== false){
        ?>
        <li><a href="manage_purchase_orders.php"><i class="fa fa-list"></i> Manage Purchase Orders</a></li>
        <?php
          }
        ?>
        <?php
          if(strpos($privileges, 'list_ppmp') !== false){
        ?>
        <li><a href="list_ppmp.php"><i class="fa fa-list"></i> List of PPMPs</a></li>
        <?php
          }
        ?>
        <?php
          if(strpos($privileges, 'list_supplies') !== false){
        ?>
        <li><a href="list_supplies.php"><i class="fa fa-list"></i> List of Supplies</a></li>
        <?php
          }
        ?>
        <?php
          if(strpos($privileges, 'list_equipment') !== false){
        ?>
        <li><a href="list_equipments.php"><i class="fa fa-list"></i> List of Equipments</a></li>
        <?php
          }
        ?>
        <?php
          if(strpos($privileges, 'list_request') !== false){
        ?>
        <li><a href="list_requests.php"><i class="fa fa-list"></i> List of Requests</a></li>
        <?php
          }
        ?>
        <?php
          if(strpos($privileges, 'doc_approval') !== false){
        ?>
        <li><a href="documents_approval.php"><i class="fa fa-legal"></i> Documents for Approval</a></li>
        <?php
          }
        ?>
        <?php
          if(strpos($privileges, 'inspection_supplies_equipments') !== false){
        ?>
        <li><a href="inspect_items.php"><i class="fa fa-search"></i> Inspection of Supplies / Equipments</a></li>
        <?php
          }
        ?>
      </ul>
    </div>
    <div class="menu_section">
      <h3>QR Management</h3>
      <ul class="nav side-menu">
        <li><a href="qr_reader.php"><i class="fa fa-qrcode"></i> QR Reader</a></li>
      </ul>
    </div>
    <?php
      if(strpos($privileges, 'reports') !== false){
    ?>
    <div class="menu_section">
      <h3>Report Management</h3>
      <ul class="nav side-menu">
        <li><a href="reports.php"><i class="fa fa-line-chart"></i> Reports</a></li> 
      </ul>
    </div>
    <?php
      }
    ?>
    <?php
      if(strpos($privileges, 'manage_account') !== false){
    ?>
    <div class="menu_section">
      <h3>Account Management</h3>
      <ul class="nav side-menu">
        <li><a href="manage_account.php"><i class="fa fa-user"></i> Manage Account</a></li>
      </ul>
    </div>
    <?php
      }
    ?>
    <div class="menu_section">
      <h3>System</h3>
      <ul class="nav side-menu">
        <?php
          if(strpos($privileges, 'reports') !== false){
        ?>
        <li><a href="user_activities.php"><i class="fa fa-tasks"></i> User Activities</a></li>
        <?php
          }
        ?>
        <li><a href="change_password.php"><i class="fa fa-lock"></i> Change Password</a></li>
      </ul>
    </div>
  </div>
  <!-- /sidebar menu -->

  <!-- /menu footer buttons -->
  <div class="sidebar-footer hidden-small">
    <a data-toggle="tooltip" data-placement="top" title="Settings">
      <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
      <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Lock">
      <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
      <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
    </a>
  </div>
  <!-- /menu footer buttons -->
</div>
