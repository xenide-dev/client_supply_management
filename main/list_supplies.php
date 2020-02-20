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
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Item / Equipment</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table id="dtItem" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Item/Equipment's Name</th>
                            <th>Category</th>
                            <th>Item Description</th>
                            <th>Default Unit</th>
                            <th>Item Type</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $retrieve = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid ORDER BY id.item_name ASC");
                            while ($row = $retrieve->fetch()) {
                          ?>
                          <tr>
                            <td><?php echo $row["item_name"]; ?></td>
                            <td><?php echo $row["cat_name"]; ?></td>
                            <td><?php echo $row["item_description"]; ?></td>
                            <td><?php echo $row["item_default_unit"]; ?></td>
                            <td><?php echo $row["item_type"]; ?></td>
                            <td>
                              <a href="#" class="btn btn-success btn-xs" onclick="loadData(<?php echo $row['itemid']; ?>, 'item')" data-toggle="modal" data-target=".bs-update-modal-sm"><span class="fa fa-edit"></span> Edit</a>
                              <a href="#" class="btn btn-danger btn-xs" onclick="removeData(<?php echo $row['itemid']; ?>, 'item', this)"><span class="fa fa-trash"></span></a>
                            </td>
                          </tr>
                          <?php
                            }
                          ?>
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
                                <label>Item/Equipment's Name: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter your text ..." required>
                                <br/>
                                <label>Item Description: </label>
                                <input type="text" class="form-control" id="item_descrip" name="item_descrip" placeholder="Enter your text ...">
                                <br/>
                                <label>Item Default Unit: <span class="text-danger">*</span></label>
                                <select name="item_default_unit" id="item_default_unit" class="form-control" required>
                                    <option value="">-- Please select a value --</option>
                                    <option value="Piece">Piece</option>
                                    <option value="Ream">Ream</option>
                                    <option value="Yard">Yard</option>
                                    <option value="Dozen">Dozen</option>
                                    <option value="Set">Set</option>
                                    <option value="Meter">Meter</option>
                                    <option value="Millimeter">Millimeter</option>
                                    <option value="Centimeter">Centimeter</option>
                                    <option value="Sack">Sack</option>
                                    <option value="Box">Box</option>
                                    <option value="Can">Can</option>
                                    <option value="Bottle">Bottle</option>
                                    <option value="Glass">Glass</option>
                                    <option value="Pair">Pair</option>
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
                                  <label>Item/Equipment's Name: <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="item_name" placeholder="Enter your text ..." required>
                                  <br/>
                                  <label>Item Description: </label>
                                  <input type="text" class="form-control" name="item_descrip" placeholder="Enter your text ...">
                                  <br/>
                                  <label>Item Default Unit: <span class="text-danger">*</span></label>
                                  <select name="item_default_unit" class="form-control" required>
                                      <option value="">-- Please select a value --</option>
                                      <option value="Piece">Piece</option>
                                      <option value="Ream">Ream</option>
                                      <option value="Yard">Yard</option>
                                      <option value="Dozen">Dozen</option>
                                      <option value="Set">Set</option>
                                      <option value="Meter">Meter</option>
                                      <option value="Millimeter">Millimeter</option>
                                      <option value="Centimeter">Centimeter</option>
                                      <option value="Sack">Sack</option>
                                      <option value="Box">Box</option>
                                      <option value="Can">Can</option>
                                      <option value="Bottle">Bottle</option>
                                      <option value="Glass">Glass</option>
                                      <option value="Pair">Pair</option>
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
