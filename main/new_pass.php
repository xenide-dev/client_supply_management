<?php
  include "connection/connection.php";
  session_start();

  if(isset($_SESSION["username"])){
    header("Location: index.php");
  }else{
    if(!isset($_SESSION["event"])){
        header("Location: login.php");
    }
  }
  $error["pass"] = false;
  if(isset($_POST["submit"])){
    // update pass
    $uid = $_SESSION["uid"];
    $new_pass = md5($_POST["new_pass"]);
    $confirm_pass = md5($_POST["confirm_pass"]);

    // check if new and confirm pass match
    if($new_pass == $confirm_pass){
        DB::run("UPDATE user_accounts SET password = ?, temp_pass = null WHERE uid = ?", [$new_pass, $uid]);

        $test = DB::run("SELECT * FROM user_accounts WHERE uid = ?", [$uid]);
        if($row = $test->fetch()){
            $_SESSION["user_type"] = $row["user_type"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["privileges"] = $row["priviledges"];

            DB::insertLog($row["uid"], "User Accessed", "None", "LOG-IN");

            // update online status
            DB::run("UPDATE user_accounts SET isOnline = 1 WHERE uid = ?", [$row["uid"]]);
            
            header("Location: index.php");
        }
    }else{
        $error["pass"] = true;
    }
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

    <title>Department of Tourism | Region V - Supply and Equipment Management System</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <div class="login_wrapper">
        <div class="animate form login_form">
          <div class="text-center">
            <img src="images/logo.png" alt="Local Government Unit of Aroroy, Masbate" width="250" height="250">
          </div>
          <section class="login_content">
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
              <h1>Hi, User</h1>
              <div class="alert alert-info">
                  Welcome to Supply and Equipment Management System <br/>
                  For additional security, Please change your password
              </div>
              <?php
                if($error["pass"]){
              ?>
              <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                Password do not match!
              </div>
              <?php
                }
              ?>
              <div>
                <input type="password" name="new_pass" class="form-control" placeholder="New Password" required="" />
              </div>
              <div>
                <input type="password" name="confirm_pass" class="form-control" placeholder="Confirm Password" required="" />
              </div>
              <div>
                <button type="submit" class="btn btn-default" href="index.html" name="submit">Submit</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1 style="margin-bottom: 0;"><i class="fa fa-users"></i> DEPARTMENT OF TOURISM</h1>
                  <h2>Region V</h2>
                  <h5>Supply and Equipment Management System</h5>
                  <p>©2020 All Rights Reserved.</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
