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
        }
    }

    echo json_encode($output);
?>