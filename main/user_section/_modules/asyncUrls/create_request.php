<?php
    include '../../../connection/connection.php';
    session_start();

    $output["msg"] = false;
    if(isset($_POST["type"])){
        if($_POST["type"] == "item"){
            if($_POST["operation"] == "getAll"){
                if($_POST["requestType"] == "requisition"){
                    $items = [];
                    // retrieve all the items under user's ppmp
                    $p = DB::run("SELECT * FROM ppmp p JOIN ppmp_items pi ON p.pid = pi.pid JOIN item_dictionary id ON pi.itemid = id.itemid WHERE p.uid = ? AND id.item_type = 'Consumable' AND p.ppmp_year = ?", [$_SESSION["uid"], date("Y")]);
                    while($prow = $p->fetch()){

                        $subTotalQty = 0;
                        // check how many requests are already conducted per item
                        $r = DB::run("SELECT * FROM request r JOIN request_items ri ON r.rid = ri.rid WHERE r.uid = ? AND ri.itemid = ? AND r.request_type = 'Requisition' AND r.created_at LIKE ?", [$_SESSION["uid"], $prow["itemid"], "%" . date("Y") . "%"]);
                        while($rrow = $r->fetch()){
                            if($rrow["status"] != "Pending" || $rrow["status"] != "Disapproved"){
                                $subTotalQty += $rrow["requested_qty"];
                            }
                        }

                        $prow["rem_qty"] = $prow["requested_qty"] - $subTotalQty;
                        array_push($items, $prow);
                    }

                    // // retrieve all items that have a stock
                    // $a = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid WHERE id.item_type = 'Consumable' ORDER BY id.item_name ASC");
                    // while($arow = $a->fetch()){
                    //     // check if there is some stock
                    //     $c = DB::run("SELECT * FROM supplies_equipment WHERE itemid = ?", [$arow["itemid"]]);
                    //     if($crow = $c->fetch()){
                    //         if($crow["available_qty"] > 0){
                                
                    //             $arow["rem_qty"] = $crow["available_qty"];
                    //             array_push($items, $arow);
                    //         }
                    //     }
                    // }
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

                // retrieve all the items under user's ppmp
                $p = DB::run("SELECT * FROM ppmp p JOIN ppmp_items pi ON p.pid = pi.pid JOIN item_dictionary id ON pi.itemid = id.itemid WHERE p.uid = ? AND id.itemid = ? AND p.ppmp_year = ?", [$_SESSION["uid"], $id, date("Y")]);
                if($prow = $p->fetch()){

                    $subTotalQty = 0;
                    // check how many requests are already conducted per item
                    $r = DB::run("SELECT * FROM request r JOIN request_items ri ON r.rid = ri.rid WHERE r.uid = ? AND ri.itemid = ? AND r.request_type = 'Requisition' AND r.created_at LIKE ?", [$_SESSION["uid"], $prow["itemid"], "%" . date("Y") . "%"]);
                    while($rrow = $r->fetch()){
                        if($rrow["status"] != "Pending" || $rrow["status"] != "Disapproved"){
                            $subTotalQty += $rrow["requested_qty"];
                        }
                    }

                    $prow["rem_qty"] = $prow["requested_qty"] - $subTotalQty;
                    $output["info"] = $prow;
                }

                
                // $r = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid WHERE itemid = ?", [$id]);
                // $row = $r->fetch();

                // // check if there is some stock
                // $c = DB::run("SELECT * FROM supplies_equipment WHERE itemid = ?", [$row["itemid"]]);
                // if($crow = $c->fetch()){
                //     $row["rem_qty"] = $crow["available_qty"];
                // }
                // $output["info"] = $row;

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