<?php
  include "connection/connection.php";
  session_start();

  // TODOIMP close the ppmp schedule if the current date is greater than the deadline


  if(isset($_SESSION["event"])){
    if($_SESSION["event"] == "change_pass"){
      header("Location: new_pass.php");
    }
  }
  if(isset($_SESSION["username"])){
    header("Location: index.php");
  }
  $error = false;
  if(isset($_POST["submit"])){
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    $test = DB::run("SELECT * FROM user_accounts WHERE username = ? AND password = ?", [$username, $password]);
    if($row = $test->fetch()){
      $_SESSION["uid"] = $row["uid"];
      $_SESSION["user_type"] = $row["user_type"];
      $_SESSION["full_name"] = $row["fname"] . " " . $row["midinit"] . "." . " " . $row["lname"];
      
      // check if new acct
      if($row["temp_pass"] != null){
        $_SESSION["event"] = "change_pass";

        DB::insertLog($row["uid"], "Redirect", "Redirect user to 'change password' page", "CHANGE PASS");
        header("Location: new_pass.php");
      }else{
        $_SESSION["username"] = $row["username"];
        $_SESSION["privileges"] = $row["priviledges"];
        
        DB::insertLog($row["uid"], "User Accessed", "None", "LOG-IN");
        // update online status
        DB::run("UPDATE user_accounts SET isOnline = 1 WHERE uid = ?", [$row["uid"]]);
        
        if($_SESSION["user_type"] == "User"){
          header("Location: user_section/index.php");
        }else{
          header("Location: index.php");
        }
      }
    }else{
      $error = true;
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
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate>
              <h1>Welcome User</h1>
              <?php
                if($error){
              ?>
              <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                Account doesn't exist!
              </div>
              <?php
                }
              ?>
              <div>
                <input type="text" name="username" class="form-control" placeholder="Username" data-parsley-required />
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" data-parsley-required />
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
  
  <!-- jQuery -->
  <script src="../vendors/jquery/dist/jquery.min.js"></script>
  <!-- Parsley -->
  <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
