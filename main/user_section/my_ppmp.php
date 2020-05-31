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
                                <h1 class="m-0 text-dark">My PPMP</h1>
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
                                        <h5 class="card-title m-0">List of Equipments</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped dt-responsive dtList" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Date</th>
                                                    <th>Total Supplies</th>
                                                    <th>Total Equipments</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    // get all ppmp
                                                    $p = DB::run("SELECT * FROM ppmp WHERE uid = ? ORDER BY ppmp_year ASC", [$_SESSION["uid"]]);
                                                    while($prow = $p->fetch()){
                                                ?>
                                                <tr>
                                                    <td><?php echo $prow["ppmp_year"]; ?></td>
                                                    <td><?php echo DB::formatDateTime($prow["created_at"]); ?></td>
                                                    <td><?php echo $prow["total_supplies"]; ?></td>
                                                    <td><?php echo $prow["total_equipments"]; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".view_ppmp" data-backdrop="static" onclick="loadData(<?php echo $prow['pid']; ?>, <?php echo $_SESSION['uid']; ?>)">View Items</button>
                                                        <!-- <button class="btn btn-success btn-xs">Update</button>
                                                        <button class="btn btn-danger btn-xs">Delete</button> -->
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="modal fade view_ppmp" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">PPMP Items</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped" id="ppmpItemsContainer">
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
                                                        <button class="btn btn-default" data-dismiss="modal">Close</button>
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
        <script src="_custom_assets/js/my_ppmp.js"></script>
    </body>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>
