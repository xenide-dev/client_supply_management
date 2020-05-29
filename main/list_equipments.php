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
              <!-- Supplies List -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div id="loading_modal">
                    <div id="loading-circle"></div>
                  </div>
                  <div class="x_title">
                    <h2>List of Equipments</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table id="dtList" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Property No.</th>
                                <th>Name/Description</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Date Issued</th>
                                <th>Issued to</th>
                                <th>Status</th>
                                <th>Transfer Date</th>
                                <th width="250">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="modal fade view_equipment_history" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                          <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmHistory">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title">Equipment's Ownership History</h4>
                            </div>
                            <div class="modal-body">
                              <table class="table table-striped" id="equipmentHistoryContainer">
                                  <thead>
                                      <tr>
                                          <th width="200">Date Issued</th>
                                          <th>Name</th>
                                          <th width="300">Acquisition Type</th>
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
                    <div class="modal fade qr_codes" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">

                          <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmQrCode">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title">QR Codes</h4>
                            </div>
                            <div class="modal-body">
                              <div class="alert alert-warning">
                                <strong>Note!</strong> To copy or save the QR Code, just right click the image then 'Copy Image' or 'Save Image As'
                              </div>
                              <div id="qrContainer">
                                <label>QR Code: asd</label>
                                <img src="" alt="">
                              </div> 
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                    <div class="modal fade transfer_equipment" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">

                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmTransfer">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Transfer Equipment</h4>
                              </div>
                              <div class="modal-body">
                                <input type="hidden" id="riid" readonly>
                                <input type="hidden" id="stid" readonly>
                                <input type="hidden" id="from_uid" readonly>
                                <div class="form-group">
                                  <label>Transfer Type:</label>
                                  <select id="transfer_type" class="form-control" required>
                                    <option value="">-- Please select a value --</option>
                                    <option value="Donation">Donation</option>
                                    <option value="Reassignment">Reassignment</option>
                                    <option value="Relocate">Relocate</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label>Transfer To:</label>
                                  <select id="transfer_to" class="form-control" required>
                                    <option value="">-- Please select a value --</option>
                                    <?php
                                      $s = DB::run("SELECT * FROM user_accounts ORDER BY lname ASC");
                                      while($srow = $s->fetch()){
                                    ?>
                                    <option value="<?php echo $srow['uid']; ?>"><?php echo $srow["lname"] . ", " . $srow["fname"] . " " . $srow["midinit"] . "." . " (" . $srow["user_type"] . ")"; ?></option>
                                    <?php
                                      }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label>Reason to transfer: </label>
                                  <input type="text" id="transfer_purpose" class="form-control" placeholder="Please enter a text" required>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="btnTransfer">Transfer</button>
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
    <!-- select2 -->
    <script src="../vendors/select2/js/select2.full.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>
    <script src="js/custom/list_equipments.js"></script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
