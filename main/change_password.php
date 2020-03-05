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

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md footer_fixed">
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
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-md-offset-3 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Change Password</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php
                      if(isset($_POST["submit"])){
                        $old_pass = $_POST["old_pass"];
                        $new_pass = $_POST["new_pass"];
                        $confirm_pass = $_POST["confirm_pass"];

                        $ret = DB::run("SELECT * FROM user_accounts WHERE uid = ?", [$_SESSION["uid"]]);
                        if($row = $ret->fetch()){
                          if($row["password"] == md5($old_pass)){
                            // check match
                            if($new_pass == $confirm_pass){
                              // procede to change
                              $up = DB::run("UPDATE user_accounts SET password = ? WHERE uid = ?", [md5($new_pass), $_SESSION["uid"]]);
                              if($up->rowCount() > 0){
                                $output["status"] = "success";
                              }else{
                                $output["status"] = "failed";
                              }
                            }else{
                              $output["status"] = "do_not_match";
                            }
                          }else{
                            $output["status"] = "wrong_old_pass";
                          }
                        }

                        if(isset($output["status"])){
                          if($output["status"] == "wrong_old_pass"){
                    ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                      <strong>Failed!</strong> Old Password is incorrect
                    </div>
                    <?php
                          }elseif ($output["status"] == "do_not_match") {
                    ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                      <strong>Failed!</strong> Password do not match
                    </div>
                    <?php
                          }elseif ($output["status"] == "success") {
                    ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                      <strong>Success!</strong> Password has been changed
                    </div>
                    <?php
                          }elseif ($output["status"] == "success"){
                    ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                      <strong>Failed!</strong> There's something wrong!
                    </div>
                    <?php
                          }
                        }
                      }
                    ?>
                    
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                      <label>Old Password: </label>
                      <input type="password" name="old_pass" class="form-control">
                      <br/>
                      <label>New Password: </label>
                      <input type="password" name="new_pass" class="form-control">
                      <br/>
                      <label>Confirm Password: </label>
                      <input type="password" name="confirm_pass" class="form-control">
                      <br/>
                      <input type="submit" name="submit" value="Change Password" class="btn btn-success">
                    </form>
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
    
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
  </body>
</html>
