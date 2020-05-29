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
                <h3>Documents for Approval</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <!-- List of documents for approval -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Documents for Approval</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table id="dtList" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Requested By</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- For Regular Request -->
                          <?php
                            $retrieve = DB::run("SELECT t.status, t.created_at, r.request_no, r.request_type, u.fname, u.mname, u.lname, u.midinit, r.request_purpose, t.rid, t.tid, t.tracer_no, t.remarks FROM request_tracer t JOIN request r ON t.rid = r.rid JOIN user_accounts u ON t.source_uid = u.uid WHERE t.destination_uid_type = 'Regional Director'");
                            while ($row = $retrieve->fetch()) {
                              $bg_color = "";
                              if($row["status"] == "Pending"){
                                  $status = '<span class="label label-warning">' . $row["status"] . '</span>';
                                  $bg_color = "style='background-color: #FFCDBB'";
                              }elseif($row["status"] == "Approved"){
                                  $status = '<span class="label label-success">Approved</span>';
                              }elseif($row["status"] == "Disapproved"){
                                  $status = '<span class="label label-success">Disapproved</span>';
                              }elseif($row["status"] == "Delivered"){
                                  $status = '<span class="label label-success">Delivered</span>';
                              }elseif($row["status"] == "Pending Items"){
                                  $status = '<span class="label label-success">Pending Items</span>';
                              }elseif($row["status"] == "Processing"){
                                  $status = '<span class="label label-warning">Processing</span>';
                              }elseif($row["status"] == "Inspected"){
                                  $status = '<span class="label label-primary">Inspected</span>';
                              }elseif($row["status"] == "Ready"){
                                  $status = '<span class="label label-success">Ready for Issuance</span>';
                              }elseif($row["status"] == "Accepted"){
                                  $status = '<span class="label label-success">Accepted</span>';
                              }

                              $bg_color = "";
                              if($row["status"] == "Pending"){
                                  $bg_color = "style='background-color: #FFCDBB'";
                              }
                          ?>
                          <tr <?php echo $bg_color; ?>>
                            <td><?php echo DB::formatDateTime($row["created_at"]); ?></td>
                            <td><?php echo $row["request_no"]; ?></td>
                            <td>
                              <?php 
                                // check if the remarks has a Purchase Order value
                                if($row["remarks"] == "Purchase Order"){
                                  echo "Purchase Order";
                                  $request_type = "Purchase Order";
                                }else{
                                  echo $row["request_type"]; 
                                  $request_type = $row["request_type"];
                                }

                                // check if it is APP
                                $app_val = (strpos($row["request_no"], 'APP') >= 0 ? '' : 'PO');
                                if($request_type == "Purchase Order"){
                                  $app_val = "PO";
                                }
                              ?>
                            </td>
                            <td><?php echo $row["lname"] . ", " . $row["fname"] . " " . $row["midinit"]; ?></td>
                            <td><?php echo $row["request_purpose"]; ?></td>
                            <td><?php echo $status; ?></td>
                            <td>
                              <a href="#" class="btn btn-success btn-xs" onclick="loadData(<?php echo $row['rid']; ?>, 'request', '#requestItemsContainer', '<?php echo $app_val; ?>');" data-toggle="modal" data-target=".view_request"><span class="fa fa-search"></span> View Items</a>
                              <?php
                                // check if the last trace if it is for regional director
                                if($row["status"] == "Pending"){
                              ?>
                              <button type="button" class="btn btn-success btn-xs row_<?php echo $row['tid']; ?>" onclick="processAction(<?php echo $row['tid']; ?>, <?php echo $row['rid']; ?>, <?php echo $_SESSION['uid']; ?>, 'Approved', <?php echo $row['tracer_no']; ?>, '<?php echo $request_type; ?>', this);">Approved!</button>
                              <button type="button" class="btn btn-danger btn-xs row_<?php echo $row['tid']; ?>" onclick="processAction(<?php echo $row['tid']; ?>, <?php echo $row['rid']; ?>, <?php echo $_SESSION['uid']; ?>, 'Disapproved', <?php echo $row['tracer_no']; ?>, '<?php echo $request_type; ?>', this);">Disapproved!</button>
                              <?php
                                }
                              ?>
                            </td>
                          </tr>
                          <?php
                            }
                          ?>

                          <!-- For special request like transfer -->
                          <?php
                            // get all out transaction that has a transfer request
                            $t = DB::run("SELECT * FROM supplies_equipment_transaction st JOIN user_accounts ua ON st.requested_by_uid = ua.uid WHERE transaction_type = 'Out' AND transaction_status LIKE '%Transfer%'");
                            while($trow = $t->fetch()){
                          ?>
                          <tr>
                              <td><?php echo $trow["updated_at"]; ?></td>
                              <td>N/A</td>
                              <td>Transfer Request</td>
                              <td><?php echo $trow["lname"] . ", " . $trow["fname"] . " " . $trow["midinit"]; ?></td>
                              <td><?php echo $trow["transaction_reason"]; ?></td>
                              <td>
                                <?php
                                  $tempText = explode("-", $trow["transaction_status"]); 
                                  echo $tempText[3] . " Transfer to " . $tempText[2] . " (" . $tempText[1] . ")"; 
                                ?>
                              </td>
                              <td>
                                <a href="#" class="btn btn-success btn-xs" onclick="loadData(<?php echo $trow['stid']; ?>, 'transfer', '#requestItemsContainer tbody');" data-toggle="modal" data-target=".view_request"><span class="fa fa-search"></span> View Item</a>
                                <?php
                                  if(strpos($trow["transaction_status"], "Pending") !== false){
                                ?>
                                <button type="button" class="btn btn-success btn-xs row_<?php echo $trow['stid']; ?>" onclick="processTransfer(<?php echo $trow['stid']; ?>, 'Approved', this);">Approved!</button>
                                <button type="button" class="btn btn-danger btn-xs row_<?php echo $trow['stid']; ?>" onclick="processTransfer(<?php echo $trow['stid']; ?>, 'Disapproved', this);">Disapproved!</button>
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
                      <div class="modal fade view_request" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">

                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmCatUpdate">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Requested Items | <span id="requested_no">RN-01</span></h4>
                              </div>
                              <div class="modal-body" id="requestItemsContainer">
                                
                                
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
    <script src="js/custom/documents_approval.js"></script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
