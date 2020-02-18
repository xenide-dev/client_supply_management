<?php
  include "connection/connection.php";
  session_start();

  if(!isset($_SESSION["username"])){
    header("Location: login.php");
  }

  if(isset($_POST["update_account"])){ // For update
    $uid = $_POST["uid"];
    $fname = strtoupper($_POST["fname"]);
    $mname = strtoupper($_POST["mname"]);
    $lname = strtoupper($_POST["lname"]);
    $priviledges = implode($_POST["priviledges"], ",");

    $update = DB::run("UPDATE user_accounts SET fname = ?, mname = ?, lname = ?, priviledges = ? WHERE uid = ?", [$fname, $mname, $lname, $priviledges, $uid]);

    if($update->rowCount() > 0){
      $modify_success["update"] = true;
    }else{
      $modify_success["update"] = false;
    }
  }
  if(isset($_POST["remove_account"])){ // For delete
    $uid = $_POST["uid"];

    $update = DB::run("DELETE FROM user_accounts WHERE uid = ?", [$uid]);

    if($update->rowCount() > 0){
      $modify_success["delete"] = true;
    }else{
      $modify_success["delete"] = false;
    }
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

    <title>LGU Aroroy - Payroll Management System | Welcome "<?php echo $_SESSION["username"];?>"</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <!-- PNotify -->
    <link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">


    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
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
                <h3>User Activities</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Activity Logs</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php
                      if(isset($_POST["submit"])){
                        $fname = strtoupper($_POST["fname"]);
                        $mname = strtoupper($_POST["mname"]);
                        $lname = strtoupper($_POST["lname"]);
                        $username = $_POST["username"];
                        $password = md5($_POST["password"]);
                        $priviledges = implode($_POST["priviledges"], ",");

                        $in = DB::run("INSERT INTO user_accounts(username, password, user_type, gmt_created, priviledges, fname, mname, lname) VALUES(?,?,?,?,?,?,?,?)", [$username, $password, "user", date("Y-m-d H:i:s"), $priviledges, $fname, $mname, $lname]);
                        if($in->rowCount() > 0){
                    ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                      <strong>Success!</strong> Data has been added
                    </div>
                    <?php
                        }else{
                    ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                      <strong>Failed!</strong> Something's wrong
                    </div>
                    <?php
                        }
                      }
                    ?>
                    <?php
                      if(isset($modify_success)){ // TO DO: modify success condition here
                    ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                      <strong>Success!</strong> Data has been <?php echo (isset($modify_success["update"]) ? "updated" : "deleted"); ?>
                    </div>
                    <?php
                      }
                    ?>
                     <table id="log_datatable" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Date and Time</th>
                            <th>Username</th>
                            <th>Event Type</th>
                            <th>Activity Name</th>
                            <th>Description</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $retrieve = DB::run("SELECT * FROM user_activities ua JOIN user_accounts a ON ua.uid = a.uid ORDER BY gmt_datetime DESC");
                            while ($row = $retrieve->fetch()) {
                          ?>
                          <tr>
                            <td><?php echo date("Y-m-d h:i:s a", strtotime($row["gmt_datetime"])); ?></td>
                            <td><?php echo $row["username"]; ?></td>
                            <td><?php echo $row["event_type"]; ?></td>
                            <td><?php echo $row["act_name"]; ?></td>
                            <td><?php echo $row["act_descrip"]; ?></td>
                          </tr>
                          <?php
                            }
                          ?>
                        </tbody>
                      </table>
                      <!-- modal for update -->
                      <div class="modal fade bs-update-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">

                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Priviledge Update</h4>
                              </div>
                              <div class="modal-body">
                                <input type="hidden" class="form-control" id="uid" name="uid">
                                <label>First Name: </label>
                                <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter your text" required>
                                <label>Middle Name: </label>
                                <input type="text" class="form-control" id="mname" name="mname" placeholder="Enter your text" required>
                                <label>Last Name: </label>
                                <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter your text" required>
                                <br/>
                                <label>Priviledges: </label><br/>
                                <hr/>
                                <label>Data Entry:</label>
                                <div class="form-group">
                                  <label><input type="checkbox" name="priviledges[]" id="employee" class="js-switch" value="employee" /> Employee</label><br/>
                                  <label><input type="checkbox" name="priviledges[]" id="emp_status" class="js-switch" value="emp_status" /> Employee Status</label><br/>
                                  <label><input type="checkbox" name="priviledges[]" id="rank" class="js-switch" value="rank" /> Rank</label><br/>
                                  <label><input type="checkbox" name="priviledges[]" id="department" class="js-switch" value="department" /> Department</label><br/>
                                  <label><input type="checkbox" name="priviledges[]" id="fund" class="js-switch" value="fund" /> Source of Fund</label><br/>
                                  <label><input type="checkbox" name="priviledges[]" id="salary_grade" class="js-switch" value="salary_grade" /> Salary Grade</label><br/>
                                  <label><input type="checkbox" name="priviledges[]" id="set_appointment" class="js-switch" value="set_appointment" /> Set Appointment</label><br/>
                                </div>
                                <label>Leave Management</label>
                                <div class="form-group">
                                  <label><input type="checkbox" name="priviledges[]" id="leave_management" class="js-switch" value="leave_management" /> Leave Management</label><br/>
                                </div>
                                <label>Payroll Management</label>
                                <div class="form-group">
                                  <label><input type="checkbox" name="priviledges[]" id="payroll" class="js-switch" value="payroll" /> Payroll</label><br/>
                                </div>
                                <label>Account Management:</label>
                                <div class="form-group">
                                  <label><input type="checkbox" name="priviledges[]" id="manage_account" class="js-switch" value="manage_account" /> Manage Account</label><br/>
                                </div>
                                <label>Report Generation:</label>
                                <div class="form-group">
                                  <label><input type="checkbox" name="priviledges[]" id="reports" class="js-switch" value="reports" /> Reports</label><br/>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <input type="submit" name="update_account" value="Save Changes" class="btn btn-success">
                                <input type="submit" name="remove_account" value="Remove" class="btn btn-danger">
                              </div>
                            </form>

                          </div>
                        </div>
                      </div>
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
    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
    <!-- PNotify -->
    <script src="../vendors/pnotify/dist/pnotify.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>
    <!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>


    <script>
      $('#log_datatable').dataTable({
        "order": [[ 0, "desc" ]]
      });
    </script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
