<?php
  include "connection/connection.php";
  session_start();

  if(!isset($_SESSION["username"])){
    header("Location: login.php");
  }

  if(isset($_POST["update_employee"])){ // For update
    $employeeid = $_POST["employeeid"];
    $lname = strtoupper($_POST["lname"]);
    $fname = strtoupper($_POST["fname"]);
    $midname = strtoupper($_POST["midname"]);
    $name_ext = strtoupper($_POST["name_ext"]);
    $agencyemployeeno = $_POST["agencyemployeeno"];

    $update = DB::run("UPDATE employee SET lname = ?, fname = ?, midname = ?, name_ext = ?, agencyemployeeno = ? WHERE employeeid = ?", [$lname, $fname, $midname, $name_ext, $agencyemployeeno, $employeeid]);

    if($update->rowCount() > 0){
      $modify_success["update"] = true;

      DB::insertLog($_SESSION["uid"], "Update Employee Record", "Employee ($lname, $fname) has been updated successfully", "UPDATE EMPLOYEE");
    }else{
      $modify_success["update"] = false;
    }
  }
  if(isset($_POST["remove_employee"])){ // For delete
    $employeeid = $_POST["employeeid"];

    //retrieve first
    $ret = DB::run("SELECT * FROM employee WHERE employeeid = ?", [$employeeid]);
    $retrow = $ret->fetch();

    $update = DB::run("DELETE FROM employee WHERE employeeid = ?", [$employeeid]);

    if($update->rowCount() > 0){
      // remove all dependencies
      DB::run("DELETE FROM appointment WHERE employeeid = ?", [$employeeid]);
      DB::run("DELETE FROM back_related WHERE employeeid = ?", [$employeeid]);
      DB::run("DELETE FROM educbackground WHERE employeeid = ?", [$employeeid]);
      DB::run("DELETE FROM empchildren WHERE employeeid = ?", [$employeeid]);
      DB::run("DELETE FROM emp_references WHERE employeeid = ?", [$employeeid]);
      DB::run("DELETE FROM license WHERE employeeid = ?", [$employeeid]);
      DB::run("DELETE FROM org_involvement WHERE employeeid = ?", [$employeeid]);
      DB::run("DELETE FROM other_info WHERE employeeid = ?", [$employeeid]);
      DB::run("DELETE FROM training_prog WHERE employeeid = ?", [$employeeid]);
      DB::run("DELETE FROM work_experience WHERE employeeid = ?", [$employeeid]);


      $modify_success["delete"] = true;
      DB::insertLog($_SESSION["uid"], "Delete Employee Record", "Employee ($retrow[lname], $retrow[fname]) has been deleted successfully", "DELETE EMPLOYEE");
    }else{
      $modify_success["delete"] = false;
    }
  }

  if(isset($_POST["submit"])){
    $lname = strtoupper($_POST["lname"]);
    $fname = strtoupper($_POST["fname"]);
    if(isset($_POST["midname"])){
      $midname = strtoupper($_POST["midname"]);
      $midinit = substr($midname, 0, 1) . ".";
    }else{
      $midname = "";
      $midinit = "";
    }
    $name_ext = strtoupper($_POST["name_ext"]);
    $agencyemployeeno = $_POST["agencyemployeeno"];

    $in = DB::run("INSERT INTO employee(lname, fname, midname, name_ext, midinit, agencyemployeeno) VALUES(?,?,?,?,?,?)", [$lname, $fname, $midname, $name_ext, $midinit, $agencyemployeeno]);
    $upMes = false;
    if($in->rowCount() > 0){
      $lastId = DB::getLastInsertedID();
      // create an entry for table back_related
      $iin = DB::run("INSERT INTO back_related(employeeid) VALUES(?)", [$lastId]);
      // create an entry for table other_info
      $ioi = DB::run("INSERT INTO other_info(employeeid) VALUES(?)", [$lastId]);
      // create an entry for leave credits
      $lc = DB::run("INSERT INTO leave_credits(employeeid) VALUES(?)", [$lastId]);

      // INSERT LOG
      DB::insertLog($_SESSION["uid"], "Insert Employee Record", "Employee ($lname, $fname) has been inserted successfully", "ADD EMPLOYEE");

      // proceed to personal info
      header("Location: personal_info.php?employeeid=" . $lastId);
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

    <title>Department of Tourism | Region V - Supply and Equipment Management System | Welcome "<?php echo $_SESSION["username"];?>"</title>

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
                <h3>Data Entry</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="alert alert-info alert-dismissible fade in" role="alert">
              <strong>Note!</strong> Kindly click the row for the update
            </div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Employees</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <!-- <div class="col-md-4">
                      <div class="form-group">
                        <label class="col-sm-3">Button addons</label>
                        <div class="col-sm-9">
                          <div class="input-group">
                            <select class="form-control" name="">
                              <option value="">-- Select an Option --</option>
                              <option value="">100</option>
                              <option value="">200</option>
                              <option value="">300</option>
                            </select>
                            <div class="input-group-btn">
                              <button type="button" class="btn btn-primary">Load</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> -->

                    <br/>
                    <?php
                      if(isset($upMes)){
                        if($upMes == false){
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
                     <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Extension</th>
                            <th>Agency Employee No</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $retrieve = DB::run("SELECT * FROM employee");
                            while ($row = $retrieve->fetch()) {
                          ?>
                          <tr onclick="<?php echo "showInfo($row[employeeid], '$row[lname]', '$row[fname]', '$row[midname]', '$row[name_ext]', '$row[agencyemployeeno]');"?>" style="cursor: pointer;" data-toggle="modal" data-target=".bs-update-modal-sm">
                            <td><?php echo $row["lname"]; ?></td>
                            <td><?php echo $row["fname"]; ?></td>
                            <td><?php echo $row["midname"]; ?></td>
                            <td><?php echo $row["name_ext"]; ?></td>
                            <td><?php echo $row["agencyemployeeno"]; ?></td>
                            <td><?php echo $row["gender"]; ?></td>
                            <td><?php echo $row["birthdate"]; ?></td>
                            <td>
                              <a href="personal_info.php?employeeid=<?php echo $row["employeeid"];?>" class="btn btn-success btn-xs"><span class="fa fa-edit"></span> Edit Information</a>
                              <a target="_blank" href="modules/generate_pds.php?employeeid=<?php echo $row["employeeid"];?>" class="btn btn-info btn-xs">PDS (Excel)</a>
                            </td>
                          </tr>
                          <?php
                            }
                          ?>
                        </tbody>
                      </table>
                      <div class="modal fade bs-update-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">

                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Update Employee</h4>
                              </div>
                              <div class="modal-body">
                                <input type="text" name="employeeid" id="employeeid" style="display: none;">
                                <label>Last Name: </label>
                                <input class="form-control" type="text" name="lname" id="lname" required>
                                <br/>
                                <label>First Name: </label>
                                <input class="form-control" type="text" name="fname" id="fname" required>
                                <br/>
                                <label>Middle Name: </label>
                                <input class="form-control" type="text" name="midname" id="midname" required>
                                <br/>
                                <label>Name Extension (Jr, Sr): </label>
                                <input class="form-control" type="text" name="name_ext" id="name_ext">
                                <br/>
                                <label>Agency Employee No.: </label>
                                <input class="form-control" type="text" name="agencyemployeeno" id="agencyemployeeno">
                                <br/>
                              </div>
                              <div class="modal-footer">
                                <input type="submit" name="update_employee" value="Save Changes" class="btn btn-success">
                                <input type="submit" name="remove_employee" value="Remove" class="btn btn-danger">
                              </div>
                            </form>

                          </div>
                        </div>
                      </div>
                      <div>
                        <button class="btn btn-success btn-xs" data-toggle="modal" data-target=".bs-example-modal-sm"><span class="fa fa-plus"></span> Add Employee</button>

                        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">

                              <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel">Add Employee</h4>
                                </div>
                                <div class="modal-body">
                                  <label>Last Name: </label>
                                  <input type="text" class="form-control" name="lname" placeholder="Enter your text ..." required>
                                  <br/>
                                  <label>First Name: </label>
                                  <input type="text" class="form-control" name="fname" placeholder="Enter your text ..." required>
                                  <br/>
                                  <label>Middle Name: </label>
                                  <input type="text" class="form-control" name="midname" placeholder="Enter your text ..." required>
                                  <br/>
                                  <label>Name Extension (Jr, Sr): </label>
                                  <input type="text" class="form-control" name="name_ext" placeholder="Enter your text ...">
                                  <br/>
                                  <label>Agency Employee No.: </label>
                                  <input type="text" class="form-control" name="agencyemployeeno" placeholder="Enter your text ...">
                                  <br/>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
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

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>


    <script>
      function showInfo(employeeid, lname, fname, midname, name_ext, agencyemployeeno) {
        $('#row_update').show();

        $('#employeeid').val(employeeid);
        $('#lname').val(lname);
        $('#fname').val(fname);
        $('#midname').val(midname);
        $('#name_ext').val(name_ext);
        $('#agencyemployeeno').val(agencyemployeeno);
      }
    </script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
