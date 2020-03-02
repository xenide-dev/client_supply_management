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
                                <h1 class="m-0 text-dark"> Create Purchase Request</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
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
                            <strong>Success!</strong> Your request has been submitted. Thank you!
                        </div>
                        <?php
                                }
                            }
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title m-0">Create Form</h5>
                                    </div>
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
                                    <div class="card-body">
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="frmForm" class="frmPurchase">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Request No.: </label>
                                                        <input type="text" class="form-control" name="request_no" placeholder="Request Number" value="<?php echo $rnno; ?>">
                                                    </div>
                                                    <div class="col-md-8">
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
                                            <hr/>
                                            <input type="submit" class="btn btn-primary btn-sm" name="frmsubmit" value="Submit Request">
                                        </form>
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
        <!-- Parsley -->
        <script src="plugins/parsleyjs/dist/parsley.min.js"></script>
        <!-- Select2 -->
        <script src="plugins/select2/js/select2.full.min.js"></script>
        <!-- SweetAlert -->
        <script src="plugins/sweetalert/sweetalert.min.js"></script>

        <!-- Custom Scripts -->
        <script src="_custom_assets/js/navigation.js"></script>
        <script src="_custom_assets/js/forms.js"></script>
        <script src="_custom_assets/js/create_request.js"></script>
    </body>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>
