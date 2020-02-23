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
                if(isset($_GET["type"]) && isset($_GET["rid"])){
                  if($_GET["type"] == "make_order" && $_GET["rid"] != ""){
                    if(isset($_POST["po_number"])){

                      // check if purchase order number already exist
                      $c = DB::run("SELECT * FROM purchase_order WHERE po_number = ?", [$_POST["po_number"]]);
                      if($crow = $c->fetch()){
              ?>
              <div class="alert alert-danger">
                <strong>Error!</strong> Purchase Order Number already exist! <a href="<?php echo $_SERVER["REQUEST_URI"]?>">Go back</a>
              </div>
              <?php
                      }else{
                        $po_number = strtoupper($_POST["po_number"]);
                        $supplier_name = strtoupper($_POST["supplier_name"]);
                        $supplier_address = strtoupper($_POST["supplier_address"]);
                        $riid = $_POST["riid"];
                        $unit_cost = $_POST["unit_cost"];
                        $total_cost = $_POST["total_cost"];
                        $total_amount = 0;

                        for ($i=0; $i < count($unit_cost); $i++) { 
                          $total_amount += $total_cost[$i];
                        }

                        // insert to main table
                        $i = DB::run("INSERT INTO purchase_order(rid, po_number, supplier_name, supplier_address, total_amount, created_at) VALUES(?, ?, ?, ?, ?, ?)", [$_GET["rid"], $po_number, $supplier_name, $supplier_address, $total_amount , DB::getCurrentDateTime()]);
                        $poid = DB::getLastInsertedID();

                        // insert to sub table
                        $f = false;
                        for ($i=0; $i < count($unit_cost); $i++) { 
                          $ii = DB::run("INSERT INTO purchase_order_items(poid, riid, unit_cost, total_cost) VALUES(?, ?, ?, ?)", [$poid, $riid[$i], $unit_cost[$i], $total_cost[$i]]);
                          if($ii->rowCount() > 0){
                            $f = true;
                          }
                        }

                        if($f){
              ?>
              <div class="alert alert-success">
                <strong>Success!</strong> Your purchase order has been submitted. Thank you! <a href="list_requests.php">Go back</a>
              </div>
              <?php
                        }


                        // update request to processing
                        DB::run("UPDATE request SET status = 'Processing', updated_at = ? WHERE rid = ?", [DB::getCurrentDateTime(), $_GET["rid"]]);

                        // get last trace
                        $l = DB::run("SELECT * FROM request_tracer WHERE rid = ? AND destination_uid_type = 'Administrator' AND destination_uid IS NULL ORDER BY tracer_no DESC", [$_GET["rid"]]);
                        $lrow = $l->fetch();

                        // update last trace
                        DB::run("UPDATE request_tracer SET destination_uid = ? WHERE tid = ?", [$_SESSION["uid"], $lrow["tid"]]);

                        // insert next trace (for inspector)
                        DB::run("INSERT INTO request_tracer(tracer_no, rid, source_uid, destination_uid_type, status) VALUES(?, ?, ?, ?, ?)", [intval($lrow["tracer_no"]) + 1, $_GET["rid"], $_SESSION["uid"], 'Inspector', 'Pending']);


                      }

                      

                    }else{
              ?>
              <!-- Make Purchase Order -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Prepare Purchase Order</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php
                      $rid = $_GET["rid"];
                      $r = DB::run("SELECT * FROM request r JOIN user_accounts ua ON r.uid = ua.uid WHERE r.rid = ?", [$rid]);
                      $row = $r->fetch();
                    ?>
                    <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="POST" data-parsley-validate id="frmList">
                      <div class="row">
                        <div class="col-md-12 col-xs-12">
                          <div class="row">
                            <div class="col-md-2">
                              <label>Request No.:</label>
                              <input type="text" class="form-control" value="<?php echo $row['request_no']; ?>" readonly>
                            </div>
                            <div class="col-md-2">
                              <label>Purchase Order No.:</label>
                              <input type="text" class="form-control" name="po_number" placeholder="Please enter purchase order number" required>
                            </div>
                            <div class="col-md-3">
                              <label>Supplier Name: </label>
                              <input type="text" class="form-control" name="supplier_name" placeholder="Please enter supplier name" required>
                            </div>
                            <div class="col-md-5">
                              <label>Supplier Address: </label>
                              <input type="text" class="form-control" name="supplier_address" placeholder="Please enter supplier address" required>
                            </div>
                          </div>
                          <hr/>
                          <div class="row">
                            <div class="col-md-12">
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Item Code</th>
                                    <th>Item Name/Description</th>
                                    <th>Quantity</th>
                                    <th>Unit of Measure</th>
                                    <th>Unit Cost</th>
                                    <th>Total</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $count = 1;
                                    $i = DB::run("SELECT * FROM request_items ri JOIN item_dictionary id ON ri.itemid = id.itemid WHERE ri.rid = ?", [$_GET["rid"]]);
                                    while($irow = $i->fetch()){
                                  ?>
                                  <tr>
                                    <input type="hidden" name="riid[]" value="<?php echo $irow['riid']; ?>" required>
                                    <th scope="row"><?php echo $count; ?></th>
                                    <td><?php echo $irow["item_code"]; ?></td>
                                    <td><?php echo $irow["item_name"] . " / (" . $irow["item_description"] . ")"; ?></td>
                                    <td class="rowQ_<?php echo $irow['riid']; ?>"><?php echo $irow["requested_qty"]; ?></td>
                                    <td><?php echo $irow["requested_unit"]; ?></td>
                                    <td>
                                      <input type="number" step="0.01" min="1" class="form-control rowC_<?php echo $irow['riid']; ?>" name="unit_cost[]" required data-parsley-type="number">
                                    </td>
                                    <td>
                                      <input type="text" class="form-control rowT_<?php echo $irow['riid']; ?>" name="total_cost[]" readonly value="0">
                                    </td>
                                  </tr>
                                  <?php
                                      $count++;
                                    }
                                  ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <br/>
                      <div class="modal-footer">
                        <input type="submit" name="submitOrder" value="Submit" class="btn btn-success">
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
                    <h2>List of Requests</h2>
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
                            <th width="100">Request No.</th>
                            <th width="100">Date</th>
                            <th>Type</th>
                            <th>Requested By</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th width="300">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      <div class="modal fade view_request" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">

                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmCatUpdate">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Requested Items | <span id="requested_no">RN-01</span></h4>
                              </div>
                              <div class="modal-body">
                                <table class="table table-striped" id="requestItemsContainer">
                                  <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Item Code</th>
                                      <th>Item Name/Description</th>
                                      <th>Quantity</th>
                                      <th>Unit of Measure</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    
                                  </tbody>
                                </table>
                              </div>
                              <div class="modal-footer">
                                <button class="btn btn-default" data-dismiss="modal">Close</button>
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

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>
    <script src="js/custom/list_requests.js"></script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
