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
                if(isset($_GET["type"]) && isset($_GET["h"])){
                  if($_GET["type"] == "create" && $_GET["h"] != ""){
                    if(md5('h') == $_GET["h"]){


                      // handle submission
                      if(isset($_POST["app_year"])){
                        $app_year = $_POST["app_year"];
                        $request_purpose = $_POST["request_purpose"];
                        $itemid = $_POST["itemid"];
                        $requested_qty = $_POST["requested_qty"];
                        $requested_unit = $_POST["requested_unit"];
                        
                        // check if already exist
                        $c = DB::run("SELECT * FROM app WHERE app_year = ?", [$app_year]);
                        if($c->fetch()){
              ?>
              <div class="alert alert-danger">
                <strong>Error!</strong> APP for the year <?php echo $app_year; ?> is already exist!
              </div>
              <?php
                        }else{
                          $total_supplies = 0;
                          $total_equipments = 0;
                          // get total supplies and equipments
                          for ($i=0; $i < count($itemid); $i++) { 
                            $t = DB::run("SELECT * FROM item_dictionary WHERE itemid = ?", [$itemid[$i]]);
                            $trow = $t->fetch();
                            if($trow["item_type"] == "Consumable"){
                              $total_supplies++;
                            }else{
                              $total_equipments++;
                            }
                          }

                          // add entries to main app table
                          $a = DB::run("INSERT INTO app(uid, app_year, total_supplies, total_equipments) VALUES(?, ?, ?, ?)", [$_SESSION["uid"], $app_year, $total_supplies, $total_equipments]);
                          $aid = DB::getLastInsertedID();
                          if($a->rowCount() > 0){
                            // add to sub table
                            for ($i=0; $i < count($itemid); $i++) {
                              DB::run("INSERT INTO app_items(aid, itemid, requested_qty, requested_unit) VALUES(?, ?, ?, ?)", [$aid, $itemid[$i], $requested_qty[$i], $requested_unit[$i]]);
                            }
                          }
                          
                          // create a purchase request
                          $p = DB::run("INSERT INTO request(uid, request_no, request_type, request_purpose, total_supplies_requested, total_equipments_requested) VALUES(?, ?, ?, ?, ?, ?)", [$_SESSION["uid"], "APP-" . $app_year , "Purchase Request", $request_purpose, $total_supplies, $total_equipments]);
                          $rid = DB::getLastInsertedID();
                          // save all items
                          for ($i=0; $i < count($itemid); $i++) { 
                            DB::run("INSERT INTO request_items(rid, itemid, requested_qty, requested_unit) VALUES(?, ?, ?, ?)", [$rid, $itemid[$i], $requested_qty[$i], $requested_unit[$i]]);
                          }

                          // create trace entry
                          DB::insertTraceEntry(1, $rid, $_SESSION["uid"], "Regional Director", null, "Pending", null);
              ?>
              <div class="alert alert-success">
                <strong>Success!</strong> APP has been submitted. Thank you!
              </div>
              <?php
                        }
                      }
              ?>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div id="loading_modal">
                      <div id="loading-circle"></div>
                    </div>
                    <div class="x_title">
                      <h2>Create APP</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" data-parsley-validate method="POST" id="frmList">
                        <div class="row">
                          <div class="col-md-5 col-sm-12 col-xs-12">
                            <div class="form-group">
                              <label>Select year:</label>
                              <select name="app_year" class="form-control" id="app_year">
                                <option value="">-- Please select a value --</option>
                                <?php
                                  $a = DB::run("SELECT ppmp_year FROM ppmp GROUP BY ppmp_year ORDER BY ppmp_year ASC");
                                  while($arow = $a->fetch()){
                                ?>
                                <option value="<?php echo $arow['ppmp_year']; ?>"><?php echo $arow['ppmp_year']; ?></option>
                                <?php
                                  }
                                ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-1 col-sm-12 col-xs-12">
                            <div class="form-group">
                              <label>&nbsp;</label>
                              <button type="button" class="btn btn-primary" onclick="loadConsolidated()">Consolidate</button>
                            </div>
                          </div>
                          <div class="col-md-6 col-sm-12 col-xs-12"> 
                            <div class="form-group">
                              <label>Purpose:</label>
                              <input type="text" class="form-control" name="request_purpose">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="table-responsive">
                            <table class="table table-striped jambo_table" id="tblConsolidated">
                              <thead>
                                <tr>
                                  <th></th>
                                  <th>Item Name / Description</th>
                                  <th>Item Quantity</th>
                                  <th>Item Unit</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-1 col-sm-12 col-xs-12">
                            <div class="form-group">
                              <button type="submit" name="frmSubmit" class="btn btn-primary">Submit</button>
                            </div>
                          </div>
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
              <div class="alert alert-danger">
                  <strong>Oops!</strong> Looks like you manage to change the url. Please go back or choose one of the navigation links on the left
              </div>
              <?php
                  }
                }else{

                  if(isset($_POST["btn_schedule"])){
                    $frmDate = $_POST["frmDate"];
                    $toDate = $_POST["toDate"];

                    $values["frmDate"] = $frmDate;
                    $values["toDate"] = $toDate;

                    // check if schedule event is set
                    $s = DB::run("SELECT * FROM event_activities WHERE e_name = ?", ["ppmp_schedule"]);
                    if($srow = $s->fetch()){
                      // just update
                      $u = DB::run("UPDATE event_activities SET e_attributes = ? WHERE e_name = ?", [json_encode($values), "ppmp_schedule"]);
                    }else{
                      // create
                      $c = DB::run("INSERT INTO event_activities(e_name, e_attributes) VALUES(?, ?)", ["ppmp_schedule", json_encode($values)]);
                    }

              ?>
              <div class="alert alert-success">
                  <strong>Success!</strong> Date of submission has been set 
              </div>
              <?php
                  }

                  if(isset($_POST["close_schedule"])){
                    $d = DB::run("DELETE FROM event_activities WHERE e_name = 'ppmp_schedule'");
                    if($d->rowCount() > 0){
              ?>
              <div class="alert alert-success">
                  <strong>Success!</strong> Date of submission has been cleared 
              </div>
              <?php
                    }
                  }

                  $e = DB::run("SELECT * FROM event_activities WHERE e_name = ?", ["ppmp_schedule"]);
                  $erow = $e->fetch();
                  $evalues = (isset($erow["e_attributes"]) ? json_decode($erow["e_attributes"]) : null);
              ?>
              <!-- PPMPs List -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div id="loading_modal">
                    <div id="loading-circle"></div>
                  </div>
                  <div class="x_title">
                    <h2>List of All Submitted PPMPs</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <p>PPMPs submission date: <b><?php echo ($evalues == null) ? 'n/a' : $evalues->frmDate . " to " . $evalues->toDate; ?></b></p>
                     <table id="dtList" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>For the Year</th>
                                <th>Employee Name</th>
                                <th>Total Supplies</th>
                                <th>Total Equipments</th>
                                <th width="250">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <a href="list_ppmp.php?type=create&h=<?php echo md5('h'); ?>" class="btn btn-primary btn-xs"><span class="fa fa-plus"></span> Create APP</a>
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".set_schedule" data-backdrop="static"><span class="fa fa-calendar"></span> Set Schedule</button>
                    <?php
                      if(isset($erow["e_attributes"])){
                    ?>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" style="display: inline;">
                      <button type="submit" class="btn btn-danger btn-xs" name="close_schedule"><span class="fa fa-times"></span> Delete the schedule</button>
                    </form>
                    <?php
                      }
                    ?>
                    <div class="modal fade ppmp_items" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmTransfer">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title">QR Codes</h4>
                                    </div>
                                    <div class="modal-body">
                                        <table id="dtListItems" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Item Name/Description</th>
                                                    <th>Quantity (Unit)</th>
                                                    <th>Jan.</th>
                                                    <th>Feb.</th>
                                                    <th>Mar.</th>
                                                    <th>Apr.</th>
                                                    <th>May</th>
                                                    <th>Jun.</th>
                                                    <th>Jul.</th>
                                                    <th>Aug.</th>
                                                    <th>Sep.</th>
                                                    <th>Oct.</th>
                                                    <th>Nov.</th>
                                                    <th>Dec.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade set_schedule" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmTransfer">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title">Set Schedule for ppmp submission</h4>
                                    </div>
                                    <div class="modal-body">
                                        <label>Select date for opening:</label>
                                        <input type="date" min="<?php echo date("Y-m-d"); ?>" class="form-control" id="frmDate" name="frmDate" required>
                                        <br/>
                                        <label>Select date for closing:</label>
                                        <input type="date" min="<?php echo date ('Y-m-d' , strtotime(date("Y-m-d") . ' + 1 day' )); ?>" class="form-control" id="toDate" name="toDate" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="btn_schedule">Save</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>


              <!-- APP List -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="loading_modal">
                    <div class="loading-circle"></div>
                  </div>
                  <div class="x_title">
                    <h2>List of All APPs</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table id="dtAPP" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>For the Year</th>
                                <th>Total Supplies</th>
                                <th>Total Equipments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="modal fade app_items" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmTransfer">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title">QR Codes</h4>
                                    </div>
                                    <div class="modal-body">
                                        <table id="dtAPPItems" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Item Name/Description</th>
                                                    <th>Quantity (Unit)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
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
    <!-- <script src="../vendors/parsleyjs/dist/parsley.min.js"></script> -->
    <!-- sweetalert -->
    <script src="../vendors/sweetalert/sweetalert.min.js"></script>
    <!-- select2 -->
    <script src="../vendors/select2/js/select2.full.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>
    <script src="js/custom/list_ppmp.js"></script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
