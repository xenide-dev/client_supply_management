<?php
  include "../connection/connection.php";
  session_start();

  if(!isset($_SESSION["username"])){
    header("Location: ../login.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>Welcome User | Department of Tourism Region V | Supply and Management System</title>

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
        <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    </head>
    <body class="hold-transition layout-top-nav">
        <div class="wrapper">
            <?php
                require_once("_modules/inc_header.php");
            ?>
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Supplies</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title m-0">List of Supplies</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped dt-responsive dtList" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Name/Description</th>
                                                    <th>Qty on Hand</th>
                                                    <th>Unit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    // get all supplies
                                                    $r = DB::run("SELECT * FROM supplies_equipment se JOIN item_dictionary id ON se.itemid = id.itemid ORDER BY item_name ASC");
                                                    while($row = $r->fetch()){
                                                        // check if the last transaction is out
                                                        if($row["item_qty"] != 0){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["item_name"] . "(" . $row["item_description"] . ")"; ?></td>
                                                    <td><?php echo $row["available_qty"]; ?></td>
                                                    <td><?php echo $row["item_unit"]; ?></td>
                                                </tr>
                                                <?php
                                                        }
                                                    }

                                                    $l = DB::run("SELECT * FROM request WHERE uid = ?", [$_SESSION["uid"]]);
                                                    while($lrow = $l->fetch()){
                                                        if($lrow["status"] == "Pending"){
                                                            $status = '<span class="badge bg-warning">' . $lrow["status"] . '</span>';
                                                        }elseif($lrow["status"] == "Pending"){
                                                            $status = '<span class="badge bg-warning">Pending</span>';
                                                        }elseif($lrow["status"] == "Approved"){
                                                            $status = '<span class="badge bg-success">Approved</span>';
                                                        }elseif($lrow["status"] == "Disapproved"){
                                                            $status = '<span class="badge bg-danger">Disapproved</span>';
                                                        }elseif($lrow["status"] == "Delivered"){
                                                            $status = '<span class="badge bg-success">Delivered</span>';
                                                        }elseif($lrow["status"] == "Pending Items"){
                                                            $status = '<span class="badge bg-success">Pending Items</span>';
                                                        }elseif($lrow["status"] == "Processing"){
                                                            $status = '<span class="badge bg-warning">Processing</span>';
                                                        }elseif($lrow["status"] == "Inspected"){
                                                            $status = '<span class="badge bg-primary">Inspected</span>';
                                                        }elseif($lrow["status"] == "Ready"){
                                                            $status = '<span class="badge bg-success">Ready for Issuance</span>';
                                                        }elseif($lrow["status"] == "Accepted"){
                                                            $status = '<span class="badge bg-success">Accepted</span>';
                                                        }
                                                ?>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="modal fade view_request" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Requested Items | <span id="requested_no">RN-01</span></h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
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

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-update">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">UNDER CONSTRUCTION</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>UNDER CONSTRUCTION</p>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
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
            </div>
            <?php
                require_once('_modules/inc_footer.php');
            ?>
        </div>

        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>
        <!-- DataTables -->
        <script src="plugins/datatables/jquery.dataTables.js"></script>
        <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
        <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <!-- Parsley -->
        <script src="plugins/parsleyjs/dist/parsley.min.js"></script>
        <!-- Select2 -->
        <script src="plugins/select2/js/select2.full.min.js"></script>
        <!-- SweetAlert -->
        <script src="plugins/sweetalert/sweetalert.min.js"></script>

        <!-- Custom Scripts -->
        <script src="_custom_assets/js/navigation.js"></script>
        <script src="_custom_assets/js/forms.js"></script>
        <script src="_custom_assets/js/tables.js"></script>
    </body>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>
