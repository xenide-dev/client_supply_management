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
    <!-- select2 -->
    <link href="../vendors/select2/css/select2.min.css" rel="stylesheet">
    <link href="../vendors/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">


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
                  if($_GET["type"] == "make_request"){
              ?>
              <?php
                    if(isset($_POST["request_purpose"])){
                        $request_purpose = $_POST["request_purpose"];
                        $request_no = strtoupper($_POST["request_no"]);
                        $counts = (isset($_POST["item_type"]) ? array_count_values($_POST["item_type"]) : null);
                        $totalSupplies = (isset($counts["Consumable"]) ? $counts["Consumable"] : 0);
                        $totalEquipments = (isset($counts["Non-Consumable"]) ? $counts["Non-Consumable"] : 0);

                        // check if request number already exist
                        $c = DB::run("SELECT * FROM request WHERE request_no = ?", [$request_no]);
                        if($c->fetch()){
              ?>
              <div class="alert alert-danger">
                  <strong>Error!</strong> Request Number already exist!
              </div>
              <?php
                        }else{
                            // insert to main table
                            $m = DB::run("INSERT INTO request(uid, request_no, request_type, request_purpose, total_supplies_requested, total_equipments_requested) VALUES(?, ?, ?, ?, ?, ?)", [$_SESSION["uid"], $request_no, 'Purchase Request', $request_purpose, $totalSupplies, $totalEquipments]);
                            $lastID = DB::getLastInsertedID();
                            if(isset($_POST["itemid"])){
                                // insert to sub table
                                for($i = 0; $i < count($_POST["itemid"]); $i++){
                                    DB::run("INSERT INTO request_items(rid, itemid, requested_qty, requested_unit) VALUES(?, ?, ?, ?)", [$lastID, $_POST["itemid"][$i], $_POST["requested_qty"][$i], $_POST["requested_unit"][$i]]);
                                }
                            }

                            // create trace entry
                            DB::insertTraceEntry(1, $lastID, $_SESSION["uid"], "Regional Director", null, "Pending", null);
              ?>
              <div class="alert alert-success">
                  <strong>Success!</strong> Your request has been submitted. Thank you! <a href="list_supplies.php">Go back</a>
              </div>
              <?php
                        }
                    }else{
                      ?>
                      <!-- Make Purchase Order -->
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                            <h2>Purchase Request</h2>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <?php
                                $rn = DB::run("SELECT * FROM request ORDER BY rid DESC");
                                $rnrow = $rn->fetch();
                                if(isset($rnrow["rid"])){
                                    $len = strlen(strval($rnrow["rid"])) + 1;
                                    $rnno = "RN-" . str_pad($rnrow["rid"] + 1, $len, '0', STR_PAD_LEFT);
                                }else{
                                    $rnno = "RN-" . str_pad(1, 2, '0', STR_PAD_LEFT);
                                }
                            ?>
                            <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="POST" data-parsley-validate id="frmForm">
                              <div class="form-group">
                                  <div class="row">
                                      <div class="col-md-4 col-xs-12">
                                          <label>Request No.: </label>
                                          <input type="text" class="form-control" name="request_no" placeholder="Request Number" value="<?php echo $rnno; ?>">
                                      </div>
                                      <div class="col-md-8 col-xs-12">
                                          <label>Purpose: </label>
                                          <input type="text" class="form-control" name="request_purpose" placeholder="Please enter your purpose">
                                      </div>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <label>Add Item/Equipment</label>
                                  <div class="requestItemContainer">
                                      
                                  </div>
                                  <br/>
                                  <button type="button" class="btn btn-primary btn-xs" id="btnAddItemPurchase"><span class="fa fa-plus"></span> Add item/equipment</button>
                              </div>
                              <div class="modal-footer">
                                <input type="submit" class="btn btn-primary" name="frmsubmit" value="Submit Request">
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
              <?php
                    }
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
                    <h2>List of Supplies</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table id="dtList" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Item Code</th>
                            <th>Item Name/Description</th>
                            <th width="100">Available Quantity</th>
                            <th width="100">Base Quantity <br/> Default (100)</th>
                            <th>Item Unit</th>
                            <th width="100">Item Type</th>
                            <th width="100">Reorder Point (Percentage)</th>
                            <th>Last Update</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      <div>
                        <?php
                          if($_SESSION["user_type"] == "Administrator"){
                        ?>
                        <a href="list_supplies.php?type=make_request" class="btn btn-success btn-xs"><span class="fa fa-plus"> Make Purchase Request</span></a>
                        <?php
                          }
                        ?>
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
    <!-- select2 -->
    <script src="../vendors/select2/js/select2.full.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>
    <script src="js/custom/list_supplies.js"></script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
