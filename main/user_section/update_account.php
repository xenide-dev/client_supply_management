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
                                <h1 class="m-0 text-dark"> My Account</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-widget widget-user">
                                    <div class="widget-user-header bg-primary">
                                        <h3 class="widget-user-username"><?php echo $_SESSION["full_name"]; ?></h3>
                                        <h5 class="widget-user-desc"><?php echo $_SESSION["user_type"]; ?></h5>
                                    </div>
                                    <div class="widget-user-image">
                                        <img class="img-circle elevation-2" src="dist/img/user.png" alt="User Avatar">
                                    </div>
                                    <?php
                                        // get user request
                                        $r = DB::run("SELECT COUNT(*) as total FROM request WHERE uid = ?", [$_SESSION["uid"]]);
                                        $rrow = $r->fetch();

                                        // get equipment
                                        $e = DB::run("SELECT COUNT(*) as total FROM supplies_equipment_transaction st JOIN request_items ri ON st.riid = ri.riid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE st.transaction_type = 'Out' AND id.item_type = 'Non-Consumable' AND st.destination_uid = ? AND updated_at IS NULL", [$_SESSION["uid"]]);
                                        $erow = $e->fetch();
                                    ?>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-sm-6 border-right">
                                                <div class="description-block">
                                                <h5 class="description-header"><?php echo $rrow['total']; ?></h5>
                                                <span class="description-text">Total Request</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 border-right">
                                                <div class="description-block">
                                                <h5 class="description-header"><?php echo $erow['total']; ?></h5>
                                                <span class="description-text">Total Equipment</span>
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

        <!-- Custom Scripts -->
        <script src="_custom_assets/js/navigation.js"></script>
    </body>
</html>
