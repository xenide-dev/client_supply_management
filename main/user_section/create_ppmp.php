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
                                <h1 class="m-0 text-dark"> Create PPMP</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
                        <?php
                            if(isset($_POST["ppmp_year"])){
                                $ppmp_year = $_POST["ppmp_year"];
                                $itemid = $_POST["itemid"];
                                $requested_qty = $_POST["requested_qty"];
                                $requested_unit = $_POST["requested_unit"];
                                $item_type = $_POST["item_type"];
                                $mon_jan = $_POST["mon_jan"];
                                $mon_feb = $_POST["mon_feb"];
                                $mon_mar = $_POST["mon_mar"];
                                $mon_apr = $_POST["mon_apr"];
                                $mon_may = $_POST["mon_may"];
                                $mon_jun = $_POST["mon_jun"];
                                $mon_jul = $_POST["mon_jul"];
                                $mon_aug = $_POST["mon_aug"];
                                $mon_sep = $_POST["mon_sep"];
                                $mon_oct = $_POST["mon_oct"];
                                $mon_nov = $_POST["mon_nov"];
                                $mon_dec = $_POST["mon_dec"];

                                // check if already created
                                $c = DB::run("SELECT * FROM ppmp WHERE uid = ? AND ppmp_year = ?", [$_SESSION["uid"], $ppmp_year]);
                                if($crow = $c->fetch()){
                        ?>
                        <div class="alert alert-danger">
                            <strong>Oops!</strong> You already created your ppmp for that year
                        </div>
                        <?php
                                }else{
                                    // check total supplies and equipment
                                    $total_supplies = $total_equipment = 0;
                                    for ($i=0; $i < count($item_type); $i++) { 
                                        if($item_type[$i] == "Consumable"){
                                            $total_supplies++;
                                        }else{
                                            $total_equipment++;
                                        }
                                    }

                                    // add entry to main ppmp table
                                    $p = DB::run("INSERT INTO ppmp(uid, ppmp_year, total_supplies, total_equipments) VALUES(?, ?, ?, ?)", [$_SESSION["uid"], $ppmp_year, $total_supplies, $total_equipment]);
                                    $pid = DB::getLastInsertedID();

                                    // add all items to sub table
                                    $flag = false;
                                    for ($i=0; $i < count($itemid); $i++) {
                                        $pi = DB::run("INSERT INTO ppmp_items(pid, itemid, requested_qty, requested_unit, mon_jan, mon_feb, mon_mar, mon_apr, mon_may, mon_jun, mon_jul, mon_aug, mon_sep, mon_oct, mon_nov, mon_dec) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [$pid, intval($itemid[$i]), intval($requested_qty[$i]), $requested_unit[$i], $mon_jan[$i], $mon_feb[$i], $mon_mar[$i], $mon_apr[$i], $mon_may[$i], $mon_jun[$i], $mon_jul[$i], $mon_aug[$i], $mon_sep[$i], $mon_oct[$i], $mon_nov[$i], $mon_dec[$i]]);
                                        if($pi->rowCount() > 0){
                                            $flag = true;
                                        }
                                    }
                                    if($flag){
                        ?>
                            <div class="alert alert-success">
                                <strong>Success!</strong> Your PPMP has been submitted. Thank you!
                            </div>
                        <?php
                                    }
                                }
                            }
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title m-0">Create Form</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="frmForm">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>For the Year: </label>
                                                        <input type="number" name="ppmp_year" class="form-control" placeholder="Please enter the year" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Add Item/Equipment</label>
                                                <div class="requestItemContainer">
                                                    
                                                </div>
                                                <br/>
                                                <button type="button" class="btn btn-primary btn-xs" id="btnAddItem"><span class="fa fa-plus"></span> Add item/equipment</button>
                                            </div>
                                            <hr/>
                                            <?php
                                                // check if open
                                                $e = DB::run("SELECT * FROM event_activities WHERE e_name = 'ppmp_schedule'");
                                                if($erow = $e->fetch()){
                                                    $evalues = json_decode($erow["e_attributes"]);
                                            ?>
                                            <p class="text-danger">Deadline: <?php echo $evalues->toDate; ?></p>
                                            <input type="submit" class="btn btn-primary btn-sm" name="frmsubmit" value="Submit Request"> 
                                            <?php
                                                }else{
                                            ?>
                                            <p class="text-danger">Oops! Submission is still closed</p>
                                            <?php
                                                }
                                            ?>
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
        <script src="_custom_assets/js/create_ppmp.js"></script>
    </body>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>
