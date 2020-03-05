<?php
  include "connection/connection.php";
  session_start();

  if(!isset($_SESSION["username"])){
    header("Location: login.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Department of Tourism | Region V - Supply and Equipment Management System | Welcome "<?php echo $_SESSION["username"];?>"</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md footer_fixed">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <?php
            require_once("modules/navigation.php");
          ?>
        </div>

        <?php
          require_once("modules/header.php");
        ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Dashboard</h3>
              </div>
            </div>

            <div class="clearfix"></div>
            <?php
              // data retrieval
              // for total users
              $u = DB::run("SELECT COUNT(*) as total FROM user_accounts");
              $urow = $u->fetch();

              // for total request
              $r = DB::run("SELECT COUNT(*) as total FROM request");
              $rrow = $r->fetch();

              // for total ppmp
              $p = DB::run("SELECT COUNT(*) as total FROM ppmp");
              $prow = $p->fetch();
              
              // for total online users
              $o = DB::run("SELECT COUNT(*) as total FROM user_accounts WHERE isOnline = 1");
              $orow = $o->fetch();
            ?>
            <div class="row top_tiles">
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-users"></i></div>
                  <div class="count"><?php echo $urow["total"];?></div>
                  <h3>Total Users</h3>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-edit"></i></div>
                  <div class="count"><?php echo $rrow["total"];?></div>
                  <h3>Total Requests</h3>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-list"></i></div>
                  <div class="count"><?php echo $prow["total"];?></div>
                  <h3>Total PPMPs</h3>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-circle text-success"></i></div>
                  <div class="count"><?php echo $orow["total"];?></div>
                  <h3>Online Users</h3>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Number of Requests per Month (<?php echo date("Y"); ?>)</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div id="chart1" style="width: 100%; min-height: 400px;"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Most Requested Items</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Item Name / Description</th>
                          <th>Number of Requests</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          // retrieve all items
                          $i = DB::run("SELECT id.item_name, id.item_description, COUNT(*) as total FROM request_items ri JOIN item_dictionary id ON ri.itemid = id.itemid GROUP BY ri.itemid ORDER BY total DESC LIMIT 10");
                          $counter=1;
                          while($irow = $i->fetch()){
                        ?>
                        <tr>
                          <th scope="row"><?php echo $counter; ?></th>
                          <td><?php echo $irow["item_name"] . " (" . $irow["item_description"] . ")"; ?></td>
                          <td width="20%"><?php echo $irow["total"]; ?></td>
                        </tr>
                        <?php
                            $counter++;
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
        <!-- /page content -->

        <?php
          require_once("modules/footer.php");
        ?>
      </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- ECharts -->
    <script src="../vendors/echarts/dist/echarts.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <script src="js/custom/index.js"></script>
  </body>
</html>
