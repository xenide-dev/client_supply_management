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


    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <link href="css/custom/custom_loading.css" rel="stylesheet">
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
                <h3>Data Entry</h3>
              </div>
            </div>

            <div class="clearfix"></div>


            <div class="row">
              <!-- Item -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div id="loading_modal">
                    <div id="loading-circle"></div>
                  </div>
                  <div class="x_title">
                    <h2>List of Item / Equipment</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php
                      if(isset($_POST["submit_item"])){
                        $catid = $_POST["catid"];
                        $item_code = strtoupper($_POST["item_code"]);
                        $item_name = strtoupper($_POST["item_name"]);
                        $item_descrip = strtolower($_POST["item_descrip"]);
                        $item_default_unit = $_POST["item_default_unit"];
                        $item_type = $_POST["item_type"];

                        $i = DB::run("INSERT INTO item_dictionary(catid, item_code, item_name, item_description, item_default_unit, item_type) VALUES(?, ?, ?, ?, ?, ?)", [$catid, $item_code, $item_name, $item_descrip, $item_default_unit, $item_type]);

                        if($i->rowCount() > 0){
                    ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong> Item has been added
                    </div>
                    <?php
                        }
                      }

                      if(isset($_POST["update_item"])){
                        $itemid = $_POST["itemid"];
                        $catid = $_POST["catid"];
                        $item_code = strtoupper($_POST["item_code"]);
                        $item_name = strtoupper($_POST["item_name"]);
                        $item_descrip = strtolower($_POST["item_descrip"]);
                        $item_default_unit = $_POST["item_default_unit"];
                        $item_type = $_POST["item_type"];

                        $u = DB::run("UPDATE item_dictionary SET catid = ?, item_code = ?, item_name = ?, item_description = ?, item_default_unit = ?, item_type = ? WHERE itemid = ?", [$catid, $item_code, $item_name, $item_descrip, $item_default_unit, $item_type, $itemid]);
                        if($u->rowCount() > 0){
                    ?>
                    <div class="alert alert-success">
                      <strong>Success!</strong> Item has been updated
                    </div>
                    <?php
                        }
                      }

                      if(isset($_POST["submitItemCSV"])){
                        $flag = false;
                        foreach ($_POST["item_rows"] as $key => $value) {
                          $value = explode(',', $value);
                          $cat_name = strtoupper(trim($value[0]));
                          $item_code = strtoupper(trim($value[1]));
                          $item_unit = ucfirst(trim($value[2]));

                          // check if category exist
                          $c = DB::run("SELECT * FROM item_category WHERE cat_name = ?", [$cat_name]);
                          if($crow = $c->fetch()){
                            $catid = $crow["catid"];
                          }else{
                            DB::run("INSERT INTO item_category(cat_name) VALUES(?)", [$cat_name]);
                            $catid = DB::getLastInsertedID();
                          }

                          // check if unit of measure exist (for records only)
                          $u = DB::run("SELECT count(*) as total FROM units_measure WHERE unit_name = ?", [$item_unit]);
                          $ur = $u->fetch();
                          if($ur["total"] == 0 ){
                            DB::run("INSERT INTO units_measure(unit_name) VALUES(?)", [$item_unit]);
                          }

                          // process item description
                          $temp = array_slice($value, 3);
                          $allUp = [];
                          $rem = [];
                          for ($i=0; $i < count($temp); $i++) {
                            $val = trim($temp[$i]);
                            if(ctype_upper($val[0])){
                              array_push($allUp, $val);
                            }else{
                              array_push($rem, $val);
                            }
                          }

                          $item_name = implode(",", $allUp);
                          $item_descrip = implode(",", $rem);

                          // check if item already exist based on item code
                          $i = DB::run("SELECT * FROM item_dictionary WHERE item_code = ?", [$item_code]);
                          if($ir = $i->fetch()){
                            // update based on item_code
                            $u = DB::run("UPDATE item_dictionary SET catid = ?, item_name = ?, item_description = ?, item_default_unit = ? WHERE item_code = ?", [$catid, $item_name, $item_descrip, $item_default_unit, $item_code]);
                            if($u->rowCount() > 0){
                              $flag = true;
                            }
                          }else{
                            // insert as new
                            $a = DB::run("INSERT INTO item_dictionary(catid, item_code, item_name, item_description, item_default_unit) VALUES(?, ?, ?, ?, ?)", [$catid, $item_code, $item_name, $item_descrip, $item_unit]);
                            if($a->rowCount() > 0){
                              $flag = true;
                            }
                          }

                        }

                        if($flag){
                    ?>
                    <div class="alert alert-success">
                      <strong>Success!</strong> Data has been uploaded
                    </div>
                    <?php
                        }
                      }
                    ?>
                     <table id="dtItem" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Category</th>
                            <th>Item Code</th>
                            <th>Item/Equipment's Name</th>
                            <th>Item Description</th>
                            <th>Default Unit</th>
                            <th>Item Type</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      <div class="modal fade bs-update-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">

                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate>
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Update Item/Equipment</h4>
                              </div>
                              <div class="modal-body">
                                <input type="text" name="itemid" id="itemid" style="display: none;">
                                <label>Category: <span class="text-danger">*</span></label>
                                <select name="catid" class="form-control cat_select" id="up_catid" required>
                                  <option value="">-- Please select a value --</option>
                                  <?php
                                      $cat = DB::run("SELECT * FROM item_category ORDER BY cat_name ASC");
                                      while($catrow = $cat->fetch()){
                                  ?>
                                  <option value="<?php echo $catrow['catid']; ?>"><?php echo $catrow['cat_name']; ?></option>
                                  <?php
                                      }
                                  ?>
                                </select>
                                <br/>
                                <label>Item Code: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="item_code" name="item_code" placeholder="Enter your text ..." required>
                                <br/>
                                <label>Item/Equipment's Name: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter your text ..." required>
                                <br/>
                                <label>Item Description: </label>
                                <input type="text" class="form-control" id="item_descrip" name="item_descrip" placeholder="Enter your text ...">
                                <br/>
                                <label>Item Default Unit: <span class="text-danger">*</span></label>
                                <select name="item_default_unit" id="item_default_unit" class="form-control" required>
                                    <option value="">-- Please select a value --</option>
                                    <?php
                                      $u = DB::run("SELECT * FROM units_measure ORDER BY unit_name ASC");
                                      while($urow = $u->fetch()){
                                    ?>
                                    <option value="<?php echo $urow['unit_name']; ?>"><?php echo $urow['unit_name']; ?></option>
                                    <?php
                                      }
                                    ?>
                                </select>
                                <br/>
                                <label>Item Type: <span class="text-danger">*</span></label>
                                <select name="item_type" id="item_type" class="form-control" required>
                                    <option value="">-- Please select a value --</option>
                                    <option value="Consumable">Consumable</option>
                                    <option value="Non-Consumable">Non-Consumable</option>
                                </select>
                                <br/>
                              </div>
                              <div class="modal-footer">
                                <input type="submit" name="update_item" value="Save Changes" class="btn btn-success">
                              </div>
                            </form>

                          </div>
                        </div>
                      </div>
                      <div>
                        <button class="btn btn-success btn-xs" data-toggle="modal" data-target=".bs-example-modal-sm" id="btnAddItem"><span class="fa fa-plus"></span> Add Item</button>
                        <button class="btn btn-success btn-xs" data-toggle="modal" data-target=".bs-item-csv" id="btnAddItem"><span class="fa fa-upload"></span> Upload CSV</button>

                        <!-- for add modal -->
                        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">

                              <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate>
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel">Add Item</h4>
                                </div>
                                <div class="modal-body">
                                  <label>Category: <span class="text-danger">*</span></label>
                                  <select name="catid" class="form-control cat_select" required>
                                    <option value="">-- Please select a value --</option>
                                    <?php
                                        $cat = DB::run("SELECT * FROM item_category ORDER BY cat_name ASC");
                                        while($catrow = $cat->fetch()){
                                    ?>
                                    <option value="<?php echo $catrow['catid']; ?>"><?php echo $catrow['cat_name']; ?></option>
                                    <?php
                                        }
                                    ?>
                                  </select>
                                  <br/>
                                  <label>Item Code: <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="item_code" placeholder="Enter your text ..." required>
                                  <br/>
                                  <label>Item/Equipment's Name: <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="item_name" placeholder="Enter your text ..." required>
                                  <br/>
                                  <label>Item Description: </label>
                                  <input type="text" class="form-control" name="item_descrip" placeholder="Enter your text ...">
                                  <br/>
                                  <label>Item Default Unit: <span class="text-danger">*</span></label>
                                  <select name="item_default_unit" class="form-control" required>
                                      <option value="">-- Please select a value --</option>
                                      <?php
                                        $u = DB::run("SELECT * FROM units_measure ORDER BY unit_name ASC");
                                        while($urow = $u->fetch()){
                                      ?>
                                      <option value="<?php echo $urow['unit_name']; ?>"><?php echo $urow['unit_name']; ?></option>
                                      <?php
                                        }
                                      ?>
                                  </select>
                                  <br/>
                                  <label>Item Type: <span class="text-danger">*</span></label>
                                  <select name="item_type" class="form-control" required>
                                      <option value="">-- Please select a value --</option>
                                      <option value="Consumable">Consumable</option>
                                      <option value="Non-Consumable">Non-Consumable</option>
                                  </select>
                                  <br/>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" name="submit_item" class="btn btn-primary">Save changes</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        <!-- for upload csv -->
                        <div class="modal fade bs-item-csv" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                              <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel">Upload CSV File</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="alert alert-warning">
                                      <b>Note! Your CSV File content should be in a format (Category, Item Code, Unit of Measure, Item Name, Item Description)</b> <br/>
                                      <strong>For Item Name, the text should be in a upper case (or at least the first letter should be in a upper case).</strong> <br/>
                                      <strong>For Item Description, all characters should be in a lower case.</strong><br/>
                                      <strong>You can get the format <a href="template/common_items.csv">here.</a></strong>
                                  </div>
                                  <label>Select File: </label>
                                  <input type="file" class="form-control" name="upload_csv" accept=".csv" id="upload_csv">
                                  <br/>
                                  <label>File Content</label>
                                  <small>Uncheck the row if you don't want to include</small>
                                  <div id="rows_container"></div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" name="submitItemCSV" class="btn btn-primary">Save changes</button>
                                </div>
                              </form>

                            </div>
                          </div>
                        </div>

                      </div>
                  </div>
                </div>
              </div>
              
              <!-- Category -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Category List</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br/>
                    <?php
                        if(isset($_POST["submit_cat"])){
                            $cat_code = $_POST["cat_code"];
                            $cat_name = strtoupper($_POST["cat_name"]);
                            $cat_descrip = strtoupper($_POST["cat_descrip"]);

                            $c = DB::run("INSERT INTO item_category(cat_code, cat_name, cat_descrip) VALUES(?, ?, ?)", [$cat_code, $cat_name, $cat_descrip]);
                            if($c->rowCount() > 0){
                    ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong> Data has been added
                    </div>
                    <?php
                            }
                        }

                        if(isset($_POST["update_cat"])){
                          $catid = $_POST["catid"];
                          $cat_code = $_POST["cat_code"];
                          $cat_name = strtoupper($_POST["cat_name"]);
                          $cat_descrip = strtoupper($_POST["cat_descrip"]);

                          $u = DB::run("UPDATE item_category SET cat_code = ?, cat_name = ?, cat_descrip = ? WHERE catid = ?", [$cat_code, $cat_name, $cat_descrip, $catid]);
                          if($u->rowCount() > 0){
                    ?>
                    <div class="alert alert-success">
                      <strong>Success!</strong> Category has been updated
                    </div>
                    <?php
                          }
                        }
                    ?>
                     <table id="dtCategory" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $retrieve = DB::run("SELECT * FROM item_category ORDER BY cat_name ASC");
                            while ($row = $retrieve->fetch()) {
                          ?>
                          <tr>
                            <td><?php echo $row["cat_code"]; ?></td>
                            <td><?php echo $row["cat_name"]; ?></td>
                            <td><?php echo $row["cat_descrip"]; ?></td>
                            <td>
                              <a href="#" class="btn btn-success btn-xs" onclick="loadData(<?php echo $row['catid']; ?>, 'cat')" data-toggle="modal" data-target=".cat_up"><span class="fa fa-edit"></span> Edit</a>
                              <a href="#" class="btn btn-danger btn-xs" onclick="removeData(<?php echo $row['catid']; ?>, 'cat', this)"><span class="fa fa-trash"></span></a>
                            </td>
                          </tr>
                          <?php
                            }
                          ?>
                        </tbody>
                      </table>
                      <div class="modal fade cat_up" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">

                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate id="frmCatUpdate">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Update Category</h4>
                              </div>
                              <div class="modal-body">
                                <input type="text" name="catid" id="catid" style="display: none;">
                                <label>Category Code: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="cat_code" id="cat_code" placeholder="Enter your text ..." required>
                                <br/>
                                <label>Category Name: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="Enter your text ..." required>
                                <br/>
                                <label>Category Description: </label>
                                <input type="text" class="form-control" name="cat_descrip" id="cat_descrip" placeholder="Enter your text ...">
                                <br/>
                              </div>
                              <div class="modal-footer">
                                <input type="submit" name="update_cat" value="Save Changes" class="btn btn-success">
                              </div>
                            </form>

                          </div>
                        </div>
                      </div>
                      <div>
                        <button class="btn btn-success btn-xs" data-toggle="modal" data-target=".cat"><span class="fa fa-plus"></span> Add Category</button>

                        <div class="modal fade cat" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">

                              <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" data-parsley-validate>
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel">Add Category</h4>
                                </div>
                                <div class="modal-body">
                                  <label>Category Code: <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="cat_code" placeholder="Enter your text ..." required>
                                  <br/>
                                  <label>Category Name: <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="cat_name" placeholder="Enter your text ..." required>
                                  <br/>
                                  <label>Category Description: </label>
                                  <input type="text" class="form-control" name="cat_descrip" placeholder="Enter your text ...">
                                  <br/>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" name="submit_cat" class="btn btn-primary">Add Category</button>
                                </div>
                              </form>

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
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- sweetalert -->
    <script src="../vendors/sweetalert/sweetalert.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>
    <script src="js/custom/data_entry.js"></script>
  </body>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
</html>
