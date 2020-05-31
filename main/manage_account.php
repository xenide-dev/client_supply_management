<?php
  include "connection/connection.php";
  session_start();

  if(!isset($_SESSION["username"])){
    header("Location: login.php");
  }

  if(isset($_POST["update_account"])){ // For update
    $uid = $_POST["uid"];
    $employeeid = $_POST["employeeid"];
    $fname = strtoupper($_POST["fname"]);
    $mname = strtoupper($_POST["mname"]);
    $midinit = substr($mname, 0, 1);
    $lname = strtoupper($_POST["lname"]);
    $birthdate = $_POST["birthdate"];
    $gender = $_POST["gender"];
    $citizenship = strtoupper($_POST["citizenship"]);
    $religion = strtoupper($_POST["religion"]);
    $address = strtoupper($_POST["address"]);
    $contact_mobile = $_POST["contact_mobile"];
    $contact_email = $_POST["contact_email"];
    $username = $_POST["username"];
    $user_type = $_POST["user_type"];
    if(isset($_POST["privileges"])){
      $privileges = implode(",", $_POST["privileges"]);
    }else{
      $privileges = null;
    }

    $update = DB::run("UPDATE user_accounts SET employeeid = ?, username = ?, fname = ?, mname = ?, lname = ?, midinit = ?, birthdate = ?, gender = ?, citizenship = ?, religion = ?, address = ?, contact_mobile = ?, contact_email = ?, user_type = ?, priviledges = ? WHERE uid = ?", [$employeeid, $username, $fname, $mname, $lname, $midinit, $birthdate, $gender, $citizenship, $religion, $address, $contact_mobile, $contact_email, $user_type, $privileges, $uid]);

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
    <!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">


    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">

    <style>
      input, select {
        margin-bottom: 10px;
      }
    </style>
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
                <h3>Account Management</h3>
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
                    <h2>List of Account</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php
                      if(isset($_POST["submit"])){
                        $employeeid = $_POST["employeeid"];
                        $fname = strtoupper($_POST["fname"]);
                        $mname = strtoupper($_POST["mname"]);
                        $midinit = substr($mname, 0, 1);
                        $lname = strtoupper($_POST["lname"]);
                        $birthdate = $_POST["birthdate"];
                        $gender = $_POST["gender"];
                        $citizenship = strtoupper($_POST["citizenship"]);
                        $religion = strtoupper($_POST["religion"]);
                        $address = strtoupper($_POST["address"]);
                        $contact_mobile = $_POST["contact_mobile"];
                        $contact_email = $_POST["contact_email"];
                        $username = $_POST["username"];
                        $user_type = $_POST["user_type"];
                        $password = md5($_POST["password"]);
                        $temp_pass = $_POST["password"];
                        if(isset($_POST["privileges"])){
                          $privileges = implode(",", $_POST["privileges"]);
                        }else{
                          $privileges = null;
                        }

                        // check if username already exist
                        $c = DB::run("SELECT * FROM user_accounts WHERE username = ?", [$username]);
                        if($c->fetch()){
                    ?>
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <strong>Failed!</strong> Employee ID already exist
                        </div>
                    <?php
                        }else{
                          $in = DB::run("INSERT INTO user_accounts(username, password, temp_pass, user_type, gmt_created, priviledges, employeeid, fname, mname, lname, midinit, birthdate, gender, citizenship, religion, address, contact_mobile, contact_email) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", [$username, $password, $temp_pass, $user_type, date("Y-m-d H:i:s"), $privileges, $employeeid, $fname, $mname, $lname, $midinit, $birthdate, $gender, $citizenship, $religion, $address, $contact_mobile, $contact_email]);

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
                            <th>Employee's Name</th>
                            <th>Account Type</th>
                            <th>Account Status</th>
                            <th>Last Access</th>
                            <td>Action</td>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            // $retrieve = DB::run("SELECT * FROM user_accounts");
                            $retrieve = DB::run("SELECT * FROM user_accounts WHERE uid != ?",[$_SESSION["uid"]]);
                            while ($row = $retrieve->fetch()) {
                          ?>
                          <tr>
                            <td><?php echo $row["lname"] . ", " . $row["fname"] . " " . $row["midinit"] . "."; ?></td>
                            <td><?php echo $row["user_type"]; ?></td>
                            <td><?php echo ($row["isActive"] == 1 ? "Active" : "Inactive"); ?></td>
                            <td>
                              <?php 
                                if($row["gmt_last_access"] == null && $row["temp_pass"] != null){
                                  echo "Newly created - Temp Password: " . $row["temp_pass"] ;
                                }else{
                                  if($row["isOnline"] == 1){
                                    echo "Online";
                                  }else{
                                    echo DB::time_elapsed_string($row["gmt_last_access"]); 
                                  }
                                }
                              ?>
                            </td>
                            <td>
                              <button class="btn btn-primary btn-xs" onclick="editRow(<?php echo $row['uid']; ?>);" data-toggle="modal" data-target=".bs-update-modal-sm">Edit</button>
                              <?php 
                                if($row["isActive"]){
                              ?>
                              <button class="btn btn-danger btn-xs" onclick="toggle_account_status(<?php echo $row['uid']; ?>, 'deactivate')">Deactivate</button>
                              <?php
                                }else{
                              ?>
                              <button class="btn btn-success btn-xs" onclick="toggle_account_status(<?php echo $row['uid']; ?>, 'activate')">Activate</button>
                              <?php
                                }
                              ?>
                            </td>
                          </tr>
                          <?php
                            }
                          ?>
                        </tbody>
                      </table>
                      <!-- modal for update -->
                      <div class="modal fade bs-update-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">

                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmUpdate">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Update User's Information</h4>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col-md-6 col-xs-12">
                                    <input type="hidden" class="form-control" id="uid" name="uid">
                                    <h3>Basic Information</h3>
                                    <label>Employee ID: </label>
                                    <input type="text" class="form-control" id="up_employeeid" name="employeeid" placeholder="Enter your text" required>
                                    <label>First Name: </label>
                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter your text" required data-parsley-pattern="/^[A-Za-z]+$/">
                                    <label>Middle Name: </label>
                                    <input type="text" class="form-control" id="mname" name="mname" placeholder="Enter your text" required data-parsley-pattern="/^[A-Za-z]+$/">
                                    <label>Last Name: </label>
                                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter your text" required data-parsley-pattern="/^[A-Za-z]+$/">
                                    <label>Date of Birth: </label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                                    <label>Gender: </label>
                                    <select name="gender" id="gender" class="form-control" required>
                                      <option value="">-- Please select a value --</option>
                                      <option value="Male">Male</option>
                                      <option value="Female">Female</option>
                                    </select>
                                    <label>Citizenship: </label>
                                    <input type="text" class="form-control" id="citizenship" name="citizenship" placeholder="Enter your text" required>
                                    <label>Religion: </label>
                                    <input type="text" class="form-control" id="religion" name="religion" placeholder="Enter your text" required>
                                    <label>Address: </label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter your text" required>
                                    <h3>Contact Information</h3>
                                    <label>Mobile Number: </label>
                                    <input type="text" class="form-control" id="contact_mobile" name="contact_mobile" placeholder="Enter your text" required data-inputmask="'mask': '9999 999 9999'">
                                    <label>Email Address: </label>
                                    <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="Enter your text" required>
                                  </div>
                                  <div class="col-md-6 col-xs-12">
                                    <h3>User Credentials</h3>
                                    <label>Username: (Same as Employee ID)</label>
                                    <input type="text" class="form-control" name="username" id="up_username" placeholder="Enter your text" required readonly>
                                    <label>Account Type: </label>
                                    <select name="user_type" id="user_type" class="form-control" required>
                                      <option value="">-- Please select a value --</option>
                                      <option value="Administrator">Administrator</option>
                                      <option value="Regional Director">Regional Director</option>
                                      <option value="Inspector">Inspector</option>
                                      <option value="User">User</option>
                                    </select>
                                    <h3>Privileges: </h3>
                                    <label>Data Entry:</label>
                                    <div class="form-group">
                                      <label><input type="checkbox" id="item_equipment" name="privileges[]" class="js-switch" value="item_equipment" /> Item / Equipment</label><br/>
                                    </div>
                                    <label>Supply and Equipment:</label>
                                    <div class="form-group">
                                      <label><input type="checkbox" name="privileges[]" id="purchase_order" class="js-switch" value="purchase_order" /> Manage Purchase Orders</label><br/>
                                      <label><input type="checkbox" name="privileges[]" id="list_ppmp" class="js-switch" value="list_ppmp" /> List of PPMPs</label><br/>
                                      <label><input type="checkbox" name="privileges[]" id="list_supplies" class="js-switch" value="list_supplies" /> List of Supplies</label><br/>
                                      <label><input type="checkbox" name="privileges[]" id="list_equipment" class="js-switch" value="list_equipment" /> List of Equipments</label><br/>
                                      <label><input type="checkbox" name="privileges[]" id="list_request" class="js-switch" value="list_request" /> List of Requests</label><br/>
                                      <label><input type="checkbox" name="privileges[]" id="doc_approval" class="js-switch" value="doc_approval" /> Documents for Approval</label><br/>
                                      <label><input type="checkbox" name="privileges[]" id="inspection_supplies_equipments" class="js-switch" value="inspection_supplies_equipments" /> Inspection of Supplies/Equipments</label><br/>
                                      <label>List of Issuances: </label><br/>
                                      <div style="margin-left: 20px;">
                                        <label><input type="checkbox" id="issuance_supplies" name="privileges[]" class="js-switch" value="issuance_supplies" /> Issuance of Supplies</label><br/>
                                        <label><input type="checkbox" id="issuance_equipments" name="privileges[]" class="js-switch" value="issuance_equipments" /> Issuance of Equipments</label><br/>
                                        <label><input type="checkbox" id="issuance_records" name="privileges[]" class="js-switch" value="issuance_records" /> Issuance Records</label><br/>
                                      </div>
                                    </div>
                                    <label>Report Generation:</label>
                                    <div class="form-group">
                                      <label><input type="checkbox" id="reports" name="privileges[]" class="js-switch" value="reports" /> Reports</label><br/>
                                    </div>
                                    <label>Account Management:</label>
                                    <div class="form-group">
                                      <label><input type="checkbox" id="manage_account" name="privileges[]" class="js-switch" value="manage_account" /> Manage Account</label><br/>
                                    </div>
                                    <label>System:</label>
                                    <div class="form-group">
                                      <label><input type="checkbox" id="user_activities" name="privileges[]" class="js-switch" value="user_activities" /> User Activities</label><br/>
                                    </div>
                                  </div>
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

                      <div>
                        <button class="btn btn-success btn-xs" data-toggle="modal" data-target=".modal_salary"><span class="fa fa-plus"></span> Add Account</button>
                        <!-- TODOIMP: ADD ALL TRANSACTION HERE AS privileges -->
                        <div class="modal fade modal_salary" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                              <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate>
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel">Add Account</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                      <h3>Basic Information</h3>
                                      <label>Employee ID: </label>
                                      <input type="text" class="form-control" name="employeeid" id="employeeid" placeholder="Enter your text" required>
                                      <label>First Name: </label>
                                      <input type="text" class="form-control" name="fname" placeholder="Enter your text" required data-parsley-pattern="/^[A-Za-z]+$/">
                                      <label>Middle Name: </label>
                                      <input type="text" class="form-control" name="mname" placeholder="Enter your text" required data-parsley-pattern="/^[A-Za-z]+$/">
                                      <label>Last Name: </label>
                                      <input type="text" class="form-control" name="lname" placeholder="Enter your text" required data-parsley-pattern="/^[A-Za-z]+$/">
                                      <label>Date of Birth: </label>
                                      <input type="date" class="form-control" name="birthdate" placeholder="Enter your text" required>
                                      <label>Gender: </label>
                                      <select name="gender" class="form-control" required>
                                        <option value="">-- Please select a value --</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                      </select>
                                      <label>Citizenship: </label>
                                      <input type="text" class="form-control" name="citizenship" placeholder="Enter your text" required>
                                      <label>Religion: </label>
                                      <input type="text" class="form-control" name="religion" placeholder="Enter your text" required>
                                      <label>Address: </label>
                                      <input type="text" class="form-control" name="address" placeholder="Enter your text" required>
                                      <h3>Contact Information</h3>
                                      <label>Mobile No.: </label>
                                      <input type="text" class="form-control" name="contact_mobile" placeholder="Enter your text" required data-inputmask="'mask': '9999 999 9999'">
                                      <label>Email Address: </label>
                                      <input type="email" class="form-control" name="contact_email" placeholder="Enter your text" required>
                                      <br/>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                      <h3>User Credentials</h3>
                                      <label>Username: (Same as Employee ID)</label>
                                      <input type="text" class="form-control" name="username" id="username" placeholder="Enter your text" required readonly>
                                      <label>Account Type: </label>
                                      <select name="user_type" class="form-control" required>
                                        <option value="">-- Please select a value --</option>
                                        <option value="Administrator">Administrator</option>
                                        <option value="Regional Director">Regional Director</option>
                                        <option value="Inspector">Inspector</option>
                                        <option value="User">User</option>
                                      </select>
                                      <label>Password: (System Generated)</label>
                                      <?php 
                                        $sys_gen = md5(date("Y-m-d H:i:s"));
                                        $sys_gen = substr($sys_gen, 0, 8);
                                      ?>
                                      <input type="text" class="form-control" name="password" placeholder="Enter your text" readonly value="<?php echo $sys_gen; ?>">
                                      <h3>Privileges: </h3>
                                      <label>Data Entry:</label>
                                      <div class="form-group">
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="item_equipment" /> Item / Equipment</label><br/>
                                      </div>
                                      <label>Supply and Equipment:</label>
                                      <div class="form-group">
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="purchase_order" /> Manage Purchase Orders</label><br/>
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="list_ppmp" /> List of PPMPs</label><br/>
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="list_supplies" /> List of Supplies</label><br/>
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="list_equipment" /> List of Equipments</label><br/>
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="list_request" /> List of Requests</label><br/>
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="doc_approval" /> Documents for Approval</label><br/>
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="inspection_supplies_equipments" /> Inspection of Supplies/Equipments</label><br/>
                                        <label>List of Issuances: </label><br/>
                                        <div style="margin-left: 20px;">
                                          <label><input type="checkbox" id="grp1_1" name="privileges[]" class="js-switch" value="issuance_supplies" /> Issuance of Supplies</label><br/>
                                          <label><input type="checkbox" id="grp1_2" name="privileges[]" class="js-switch" value="issuance_equipments" /> Issuance of Equipments</label><br/>
                                          <label><input type="checkbox" id="grp1_3" name="privileges[]" class="js-switch" value="issuance_records" /> Issuance Records</label><br/>
                                        </div>
                                      </div>
                                      <label>Report Generation:</label>
                                      <div class="form-group">
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="reports" /> Reports</label><br/>
                                      </div>
                                      <label>Account Management:</label>
                                      <div class="form-group">
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="manage_account" /> Manage Account</label><br/>
                                      </div>
                                      <label>System:</label>
                                      <div class="form-group">
                                        <label><input type="checkbox" name="privileges[]" class="js-switch" value="user_activities" /> User Activities</label><br/>
                                      </div>
                                      <hr/>
                                    </div>
                                  </div>
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
    <!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- jquery.inputmask -->
    <script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- sweetalert -->
    <script src="../vendors/sweetalert/sweetalert.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>
    <script src="js/custom/manage_account.js"></script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
