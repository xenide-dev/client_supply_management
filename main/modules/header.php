<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="images/user.png" alt="">
            <?php
              echo "Hi, " . $_SESSION["user_type"];
            ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Exit</a></li>
          </ul>
        </li>
        <?php
          // all notifications container
          $notifContainer = [];
          
          if($_SESSION["user_type"] == "Administrator"){
            // check all item's quantity
            $counter = 0;
            $s = DB::run("SELECT * FROM supplies_equipment s JOIN item_dictionary id ON s.itemid = id.itemid WHERE id.item_type = 'Consumable'");
            while($srow = $s->fetch()){
              // check if its need for restocking
              $percentage = $srow["reorder_point"] / 100;
              $tempVal = $srow["base_qty"] * $percentage;
              if($srow["available_qty"] < $tempVal){
                $counter++;
              }
            }

            if($counter > 0){
              $message["title"] = "Low / No Stock";
              $message["description"] = "You have " . $counter . " item/s that needs for restocking. Please check them out!";
              $message["link"] = "list_supplies.php";
              array_push($notifContainer, $message);
            }

            // check all pending documents
            $t = DB::run("SELECT COUNT(*) as total FROM request_tracer WHERE destination_uid_type = 'Administrator' AND status = 'Pending'");
            $trow = $t->fetch();
            $total = $trow["total"];

            if($total > 0){
              $message["title"] = "Pending Documents";
              $message["description"] = "You have " . $total . " document/s which require immediate attention. Please check them out!";
              $message["link"] = "list_requests.php";
              array_push($notifContainer, $message);
            }
          }elseif($_SESSION["user_type"] == "Regional Director"){
            // check all pending documents
            $t = DB::run("SELECT COUNT(*) as total FROM request_tracer WHERE destination_uid_type = 'Regional Director' AND status = 'Pending'");
            $trow = $t->fetch();
            $total = $trow["total"];

            if($total > 0){
              $message["title"] = "Pending Documents";
              $message["description"] = "You have " . $total . " document/s which require immediate attention. Please check them out!";
              $message["link"] = "documents_approval.php";
              array_push($notifContainer, $message);
            }
          }elseif($_SESSION["user_type"] == "Inspector"){
            // check all pending documents
            $t = DB::run("SELECT COUNT(*) as total FROM request_tracer WHERE destination_uid_type = 'Inspector' AND status = 'Pending'");
            $trow = $t->fetch();
            $total = $trow["total"];

            if($total > 0){
              $message["title"] = "Pending Documents";
              $message["description"] = "You have " . $total . " document/s which require immediate attention. Please check them out!";
              $message["link"] = "documents_approval.php";
              array_push($notifContainer, $message);
            }
          }

          if(count($notifContainer)){
        ?>
        <li role="presentation" class="dropdown">
          <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-warning" style="color: orange"></i>
            <span class="badge bg-red"><?php echo count($notifContainer); ?></span>
          </a>
          <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
            <?php
              for ($i=0; $i < count($notifContainer); $i++) { 
            ?>
            <li style="font-size: 13px;">
              <a href="<?php echo $notifContainer[$i]["link"]; ?>">
                <span>
                  <span class="bg-orange"><b><?php echo $notifContainer[$i]["title"]; ?></b></span>
                </span>
                <span class="message" style="font-size: 13px; margin-top: 5px;">
                  <?php echo $notifContainer[$i]["description"]; ?>
                </span>
              </a>
            </li>
            <?php
              }
            ?>
            <!-- <li>
              <div class="text-center">
                <a>
                  <strong>See All Alerts</strong>
                  <i class="fa fa-angle-right"></i>
                </a>
              </div>
            </li> -->
          </ul>
        </li>
        <?php
          }
        ?>
      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->