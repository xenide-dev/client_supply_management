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
                                <h1 class="m-0 text-dark">
                                    <?php
                                        if(isset($_GET["rid"]) && isset($_GET["h"])){
                                            if(md5($_GET["rid"]) == $_GET["h"]){
                                                $r = DB::run("SELECT * FROM request WHERE rid = ?", [$_GET["rid"]]);
                                                $row = $r->fetch();
                                                echo "<a href='my_request.php'>My Requests </a>> " . $row["request_no"];
                                            }else{
                                                echo "My Requests ";
                                            }
                                        }else{
                                            echo "My Requests ";
                                        }
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
                        <?php
                            $flag = false;
                            if(isset($_GET["rid"]) && isset($_GET["h"])){
                                if(md5($_GET["rid"]) == $_GET["h"]){
                                    $flag = true;
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="timeline">
                                    <?php
                                        // group the tracer entry by date
                                        $g = DB::run("SELECT DATE(created_at) as createdAt FROM request_tracer WHERE rid = ? GROUP BY DATE(created_at) ORDER BY DATE(created_at) ASC", [$_GET["rid"]]);
                                        while($grow = $g->fetch()){
                                    ?>
                                    <div class="time-label">
                                        <span class="bg-warning"><?php echo $grow["createdAt"]; ?></span>
                                    </div>
                                    <?php
                                            // retrive entries by date
                                            $s = DB::run("SELECT * FROM request_tracer WHERE rid = ? AND created_at LIKE ? ORDER BY created_at ASC", [$_GET["rid"], "%" . $grow["createdAt"] . "%"]);
                                            while($srow = $s->fetch()){
                                                if($grow["createdAt"] == date("Y-m-d")){
                                                    $time = DB::time_elapsed_string($srow["created_at"]);
                                                }else{
                                                    $time = date("h:i:s a", strtotime($srow["created_at"]));
                                                }

                                                if($srow["status"] == "Pending"){
                                                    $status = '<span class="badge bg-warning">' . $srow["status"] . '</span>';
                                                }elseif($srow["status"] == "Approved"){
                                                    $status = '<span class="badge bg-success">Approved</span>';
                                                }elseif($srow["status"] == "Disapproved"){
                                                    $status = '<span class="badge bg-danger">Disapproved</span>';
                                                }elseif($srow["status"] == "Delivered"){
                                                    $status = '<span class="badge bg-success">Delivered</span>';
                                                }elseif($srow["status"] == "Pending Items"){
                                                    $status = '<span class="badge bg-success">Pending Items</span>';
                                                }elseif($srow["status"] == "Processing"){
                                                    $status = '<span class="badge bg-warning">Processing</span>';
                                                }elseif($srow["status"] == "Inspected"){
                                                    $status = '<span class="badge bg-primary">Inspected</span>';
                                                }elseif($srow["status"] == "Ready"){
                                                    $status = '<span class="badge bg-success">Ready for Issuance</span>';
                                                }elseif($srow["status"] == "Accepted"){
                                                    $status = '<span class="badge bg-success">Accepted</span>';
                                                }

                                                if($srow["status"] == "Delivered"){
                                                    $msg = "Your requested items has been delivered";
                                                    $done = true;
                                                }elseif($srow["source_uid"] == $_SESSION["uid"]){
                                                    $msg = "You submitted a request to Regional Director | The status is " . $status;
                                                }elseif($srow["destination_uid"] == $_SESSION["uid"]){
                                                    $msg = "Your requested items is ready to deliver";
                                                }else{
                                                    $msg = "Your request has been forwarded to " . $srow["destination_uid_type"] . " | The status is " . $status;
                                                }
                                    ?>
                                    <div>
                                        <i class="fas fa-user bg-green"></i>
                                        <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> <?php echo $time; ?></span>
                                        <h3 class="timeline-header no-border"><?php echo $msg; ?></h3>
                                        </div>
                                    </div>
                                    <?php
                                            }
                                        }

                                        if(!isset($done)){
                                    ?>
                                    <div>
                                        <i class="fas fa-clock bg-gray"></i>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <?php
                                }
                            }
                        ?>
                        <?php
                            if(!$flag){
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title m-0">List of Requests</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped dt-responsive dtList" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Request No.</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
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
                                                <tr>
                                                    <td><?php echo $lrow["request_no"]; ?></td>
                                                    <td><?php echo DB::formatDateTime($lrow["created_at"]); ?></td>
                                                    <td><?php echo $status; ?></td>
                                                    <td>
                                                        <button class="btn btn-primary btn-xs" onclick="loadData(<?php echo $lrow['rid']; ?>, 'request', '#requestItemsContainer tbody');" data-toggle="modal" data-target=".view_request">View Items</button>
                                                        <a href="my_request.php?rid=<?php echo $lrow['rid']; ?>&h=<?php echo md5($lrow['rid']); ?>" class="btn btn-success btn-xs">Trace</a>
                                                        <?php
                                                            // display the button while the status is pending
                                                            $c = DB::run("SELECT * FROM request_tracer WHERE rid = ? AND source_uid = ? AND status = 'Pending'", [$lrow["rid"], $_SESSION["uid"]]);
                                                            if($crow = $c->fetch()){
                                                        ?>
                                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-update" data-backdrop="static">Update</button>
                                                        <button type="button" class="btn btn-danger btn-xs">Remove</button>
                                                        <?php
                                                            }

                                                            // check if the status is ready
                                                            $st = DB::run("SELECT * FROM request_tracer WHERE rid = ? ORDER BY tracer_no DESC", [$lrow["rid"]]);
                                                            if($strow = $st->fetch()){
                                                                if($strow["status"] == "Ready"){
                                                        ?>
                                                        <button type="button" class="btn btn-primary btn-xs" onclick="processAction(<?php echo $lrow['rid']; ?>, 'delivered', this);">Delivered</button>
                                                        <?php
                                                                }
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
                        <?php
                            }
                        ?>
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
        <script src="_custom_assets/js/my_requests.js"></script>
    </body>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>
