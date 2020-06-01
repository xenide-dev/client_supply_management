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
                                <h1 class="m-0 text-dark">My Equipments</h1>
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
                                                    <th>Property No.</th>
                                                    <th>Name/Description</th>
                                                    <th>Qty</th>
                                                    <th>Unit</th>
                                                    <th>Date Issued</th>
                                                    <th>Status</th>
                                                    <th>Transfer Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Listing all equipments based on the user's request -->
                                                <?php
                                                    // get all request
                                                    $r = DB::run("SELECT * FROM request r JOIN request_items ri ON r.rid = ri.rid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE r.uid = ? AND id.item_type = 'Non-Consumable'", [$_SESSION["uid"]]);
                                                    while($row = $r->fetch()){
                                                        // get the transaction
                                                        $t = DB::run("SELECT * FROM supplies_equipment_transaction WHERE riid = ? ORDER BY created_at DESC", [$row["riid"]]);
                                                        if($trow = $t->fetch()){
                                                            // check if the last transaction is out
                                                            //TODOIMP: UPDATE FOR TRANSFER
                                                            if($trow["transaction_type"] == "Out"){
                                                                // check if the destination_uid is the same as the user
                                                                if($trow["destination_uid"] == $_SESSION["uid"]){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $trow["report_item_no"]; ?></td>
                                                        <td><?php echo $row["item_name"] . "(" . $row["item_description"] . ")"; ?></td>
                                                        <td><?php echo $row["requested_qty"]; ?></td>
                                                        <td><?php echo $row["requested_unit"]; ?></td>
                                                        <td><?php echo $trow["created_at"]; ?></td>
                                                        <td>
                                                            <?php
                                                                if($trow["remarks"] == "Request"){
                                                                    // check item_status (if null serviceable otherwise print text)
                                                                    if($trow["item_status"] == null){
                                                                        echo "Serviceable";
                                                                    }else{
                                                                        echo $trow["item_status"];
                                                                    }
                                                                }else{
                                                                    // description of the transfer from whom
                                                                    echo "Something";
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                if($trow["remarks"] != "Transfer"){
                                                                    echo "N/A";
                                                                }else{
                                                                    echo $trow["created_at"];
                                                                } 
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".view_equipment_history" onclick="loadEquipmentHistory(<?php echo $trow['riid']; ?>);" data-backdrop="static">View Equipment History</button>
                                                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".report_status" onclick="changeStatus(<?php echo $trow['riid']; ?>);" data-backdrop="static">Change Status</button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>

                                                <!-- Listing all equipments based on transfer -->
                                                <?php
                                                    $t = DB::run("SELECT * FROM supplies_equipment_transaction st JOIN request_items ri ON st.riid = ri.riid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE transaction_type = 'Out' AND destination_uid = ? AND remarks = 'Transfer'", [$_SESSION["uid"]]);
                                                    while($strow = $t->fetch()){
                                                        $tempText = explode("-", $strow["transaction_status"]);
                                                ?>
                                                <tr>
                                                    <td><?php echo $strow["report_item_no"]; ?></td>
                                                    <td><?php echo $strow["item_name"] . "(" . $strow["item_description"] . ")"; ?></td>
                                                    <td><?php echo $strow["requested_qty"]; ?></td>
                                                    <td><?php echo $strow["requested_unit"]; ?></td>
                                                    <td><?php echo $strow["created_at"]; ?></td>
                                                    <td>
                                                        <?php
                                                            echo "Transferred from " . $tempText[2];
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            echo $strow["created_at"];
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".view_equipment_history" onclick="loadEquipmentHistory(<?php echo $strow['riid']; ?>);" data-backdrop="static">View Equipment History</button>
                                                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".report_status" onclick="changeStatus(<?php echo $strow['riid']; ?>);" data-backdrop="static">Change Status</button>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="modal fade report_status" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Report Equipment's Status</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Status:</label>
                                                            <input type="hidden" id="riid" readonly>
                                                            <select class="form-control" id="status">
                                                                <option value="">-- Please select a value --</option>
                                                                <option value="Serviceable">Serviceable (Active)</option>
                                                                <option value="Disposal">Disposal</option>
                                                                <option value="Need Repair">Need Repair</option>
                                                                <option value="Lost">Lost</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary" id="btnReport">Submit</button>
                                                        <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade view_equipment_history" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Equipment's Ownership History</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
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
        <script src="_custom_assets/js/my_equipments.js"></script>
    </body>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>
