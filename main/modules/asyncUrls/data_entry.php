<?php
    include '../../connection/connection.php';
    session_start();

    $output["msg"] = false;
    if(isset($_POST["type"])){
        if($_POST["type"] == "cat"){
            if($_POST["operation"] == "get"){
                $id = $_POST["id"];

                $r = DB::run("SELECT * FROM item_category WHERE catid = ?", [$id]);
                $row = $r->fetch();
                $output["info"] = $row;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "delete"){
                $id = $_POST["id"];

                $d = DB::run("DELETE FROM item_category WHERE catid = ?", [$id]);
                if($d->rowCount() > 0){
                    $output["msg"] = true;
                }
            }elseif($_POST["operation"] == "getAll"){
                $r = DB::run("SELECT * FROM item_category ORDER BY cat_name ASC");
                $row = $r->fetchAll();
                $output["info"] = $row;
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "item"){
            if($_POST["operation"] == "get"){
                $id = $_POST["id"];

                $r = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid WHERE id.itemid = ?", [$id]);
                $row = $r->fetch();
                $output["info"] = $row;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "delete"){
                $id = $_POST["id"];

                $d = DB::run("DELETE FROM item_dictionary WHERE itemid = ?", [$id]);
                if($d->rowCount() > 0){
                    $output["msg"] = true;
                }
            }elseif($_POST["operation"] == "getAll"){
                $r = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid ORDER BY ic.cat_name");
                $row = $r->fetchAll();
                $output["info"] = $row;
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "request"){
            if($_POST["operation"] == "processRequest"){
                $tid = $_POST["tid"];
                $rid = $_POST["rid"];
                $uid = $_POST["uid"];
                $action = $_POST["action"];
                $tracer_no = $_POST["tracer_no"];
                $request_type = $_POST["request_type"];
                if($action == "Approved"){
                    if($request_type == "Requisition" || $request_type == "Purchase Order"){
                        $status = "Processing";
                    }elseif($request_type == "Purchase Request"){
                        $status = "Approved";
                    }
                }else{
                    $status = $action;
                }

                // update the request entry status
                DB::run("UPDATE request SET status = ?, updated_at = ? WHERE rid = ?", [$status, DB::getCurrentDateTime(), $rid]);

                // update the previous trace
                if($request_type == "Requisition" || $request_type == "Purchase Request"){
                    DB::run("UPDATE request_tracer SET destination_uid = ?, status = ? WHERE tid = ?", [$uid, $status, $tid]);
                }elseif($request_type == "Purchase Order"){
                    DB::run("UPDATE request_tracer SET destination_uid = ?, status = ? WHERE tid = ?", [$uid, 'Approved', $tid]);
                }

                if($action == "Approved"){
                    if($request_type == "Requisition" || $request_type == "Purchase Request"){
                       // create another trace entry : forwarded to Administrator (if purchase request or requisition);
                        DB::run("INSERT request_tracer(tracer_no, rid, source_uid, destination_uid_type, status) VALUES(?, ?, ?, ?, ?)", [intval($tracer_no) + 1, $rid, $uid, 'Administrator', $status]);
                    }elseif($request_type == "Purchase Order"){
                        // create another trace entry : forwarded to Inspector (if purchase order);
                        DB::run("INSERT INTO request_tracer(tracer_no, rid, source_uid, destination_uid_type, status) VALUES(?, ?, ?, ?, ?)", [intval($tracer_no) + 1, $rid, $uid, 'Inspector', 'Pending']);
                    }

                }

                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "purchase"){
            if($_POST["operation"] == "processInspection"){
                $poiid = $_POST["poiid"];

                for ($i=0; $i < count($poiid); $i++) { 
                    $u = DB::run("UPDATE purchase_order_items SET isDelivered = ? WHERE poiid = ?", [$poiid[$i]["state"], $poiid[$i]["val"]]);
                }

                // get id
                $g = DB::run("SELECT * FROM purchase_order_items poi JOIN purchase_order po ON poi.poid = po.poid WHERE poi.poiid = ?", [ $poiid[0]["val"] ]);
                $grow = $g->fetch();
                
                $output["poid"] = $grow["poid"];
                $output["rid"] = $grow["rid"];

                // update the request for partial or incomplete
                DB::run("UPDATE request SET status = 'Incomplete' WHERE rid = ?", [$grow["rid"]]);

                // check if all items has been delivered
                $output["done"] = true;
                $c = DB::run("SELECT * FROM purchase_order_items WHERE poid = ?", [$grow["poid"]]);
                while($crow = $c->fetch()){
                    if($crow["isDelivered"] != 1){
                        $output["done"] = false;
                    }
                }
                $output["msg"] = true;
            }elseif($_POST["operation"] == "processPurchase"){
                $rid = $_POST["rid"];
                $status = $_POST["action"];
                $uid = $_SESSION["uid"];

                // update purchase order table
                DB::run("UPDATE purchase_order SET status = ?, updated_at = ? WHERE rid = ?", [$status, DB::getCurrentDateTime(), $rid]);

                // update the request entry status
                DB::run("UPDATE request SET status = ?, updated_at = ? WHERE rid = ?", [$status, DB::getCurrentDateTime(), $rid]);

                // get the last trace record
                $t = DB::run("SELECT * FROM request_tracer WHERE rid = ? AND destination_uid_type = 'Administrator' ORDER BY tracer_no DESC", [$rid]);
                $trow = $t->fetch();
                $tracer_no = $trow["tracer_no"];

                // update the previous trace
                DB::run("UPDATE request_tracer SET destination_uid = ?, status = ? WHERE tid = ?", [$uid, $status, $trow["tid"]]);

                // update the supplies record
                // retrieve all the items of the request
                $ri = DB::run("SELECT * FROM request_items WHERE rid = ?", [$rid]);
                while($rirow = $ri->fetch()){
                    // check if the item has an entry on the table
                    $c = DB::run("SELECT * FROM supplies_equipment WHERE itemid = ?", [$rirow["itemid"]]);
                    if($crow = $c->fetch()){
                        // update the entry
                        DB::run("UPDATE supplies_equipment SET item_qty = item_qty + ?, updated_at = ? WHERE sid = ?", [$rirow["requested_qty"], DB::getCurrentDateTime(), $crow["sid"]]);

                        // get poid
                        $p = DB::run("SELECT * FROM purchase_order WHERE rid = ?", [$rid]);
                        $prow = $p->fetch();

                        // insert entry for transaction
                        DB::run("INSERT INTO supplies_equipment_transaction(transaction_type, sid, poid, remarks) VALUES(?, ?, ?, ?)", ['In', $crow["sid"], $prow["poid"], "Purchase Order"]);
                    }else{
                        // insert new entry
                        DB::run("INSERT INTO supplies_equipment(itemid, item_qty, item_unit, reorder_point) VALUES(?, ?, ?, ?)", [$rirow["itemid"], $rirow["requested_qty"], $rirow["requested_unit"], 30]);
                        $sid = DB::getLastInsertedID();
                        
                        // get poid
                        $p = DB::run("SELECT * FROM purchase_order WHERE rid = ?", [$rid]);
                        $prow = $p->fetch();

                        // insert entry for transaction
                        DB::run("INSERT INTO supplies_equipment_transaction(transaction_type, sid, poid, remarks) VALUES(?, ?, ?, ?)", ['In', $sid, $prow["poid"], "Purchase Order"]);

                    }
                }


                $output["msg"] = true;
            }elseif($_POST["operation"] == "processIssuance"){
                $rid = $_POST["rid"];
                $status = $_POST["action"];
                $uid = $_SESSION["uid"];

                // update request table
                DB::run("UPDATE request SET status = ?, updated_at = ? WHERE rid = ?", [$status, DB::getCurrentDateTime(), $rid]);

                // get the last trace record
                $t = DB::run("SELECT * FROM request_tracer WHERE rid = ? AND destination_uid_type = 'Administrator' ORDER BY tracer_no DESC", [$rid]);
                $trow = $t->fetch();
                $tracer_no = $trow["tracer_no"];

                // get the uid of the request
                $u = DB::run("SELECT * FROM request WHERE rid = ?", [$rid]);
                $destin_uid = $u->fetch()["uid"];

                // create another trace entry
                DB::run("INSERT request_tracer(tracer_no, rid, source_uid, destination_uid_type, destination_uid, status) VALUES(?, ?, ?, ?, ?, ?)", [intval($tracer_no) + 1, $rid, $uid, 'User', $destin_uid, 'Ready']);

                // get the requested qty
                $g = DB::run("SELECT * FROM request_items WHERE rid = ?", [$rid]);
                while($grow = $g->fetch()){
                    // deduct the qty from the main table
                    DB::run("UPDATE supplies_equipment SET item_qty = item_qty - ?, updated_at = ? WHERE itemid = ?", [$grow["requested_qty"], DB::getCurrentDateTime(), $grow["itemid"]]);

                    // insert transaction entry
                    DB::run("INSERT INTO supplies_equipment_transaction(transaction_type, riid, destination_uid, item_qty, remarks) VALUES(?, ?, ?, ?, ?)", ['Out', $grow["riid"], $destin_uid, $grow["requested_qty"], 'Request']);
                }


                $output["msg"] = true;
            }elseif($_POST["operation"] == "processInspectionReport"){
                $poid = $_POST["poid"];
                $rid = $_POST["rid"];
                $action = $_POST["action"];
                $uid = $_SESSION["uid"];
                if($action == "Approved"){
                    $status = "Inspected";   
                }

                // update purchase order table
                DB::run("UPDATE purchase_order SET status = ?, updated_at = ? WHERE poid = ?", [$status, DB::getCurrentDateTime(), $poid]);

                // update the request entry status
                DB::run("UPDATE request SET status = ?, updated_at = ? WHERE rid = ?", [$status, DB::getCurrentDateTime(), $rid]);

                // get the last trace record
                $t = DB::run("SELECT * FROM request_tracer WHERE rid = ? AND destination_uid_type = 'Inspector' AND status = 'Pending' ORDER BY tracer_no DESC", [$rid]);
                $trow = $t->fetch();
                $tracer_no = $trow["tracer_no"];

                // update the previous trace
                DB::run("UPDATE request_tracer SET destination_uid = ?, status = ? WHERE tid = ?", [$uid, $status, $trow["tid"]]);

                // create another trace entry
                DB::run("INSERT request_tracer(tracer_no, rid, source_uid, destination_uid_type, status) VALUES(?, ?, ?, ?, ?)", [intval($tracer_no) + 1, $rid, $uid, 'Administrator', 'Pending']);
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "equipments"){
            if($_POST["operation"] == "performTransfer"){
                $stid = $_POST["stid"];
                $riid = $_POST["riid"];
                $from_uid = $_POST["from_uid"];
                $transfer_type = $_POST["transfer_type"];
                $transfer_to = $_POST["transfer_to"];
                $transfer_purpose = $_POST["transfer_purpose"];

                // get the target user details
                $u = DB::run("SELECT * FROM user_accounts WHERE uid = ?", [$transfer_to]);
                $urow = $u->fetch();

                $text = "Transfer-" . $transfer_type . "-" . $urow["lname"] . ", " . $urow["fname"] . " " . $urow["midinit"] . "-Pending";

                // update the transaction
                DB::run("UPDATE supplies_equipment_transaction SET updated_at = ?, transaction_status = ?, transaction_reason = ?, requested_by_uid = ?, target_uid = ? WHERE stid = ?", [DB::getCurrentDateTime(), $text, $transfer_purpose, $_SESSION["uid"], $transfer_to, $stid]);

                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "transfer"){
            if($_POST["operation"] == "processRequest"){
                $stid = $_POST["stid"];
                $action = $_POST["action"];

                // get the transfer details
                $t = DB::run("SELECT * FROM supplies_equipment_transaction st JOIN user_accounts ua ON st.destination_uid = ua.uid WHERE st.stid = ?", [$stid]);
                $trow = $t->fetch();

                if($action == "Approved"){
                    // update the text
                    $tempText = str_replace("Pending", "Approved", $trow["transaction_status"]);
                    DB::run("UPDATE supplies_equipment_transaction SET transaction_status = ? WHERE stid = ?", [$tempText, $stid]);

                    $exText = explode("-", $tempText);
                    $exText[2] = $trow["lname"] . ", " . $trow["fname"] . " " . $trow["midinit"];
                    $newStatus = implode("-", $exText);
                    // insert new transaction entry
                    $i = DB::run("INSERT INTO supplies_equipment_transaction(created_at, transaction_type, riid, destination_uid, item_qty, remarks, item_status, report_type, report_item_no, report_overall_no, transaction_status, transaction_reason) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [DB::getCurrentDateTime(), "Out", $trow["riid"], $trow["target_uid"], $trow["item_qty"], "Transfer", null, $trow["report_type"], $trow["report_item_no"], $trow["report_overall_no"], $newStatus, $trow["transaction_reason"]]);
                    $lastID = DB::getLastInsertedID();

                    // update the items in qr table
                    DB::run("UPDATE supplies_equipment_transaction_qr_collection SET stid = ? WHERE stid = ?", [$lastID, $stid]);
                }elseif($action == "Disapproved"){
                    // update the text
                    $tempText = str_replace("Pending", "Disapproved", $trow["transaction_status"]);
                    DB::run("UPDATE supplies_equipment_transaction SET transaction_status = ? WHERE stid = ?", [$tempText, $stid]);
                }

                $output["msg"] = true;
            }
        }
    }

    echo json_encode($output);
?>