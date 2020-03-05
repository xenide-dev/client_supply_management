<?php
    include '../../../connection/connection.php';

    $output["msg"] = false;
    if(isset($_POST["type"])){
        if($_POST["type"] == "item"){
            if($_POST["operation"] == "getAll"){
                if($_POST["requestType"] == "requisition"){
                    $items = [];
                    // retrieve all items that have a stock
                    $a = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid WHERE id.item_type = 'Consumable' ORDER BY id.item_name ASC");
                    while($arow = $a->fetch()){
                        // check if there is some stock
                        $c = DB::run("SELECT * FROM supplies_equipment WHERE itemid = ?", [$arow["itemid"]]);
                        if($crow = $c->fetch()){
                            if($crow["available_qty"] > 0){
                                $arow["rem_qty"] = $crow["available_qty"];
                                array_push($items, $arow);
                            }
                        }
                    }
                    $output["info"] = $items;
                    $output["msg"] = true;

                }elseif($_POST["requestType"] == "purchase"){
                    $items = [];
                    // retrieve all items that are not available
                    $a = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid ORDER BY id.item_name ASC");
                    while($arow = $a->fetch()){
                        // check if there is some stock
                        $c = DB::run("SELECT * FROM supplies_equipment WHERE itemid = ?", [$arow["itemid"]]);
                        if($crow = $c->fetch()){
                            if($crow["item_qty"] <= 0){
                                array_push($items, $arow);
                            }
                        }else{
                            array_push($items, $arow);
                        }
                    }
                    $output["info"] = $items;
                    $output["msg"] = true;
                }
            }elseif($_POST["operation"] == "get"){
                $id = $_POST["id"];

                $r = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid WHERE itemid = ?", [$id]);
                $row = $r->fetch();

                // check if there is some stock
                $c = DB::run("SELECT * FROM supplies_equipment WHERE itemid = ?", [$row["itemid"]]);
                if($crow = $c->fetch()){
                    $row["rem_qty"] = $crow["available_qty"];
                }

                $output["info"] = $row;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getAllItemPPMP"){
                // retrieve all items that have a stock
                $a = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid ORDER BY id.item_name ASC");
                $arow = $a->fetchAll();
                
                $output["info"] = $arow;
                $output["msg"] = true;
            }
        }
    }

    echo json_encode($output);
?>