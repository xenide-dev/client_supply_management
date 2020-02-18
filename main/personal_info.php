<?php
  include "connection/connection.php";
  session_start();
  if(!isset($_SESSION["username"])){
    header("Location: login.php");
  }


  if(isset($_GET["employeeid"])){
    if($_GET["employeeid"] != ""){
      $employeeid = $_GET["employeeid"];

      // get full name
      $r = DB::run("SELECT * FROM employee WHERE employeeid = ?", [$employeeid]);
      if($row = $r->fetch()){
        $fullname = $row["lname"] . ", " . $row["fname"] . " " . $row["midinit"];
      }
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

    <title>LGU Aroroy - Payroll Management System | Welcome "<?php echo $_SESSION["username"];?>"</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <!-- PNotify -->
    <link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
    <!-- Cropper -->
    <link href="../vendors/cropper/dist/cropper.min.css" rel="stylesheet">


    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
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
                <h3>Update Information <?php echo (isset($fullname) ? "(" . $fullname . ")" : ""); ?></h3>
              </div>
              <?php
                if(isset($_GET["employeeid"]) ){
              ?>
              <div class="title_right">
                <div class="col-md-8 col-sm-8 col-xs-12 form-group pull-right">
                  <a href="employee.php" class="btn btn-info"><span class="fa fa-arrow-left"></span> Go Back (Employee)</a><a href="family_background.php?employeeid=<?php echo $_GET["employeeid"];?>" class="btn btn-success"><span class="fa fa-arrow-right"></span> Next (Family Background)</a>
                </div>
              </div>
              <?php
                }
              ?>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Personal Information</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php
                      if(isset($_POST["submit"])){
                        $lname = strtoupper($_POST["lname"]);
                        $fname = strtoupper($_POST["fname"]);
                        $midname = strtoupper($_POST["mname"]);
                        $name_ext = strtoupper($_POST["name_ext"]);
                        $midinit = strtoupper(substr($_POST["mname"], 0, 1) . ".");
                        if($_POST["birthdate"] == ''){
                          $birthdate = null;
                        }else{
                          $birthdate = $_POST["birthdate"];
                        }
                        $birthplace = strtoupper($_POST["birthplace"]);
                        $gender = $_POST["gender"];
                        $civilstatus = $_POST["civilstatus"];
                        $height = ($_POST["height"] == '' ? null : $_POST["height"]);
                        $weight = ($_POST["weight"] == '' ? null : $_POST["weight"]);
                        $bloodtype = strtoupper($_POST["bloodtype"]);
                        $gsisno = strtoupper($_POST["gsisno"]);
                        $pagibigno = $_POST["pagibigno"];
                        $philhealthno = $_POST["philhealthno"];
                        $sssno = $_POST["sssno"];
                        $tinno = $_POST["tinno"];
                        $agencyemployeeno = strtoupper($_POST["agencyemployeeno"]);
                        $citizenship = strtoupper($_POST["citizenship"]);
                        $residentialaddr1 = strtoupper($_POST["residentialaddr1"]);
                        $residentialaddr2 = strtoupper($_POST["residentialaddr2"]);
                        $residentialaddr3 = strtoupper($_POST["residentialaddr3"]);
                        $reszipcode = $_POST["reszipcode"];
                        $permanentaddr1 = strtoupper($_POST["permanentaddr1"]);
                        $permanentaddr2 = strtoupper($_POST["permanentaddr2"]);
                        $permanentaddr3 = strtoupper($_POST["permanentaddr3"]);
                        $permzipcode = $_POST["permzipcode"];
                        $telno = $_POST["telno"];
                        $mobileno = $_POST["mobileno"];
                        $emailaddr = $_POST["emailaddr"];

                        // profile picture
                        if($_POST["profile_pic"] == ''){
                          $base64 = $_POST["base64"];
                          $basePHP = explode(',', $base64);
                          $data = base64_decode($basePHP[1]);
                          $filepath = 'profile_pictures/' . md5($fname . $midname . $lname) . '.png';
                          file_put_contents($filepath, $data);
                        }else{
                          $filepath = $_POST["profile_pic"];
                        }

                        $up  = DB::run("UPDATE employee SET lname = ?, fname = ?, midname = ?, name_ext = ?, midinit = ?, birthdate = ?, birthplace = ?, gender = ?, civilstatus = ?, height = ?, weight = ?, bloodtype = ?, gsisno = ?, pagibigno = ?, philhealthno = ?, sssno = ?, tinno = ?, agencyemployeeno = ?, citizenship = ?, residentialaddr1 = ?, residentialaddr2 = ?, residentialaddr3 = ?, reszipcode = ?, permanentaddr1 = ?, permanentaddr2 = ?, permanentaddr3 = ?, permzipcode = ?, telno = ?, mobileno = ?, emailaddr = ?, profile_pic = ? WHERE employeeid = ?", [$lname, $fname, $midname, $name_ext, $midinit, $birthdate, $birthplace, $gender, $civilstatus, $height, $weight, $bloodtype, $gsisno, $pagibigno, $philhealthno, $sssno, $tinno, $agencyemployeeno, $citizenship, $residentialaddr1, $residentialaddr2, $residentialaddr3, $reszipcode, $permanentaddr1, $permanentaddr2, $permanentaddr3, $permzipcode, $telno, $mobileno, $emailaddr, $filepath, (isset($employeeid)  ? $employeeid : $_SESSION["employeeid"])]);

                        if($up->rowCount() > 0){
                    ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                      <strong>Success!</strong> Data has been updated
                    </div>
                    <?php
                        }
                      }
                    ?>
                    <?php
                      // retrieve employee personal info
                      $ret = DB::run("SELECT * FROM employee WHERE employeeid = ?", [(isset($employeeid) ? $employeeid : $_SESSION["employeeid"])]);
                      if($row = $ret->fetch()){
                        $lname = $row["lname"];
                        $fname = $row["fname"];
                        $mname = $row["midname"];
                        $name_ext = $row["name_ext"];
                        $birthdate = $row["birthdate"];
                        $birthplace = $row["birthplace"];
                        $gender = $row["gender"];
                        $civilstatus = $row["civilstatus"];
                        $height = $row["height"];
                        $weight = $row["weight"];
                        $bloodtype = $row["bloodtype"];
                        $gsisno = $row["gsisno"];
                        $pagibigno = $row["pagibigno"];
                        $philhealthno = $row["philhealthno"];
                        $sssno = $row["sssno"];
                        $tinno = $row["tinno"];
                        $agencyemployeeno = $row["agencyemployeeno"];
                        $citizenship = $row["citizenship"];

                        $residentialaddr1 = $row["residentialaddr1"];
                        $residentialaddr2 = $row["residentialaddr2"];
                        $residentialaddr3 = $row["residentialaddr3"];

                        $reszipcode = $row["reszipcode"];

                        $permanentaddr1 = $row["permanentaddr1"];
                        $permanentaddr2 = $row["permanentaddr2"];
                        $permanentaddr3 = $row["permanentaddr3"];

                        $permzipcode = $row["permzipcode"];

                        $telno = $row["telno"];
                        $mobileno = $row["mobileno"];
                        $emailaddr = $row["emailaddr"];
                        $profile_pic = $row["profile_pic"];
                      }
                    ?>
                    <form action="<?php echo basename($_SERVER['REQUEST_URI']); ?>" method="POST">
                      <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                          <div class="profile_img">
                            <div id="crop-avatar">
                              <!-- Current avatar -->
                              <img class="img-responsive img-thumbnail center-block" src="<?php echo $profile_pic; ?>" alt="Upload your profile picture" id="change_dp" style="width: 250px; height: 250px;">
                              <textarea name="base64" id="base64" style="display: none;" readonly></textarea>
                              <input type="hidden" value="<?php echo $profile_pic; ?>" name="profile_pic" id="profile_pic">
                              <div class="text-center">
                                <br/>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".change_dp">Change profile picture</button>
                              </div>
                              <div class="ln_solid"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- modal -->
                      <div class="modal fade change_dp" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title" id="myModalLabel">Change Profile Picture</h4>
                            </div>
                            <div class="modal-body">
                              <input type="file" id="fileInput" accept="image/*" />
                              <div>
                                <canvas id="canvas">
                                  Your browser does not support the HTML5 canvas element.
                                </canvas>
                              </div>
                              <div id="result"></div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" name="submit" class="btn btn-primary" id="btnCrop" data-dismiss="modal">Change</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- modal -->
                      <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                          <label>Surname:</label>
                          <input type="text" name="lname" placeholder="Enter text ..." class="form-control" value="<?php echo $lname; ?>">
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                          <label>First Name:</label>
                          <input type="text" name="fname" placeholder="Enter text ..." class="form-control" value="<?php echo $fname; ?>">
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                          <label>Middle Name:</label>
                          <input type="text" name="mname" placeholder="Enter text ..." class="form-control" value="<?php echo $mname; ?>">
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                          <label>Name Extension (Jr., Sr.):</label>
                          <input type="text" name="name_ext" placeholder="Enter text ..." class="form-control" value="<?php echo $name_ext; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Date of Birth:</label>
                            <input type="date" name="birthdate" placeholder="Enter text ..." class="form-control" value="<?php echo $birthdate; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Place of Birth:</label>
                            <input type="text" name="birthplace" placeholder="Enter text ..." class="form-control" value="<?php echo $birthplace; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Sex:</label>
                            <select name="gender" class="form-control">
                              <option value=""> -- SELECT -- </option>
                              <option value="M" <?php echo $gender == "M" ? "selected" : ""; ?>>Male</option>
                              <option value="F" <?php echo $gender == "F" ? "selected" : ""; ?>>Female</option>
                            </select>
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Civil Status:</label>
                            <select name="civilstatus" class="form-control">
                              <option value=""> -- SELECT -- </option>
                              <option value="single" <?php echo $civilstatus == "single" ? "selected" : ""; ?>>Single</option>
                              <option value="widowed" <?php echo $civilstatus == "widowed" ? "selected" : ""; ?>>Widowed</option>
                              <option value="married" <?php echo $civilstatus == "married" ? "selected" : ""; ?>>Married</option>
                              <option value="separated" <?php echo $civilstatus == "separated" ? "selected" : ""; ?>>Separated</option>
                              <option value="others" <?php echo $civilstatus == "others" ? "selected" : ""; ?>>Other/s</option>
                            </select>
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Height (m):</label>
                            <input type="number" step=".01" min="0" name="height" placeholder="Enter text ..." class="form-control" value="<?php echo $height; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Weight (kg):</label>
                            <input type="number" min="0" name="weight" placeholder="Enter text ..." class="form-control" value="<?php echo $weight; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Blood Type:</label>
                            <input type="text" name="bloodtype" placeholder="Enter text ..." class="form-control" value="<?php echo $bloodtype; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>GSIS ID No:</label>
                            <input type="text" name="gsisno" placeholder="Enter text ..." class="form-control" value="<?php echo $gsisno; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>PAG-IBIG ID No:</label>
                            <input type="text" name="pagibigno" placeholder="Enter text ..." class="form-control" value="<?php echo $pagibigno; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>PHILHEALTH No:</label>
                            <input type="text" name="philhealthno" placeholder="Enter text ..." class="form-control" value="<?php echo $philhealthno; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>SSS No:</label>
                            <input type="text" name="sssno" placeholder="Enter text ..." class="form-control" value="<?php echo $sssno; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>TIN No:</label>
                            <input type="text" name="tinno" placeholder="Enter text ..." class="form-control" value="<?php echo $tinno; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Agency Employee No:</label>
                            <input type="text" name="agencyemployeeno" placeholder="Enter text ..." class="form-control" value="<?php echo $agencyemployeeno; ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Citizenship:</label>
                            <input type="text" name="citizenship" placeholder="Enter text ..." class="form-control" value="<?php echo $citizenship; ?>">
                          </div>
                          <div class="clearfix"></div>
                          <hr/>
                          <!-- residential -->
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>RESIDENTIAL ADDRESS: House/Block/Lot No. and Street:</label>
                            <input type="text" name="residentialaddr1" id="residentialaddr1" placeholder="Enter text ..." class="form-control" value="<?php echo $residentialaddr1; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>RESIDENTIAL ADDRESS: Subdivision/Village and Barangay:</label>
                            <input type="text" name="residentialaddr2" id="residentialaddr2" placeholder="Enter text ..." class="form-control" value="<?php echo $residentialaddr2; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>RESIDENTIAL ADDRESS: City/Municipality and Province:</label>
                            <input type="text" name="residentialaddr3" id="residentialaddr3" placeholder="Enter text ..." class="form-control" value="<?php echo $residentialaddr3; ?>">
                          </div>
                          <!-- residential -->
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Residential Zip Code:</label>
                            <input type="text" name="reszipcode" id="reszipcode" placeholder="Enter text ..." class="form-control" value="<?php echo $reszipcode; ?>">
                          </div>
                          <div class="clearfix"></div>
                          <hr/>
                          <!-- permanent -->
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <div class="checkbox">
                              <label><input type="checkbox" id="same_addr">Same as Residential Address</label>
                            </div>
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>PERMANENT ADDRESS: House/Block/Lot No. and Street:</label>
                            <input type="text" name="permanentaddr1" id="permanentaddr1" placeholder="Enter text ..." class="form-control" value="<?php echo $permanentaddr1; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>PERMANENT ADDRESS: Subdivision/Village and Barangay:</label>
                            <input type="text" name="permanentaddr2" id="permanentaddr2" placeholder="Enter text ..." class="form-control" value="<?php echo $permanentaddr2; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>PERMANENT ADDRESS: City/Municipality and Province:</label>
                            <input type="text" name="permanentaddr3" id="permanentaddr3" placeholder="Enter text ..." class="form-control" value="<?php echo $permanentaddr3; ?>">
                          </div>
                          <!-- permanent -->
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Permanent Zip Code:</label>
                            <input type="text" name="permzipcode" id="permzipcode" placeholder="Enter text ..." class="form-control" value="<?php echo $permzipcode; ?>">
                          </div>
                          <div class="clearfix"></div>
                          <hr/>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Telephone No:</label>
                            <input type="text" name="telno" placeholder="Enter text ..." class="form-control" value="<?php echo $telno; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Mobile No:</label>
                            <input type="text" name="mobileno" placeholder="Enter text ..." class="form-control" value="<?php echo $mobileno; ?>">
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>E-mail Address (If Any):</label>
                            <input type="text" name="emailaddr" placeholder="Enter text ..." class="form-control" value="<?php echo $emailaddr; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                          <br/>
                          <input type="submit" name="submit" value="Save Changes" class="btn btn-success">
                        </div>
                      </div>
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
    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
    <!-- PNotify -->
    <script src="../vendors/pnotify/dist/pnotify.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>
    <!-- Cropper -->
    <script src="../vendors/cropper/dist/cropper.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>
    <script src="js/custom/personal_info.js"></script>
    <script>
      $("#same_addr").on('change', function(){
        if($("#same_addr").prop("checked")){
          $("#permanentaddr1").val($("#residentialaddr1").val());
          $("#permanentaddr1").prop("readonly", true);
          
          $("#permanentaddr2").val($("#residentialaddr2").val());
          $("#permanentaddr2").prop("readonly", true);
          
          $("#permanentaddr3").val($("#residentialaddr3").val());
          $("#permanentaddr3").prop("readonly", true);
          
          $("#permzipcode").val($("#reszipcode").val());
          $("#permzipcode").prop("readonly", true);
        }else{
          $("#permanentaddr1").val("");
          $("#permanentaddr1").prop("readonly", false);
          
          $("#permanentaddr2").val("");
          $("#permanentaddr2").prop("readonly", false);
          
          $("#permanentaddr3").val("");
          $("#permanentaddr3").prop("readonly", false);
          
          $("#permzipcode").val("");
          $("#permzipcode").prop("readonly", false);
        }
      });
    </script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
