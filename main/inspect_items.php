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
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">


    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <link href="css/custom/custom_loading.css" rel="stylesheet">
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
                <h3>Supply and Equipment</h3>
              </div>
            </div>

            <div class="clearfix"></div>


            <div class="row">
              <?php
                if(isset($_GET["type"])){
                  if($_GET["type"] == "make_order"){
              ?>
              <!-- Make Purchase Order -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Purchase Order</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate>
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
                      </div>
                      <br/>
                      <div class="modal-footer">
                        <input type="submit" name="update_account" value="Save Changes" class="btn btn-success">
                        <input type="submit" name="remove_account" value="Remove" class="btn btn-danger">
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <?php
                  }else{
              ?>
              <div class="alert alert-danger">
                  <strong>Oops!</strong> Looks like you manage to change the url. Please go back or choose one of the navigation links on the left
              </div>
              <?php
                  }
                }else{
              ?>
              <!-- Supplies List -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div id="loading_modal">
                    <div id="loading-circle"></div>
                  </div>
                  <div class="x_title">
                    <h2>Inspection and Acceptance of Supplies and Equipments</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php
                        if(isset($_POST["submit_cat"])){
                            $cat_code = $_POST["cat_code"];
                            $cat_name = strtoupper($_POST["cat_name"]);
                            $cat_descrip = strtoupper($_POST["cat_descrip"]);

                            $c = DB::run("INSERT INTO item_category(cat_code, cat_name, cat_descrip) VALUES(?, ?, ?)", [$cat_code, $cat_name, $cat_descrip]);
                            if($c->rowCount() > 0){
                    ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong> Data has been added
                    </div>
                    <?php
                            }
                        }

                        if(isset($_POST["update_cat"])){
                          $catid = $_POST["catid"];
                          $cat_code = $_POST["cat_code"];
                          $cat_name = strtoupper($_POST["cat_name"]);
                          $cat_descrip = strtoupper($_POST["cat_descrip"]);

                          $u = DB::run("UPDATE item_category SET cat_code = ?, cat_name = ?, cat_descrip = ? WHERE catid = ?", [$cat_code, $cat_name, $cat_descrip, $catid]);
                          if($u->rowCount() > 0){
                    ?>
                    <div class="alert alert-success">
                      <strong>Success!</strong> Category has been updated
                    </div>
                    <?php
                          }
                        }
                    ?>
                     <table id="dtList" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th width="150">Purchase Order No.</th>
                            <th>Supplier Name</th>
                            <th>Supplier Address</th>
                            <th>Date</th>
                            <th>Requested By</th>
                            <th width="250">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      <div class="modal fade viewItem" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">

                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmCatUpdate">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Item Lists</h4>
                              </div>
                              <div class="modal-body">
                                <table class="table table-striped" id="itemsContainer">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item Code</th>
                                            <th>Item Name/Description</th>
                                            <th>Quantity</th>
                                            <th>Unit of Measure</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                            <th>Delivered?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                                <h4>Total Amount: ₱ <span id="totalAmount"></span></h4>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-success" onclick="processDelivery();">Save</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </form>

                          </div>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
              <?php
                }
              ?>




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
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- sweetalert -->
    <script src="../vendors/sweetalert/sweetalert.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>
    <script src="js/custom/inspection.js"></script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
