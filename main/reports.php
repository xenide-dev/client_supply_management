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
                <h3>Report Management</h3>
              </div>
            </div>

            <div class="clearfix"></div>


            <div class="row">
              <!-- Settings -->
              <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Settings</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                        <div class="form-group">
                            <label>Select Month:</label>
                            <select name="report_month" class="form-control" required>
                                <option value="">-- Please select a value --</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Year:</label>
                            <select name="report_year" class="form-control" required>
                                <option value="">-- Please select a value --</option>
                                <?php
                                    for ($i=date("Y"); $i > 2015 ; $i--) { 
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type of Report:</label>
                            <select name="report_type" class="form-control" required>
                                <option value="">-- Please select a value --</option>
                                <option value="purchase">Purchase Request</option>
                                <option value="requisition">Requisition</option>
                                <option value="inspection">Inspection and Acceptance</option>
                                <option value="par">Property Acknowledgement Receipt</option>
                                <option value="ics">Inventory Custodian Slip</option>
                            </select>
                        </div>
                        <div class="form-group">
                          <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
              
              <?php 
                if(isset($_POST["btnSubmit"])){
                  $report_type = $_POST["report_type"];
                  $report_month = $_POST["report_month"];
                  $report_year = $_POST["report_year"];
                  $qDate = $report_year . "-" . $report_month;
              
              ?>
              <!-- Results -->
              <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Results</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <h4>
                      Month: 
                      <b>
                        <?php
                          $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]; 
                          echo $months[intval($report_month) - 1];
                        ?>
                      </b>
                    </h4>
                    <h4>Year: <b><?php echo $report_year; ?></b></h4>
                    <h4>
                      Type of Report: 
                      <b>
                        <?php 
                          if($report_type == "purchase"){
                            $finalType = "Purchase Request";
                          }elseif($report_type == "requisition"){
                            $finalType = "Requisition";
                          }elseif($report_type == "inspection"){
                            $finalType = "Inspection and Acceptance";
                          }elseif($report_type == "par"){
                            $finalType = "Property Acknowledgement Receipt";
                          }elseif($report_type == "ics"){
                            $finalType = "Inventory Custodian Slip";
                          }
                          echo $finalType; 
                        ?>
                      </b>
                    </h4>
                    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>">
              <?php
                    if($report_type == "purchase" || $report_type == "requisition"){
                      if($report_type == "purchase"){
                        $request_type = "Purchase Request";
                      }elseif($report_type == "requisition"){
                        $request_type = "Requisition";
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
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $hasResult = false;
                            $r = DB::run("SELECT * FROM request r JOIN user_accounts ua ON r.uid = ua.uid WHERE r.request_type = ? AND r.created_at LIKE ?", [$request_type, "%" . $qDate . "%"]);
                            while($rrow = $r->fetch()){
                              $hasResult = true;
                          ?>
                          <tr>
                            <td><?php echo $rrow["request_no"]; ?></td>
                            <td><?php echo $rrow["created_at"]; ?></td>
                            <td><?php echo $rrow["request_type"]; ?></td>
                            <td><?php echo $rrow["lname"] . ", " . $rrow["fname"] . " " . $rrow["midinit"]; ?></td>
                            <td><?php echo $rrow["request_purpose"]; ?></td>
                          </tr>
                          <?php
                            }
                          ?>
                        </tbody>
                      </table>
              <?php
                      if($hasResult){
              ?>
                      <a href="modules/pdf_generator/generate_all_purchase_request.php?m=<?php echo $report_month; ?>&y=<?php echo $report_year; ?>&type=<?php echo $report_type; ?>&h=<?php echo md5($report_month.$report_year.$report_type); ?>" class="btn btn-primary btn-sm" target="_blank">Generate PDF Format</a>
              <?php
                      }
                    }elseif($report_type == "inspection"){
              ?>
                      <table id="dtList" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th width="100">PO No.</th>
                            <th width="100">Date</th>
                            <th>Supplier Name</th>
                            <th>Supplier Address</th>
                            <th>Total Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $hasResult = false;
                            $p = DB::run("SELECT * FROM purchase_order WHERE created_at LIKE ?", ["%" . $qDate . "%"]);
                            while($prow = $p->fetch()){
                              $hasResult = true;
                          ?>
                          <tr>
                            <td><?php echo $prow["po_number"]; ?></td>
                            <td><?php echo $prow["created_at"]; ?></td>
                            <td><?php echo $prow["supplier_name"]; ?></td>
                            <td><?php echo $prow["supplier_address"]; ?></td>
                            <td><?php echo $prow["total_amount"]; ?></td>
                          </tr>
                          <?php
                            }
                          ?>
                        </tbody>
                      </table>
              <?php
                      if($hasResult){
              ?>
                      <a href="modules/pdf_generator/generate_all_inspection.php?m=<?php echo $report_month; ?>&y=<?php echo $report_year; ?>&type=<?php echo "inspection"; ?>&h=<?php echo md5($report_month.$report_year."inspection"); ?>" class="btn btn-primary btn-sm" target="_blank">Generate PDF Format</a>
              <?php
                      }
                    }elseif($report_type == "par"){
              ?>
                      <table id="dtList" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th width="100">Request No.</th>
                            <th width="100">Date</th>
                            <th>Type</th>
                            <th>Requested By</th>
                            <th>Purpose</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $hasResult = false;
                            $r = DB::run("SELECT * FROM request r JOIN user_accounts ua ON r.uid = ua.uid WHERE r.created_at LIKE ? AND r.ics_par LIKE ?", ["%" . $qDate . "%", "%par%"]);
                            while($rrow = $r->fetch()){
                              $hasResult = true;
                          ?>
                          <tr>
                            <td><?php echo $rrow["request_no"]; ?></td>
                            <td><?php echo $rrow["created_at"]; ?></td>
                            <td><?php echo $rrow["request_type"]; ?></td>
                            <td><?php echo $rrow["lname"] . ", " . $rrow["fname"] . " " . $rrow["midinit"]; ?></td>
                            <td><?php echo $rrow["request_purpose"]; ?></td>
                          </tr>
                          <?php
                            }
                          ?>
                        </tbody>
                      </table>
              <?php
                      if($hasResult){
              ?>
                      <a href="modules/pdf_generator/generate_all_par_ics.php?m=<?php echo $report_month; ?>&y=<?php echo $report_year; ?>&type=par&h=<?php echo md5($report_month.$report_year.'par'); ?>" class="btn btn-primary btn-sm" target="_blank">Generate PDF Format</a>
              <?php
                      }
                    }elseif($report_type == "ics"){
              ?>
                      <table id="dtList" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th width="100">Request No.</th>
                            <th width="100">Date</th>
                            <th>Type</th>
                            <th>Requested By</th>
                            <th>Purpose</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $hasResult = false;
                            $r = DB::run("SELECT * FROM request r JOIN user_accounts ua ON r.uid = ua.uid WHERE r.created_at LIKE ? AND r.ics_par LIKE ?", ["%" . $qDate . "%", "%ics%"]);
                            while($rrow = $r->fetch()){
                              $hasResult = true;
                          ?>
                          <tr>
                            <td><?php echo $rrow["request_no"]; ?></td>
                            <td><?php echo $rrow["created_at"]; ?></td>
                            <td><?php echo $rrow["request_type"]; ?></td>
                            <td><?php echo $rrow["lname"] . ", " . $rrow["fname"] . " " . $rrow["midinit"]; ?></td>
                            <td><?php echo $rrow["request_purpose"]; ?></td>
                          </tr>
                          <?php
                            }
                          ?>
                        </tbody>
                      </table>
              <?php
                      if($hasResult){
              ?>
                      <a href="modules/pdf_generator/generate_all_par_ics.php?m=<?php echo $report_month; ?>&y=<?php echo $report_year; ?>&type=ics&h=<?php echo md5($report_month.$report_year.'ics'); ?>" class="btn btn-primary btn-sm" target="_blank">Generate PDF Format</a>
              <?php
                      }
                    }
              ?>
                    </form>
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
    <script src="js/custom/generate_reports.js"></script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
