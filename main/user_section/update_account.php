<?php
  include "../connection/connection.php";
  session_start();

  if(!isset($_SESSION["username"])){
    header("Location: ../login.php");
  }

  // retrieve user information
  $i = DB::run("SELECT * FROM user_accounts WHERE uid = ?", [$_SESSION["uid"]]);
  $irow = $i->fetch();
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title m-0">My Account</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3>Basic Information</h3>
                                                <form data-parsley-validate id="frmUpdate">
                                                    <div class="form-group">
                                                        <label>First Name:</label>
                                                        <input type="text" id="fname" class="form-control update" required value="<?php echo $irow["fname"]; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Middle Name:</label>
                                                        <input type="text" id="mname" class="form-control update" required value="<?php echo $irow["mname"]; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Last Name:</label>
                                                        <input type="text" id="lname" class="form-control update" required value="<?php echo $irow["lname"]; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Date of Birth:</label>
                                                        <input type="date" id="birthdate" class="form-control update" required value="<?php echo $irow["birthdate"]; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Gender:</label>
                                                        <select id="gender" class="form-control update" required>
                                                            <option value="">-- Please select a value --</option>
                                                            <option value="Male" <?php echo ($irow['gender'] == 'Male' ? 'selected' : ''); ?>>Male</option>
                                                            <option value="Female" <?php echo ($irow['gender'] == 'Female' ? 'selected' : ''); ?>>Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Citizenship:</label>
                                                        <input type="text" id="citizenship" class="form-control update" required value="<?php echo $irow["citizenship"]; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Religion:</label>
                                                        <input type="text" id="religion" class="form-control update" required value="<?php echo $irow["religion"]; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Address:</label>
                                                        <input type="text" id="address" class="form-control update" required value="<?php echo $irow["address"]; ?>">
                                                    </div>
                                                    <h3>Contact Information</h3>
                                                    <div class="form-group">
                                                        <label>Mobile No.:</label>
                                                        <input type="text" id="contact_mobile" class="form-control update" data-inputmask="'mask': '9999 999 9999'" required value="<?php echo $irow["contact_mobile"]; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email Address:</label>
                                                        <input type="email" id="contact_email" class="form-control" value="<?php echo $irow["contact_email"]; ?>">
                                                    </div>
                                                </form>
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary" id="btnUpdateInfo">Save Changes</button>
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
        <!-- jquery.inputmask -->
        <script src="../../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
        <!-- Parsley -->
        <script src="../../vendors/parsleyjs/dist/parsley.min.js"></script>
        <!-- sweetalert -->
        <script src="../../vendors/sweetalert/sweetalert.min.js"></script>

        <!-- Custom Scripts -->
        <script src="_custom_assets/js/navigation.js"></script>
        <script src="_custom_assets/js/update_account.js"></script>
    </body>
</html>
