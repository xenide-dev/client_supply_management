<?php
    include '../../connection/connection.php';
    session_start();

    $output["msg"] = false;
    if(isset($_POST["type"])){
        if($_POST["type"] == "supplies"){
            if($_POST["operation"] == "getAll"){
                $r = DB::run("SELECT * FROM supplies_equipment se JOIN item_dictionary id ON se.itemid = id.itemid WHERE id.item_type = 'Consumable' ORDER BY item_name ASC");
                $row = $r->fetchAll();
                $output["info"] = $row;
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "request"){
            if($_POST["operation"] == "getAllItems"){
                $id = $_POST["id"];
                $r = DB::run("SELECT * FROM request r JOIN request_items ri ON r.rid = ri.rid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE r.rid = ?", [$id]);
                $row = $r->fetchAll();
                $output["info"] = $row;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getAllRequest"){
                $sortby = $_POST["sortby"];
                // retrieve all request
                $rows = [];

                $r = DB::run("SELECT * FROM request r JOIN user_accounts u ON r.uid = u.uid ORDER BY r.created_at ASC");
                while($row = $r->fetch()){
                    // get the last row of the trace and attach
                    $i = DB::run("SELECT * FROM request_tracer WHERE rid = ? ORDER BY tracer_no DESC", [$row["rid"]]);
                    $irow = $i->fetch();
                    
                    $row["hash_val"] = md5($row["rid"]);
                    $row["cur_user_type"] = $_SESSION["user_type"];

                    if($row["user_type"] == "Administrator"){
                        $row["isDone"] = true;
                    }else{
                        $row["isDone"] = false;
                    }
                    // check if the last record is for approval of purchase order
                    if($irow["remarks"] != "Purchase Order"){
                        // check by sort by
                        if($row["status"] == ucfirst($sortby) || $sortby == ""){
                            array_push($rows, $row);
                        }
                    }
                }
                
                $output["info"] = $rows;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getAllOtherRequest"){
                $rows = [];
                $ri = DB::run("SELECT * FROM supplies_equipment_transaction st JOIN request_items ri ON st.riid = ri.riid JOIN request r ON ri.rid = r.rid JOIN user_accounts ua ON ua.uid = st.requested_by_uid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE st.transaction_type = 'Out' AND st.transaction_status IS NOT NULL AND st.remarks = 'Request'");
                while($rirow = $ri->fetch()){
                    $temp["created_at"] = $rirow["created_at"];
                    $temp["request_type"] = $rirow["remarks"];
                    $temp["description"] = $rirow["item_name"] . "(" . $rirow["item_description"] . ")";
                    $temp["requested_by"] = $rirow["lname"] . ", " . $rirow["fname"] . " " . $rirow["midinit"];
                    $exTemp = explode("-", $rirow["transaction_status"]);
                    $temp["request_type"] = $exTemp[0];

                    // get the account details if there is an id in destination uid otherwise set it to N/A
                    $g = DB::run("SELECT * FROM user_accounts WHERE uid = ?", [$rirow["target_uid"]]);
                    if($grow = $g->fetch()){
                        $temp["issued_to"] = $grow["lname"] . ", " . $grow["fname"] . " " . $grow["midinit"];
                    }else{
                        $temp["issued_to"] = "N/A";
                    }

                    $temp["purpose"] = $rirow["transaction_reason"];
                    $temp["status"] = $exTemp[0] . " Request has been " . $exTemp[3];
                    $temp["cur_user_type"] = $_SESSION["user_type"];

                    array_push($rows, $temp);
                }

                $output["info"] = $rows;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getAllItemsPO"){
                $id = $_POST["id"];
                $output["info"] = [];

                // get all purchase orders
                $p = DB::run("SELECT * FROM purchase_order WHERE rid = ?", [$id]);
                while($prow = $p->fetch()){
                    $temp["po_number"] = $prow["po_number"];
                    $temp["supplier_name"] = $prow["supplier_name"];
                    $temp["supplier_address"] = $prow["supplier_address"];
                    $temp["total_amount"] = $prow["total_amount"];
                    $temp["items"] = [];

                    // retrieve all the items
                    $pi = DB::run("SELECT * FROM purchase_order_items poi JOIN request_items ri ON poi.riid = ri.riid JOIN item_dictionary i ON ri.itemid = i.itemid WHERE poi.poid = ?", [$prow["poid"]]);
                    while($pirow = $pi->fetch()){
                        $item["item_code"] = $pirow["item_code"];
                        $item["item_name"] = $pirow["item_name"];
                        $item["item_description"] = $pirow["item_description"];
                        $item["requested_unit"] = $pirow["requested_unit"];
                        $item["requested_qty"] = $pirow["requested_qty"];
                        $item["unit_cost"] = $pirow["unit_cost"];
                        $item["total_cost"] = $pirow["total_cost"];

                        array_push($temp["items"], $item);
                    }

                    array_push($output["info"], $temp);
                }
                
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "purchase"){
            if($_POST["operation"] == "getAll"){
                // retrieve all purchase orders
                $rows = [];
                $r = DB::run("SELECT po.poid, ua.fname, ua.midinit, ua.lname, po.po_number, po.supplier_name, po.supplier_address, po.created_at, po.status, po.rid FROM purchase_order po JOIN request r ON po.rid = r.rid JOIN user_accounts ua ON r.uid = ua.uid  ORDER BY po.created_at ASC");
                while($row = $r->fetch()){
                    // check if particular purchase order has been approved by RD
                    $p = DB::run("SELECT * FROM request_tracer WHERE rid = ? AND remarks = 'Purchase Order'", [$row["rid"]]);
                    $prow = $p->fetch();

                    // if($prow["status"] == "Approved"){
                    //     $row["h"] = md5($row["poid"]);
                    //     array_push($rows, $row);
                    // }

                    $row["h"] = md5($row["poid"]);
                    array_push($rows, $row);
                }
                $output["info"] = $rows;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getAllItems"){
                $id = $_POST["id"];
                $r = DB::run("SELECT * FROM purchase_order_items poi JOIN request_items ri ON poi.riid = ri.riid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE poi.poid = ?", [$id]);
                $row = $r->fetchAll();
                $output["info"] = $row;
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "equipments"){
            if($_POST["operation"] == "getAll"){
                $rows = [];
                // get all request
                $r = DB::run("SELECT * FROM request r JOIN request_items ri ON r.rid = ri.rid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE id.item_type = 'Non-Consumable'");
                while($row = $r->fetch()){
                    // get the transaction
                    $t = DB::run("SELECT * FROM supplies_equipment_transaction st JOIN user_accounts ua ON st.destination_uid = ua.uid WHERE st.riid = ? ORDER BY st.created_at DESC", [$row["riid"]]);
                    $trow = $t->fetch();
                    // check if the last transaction is out
                    // TODOIMP: UPDATE THE RETRIEVAL OF TRANSFER RECORDS (IF THERE IS)
                    if(isset($trow["transaction_type"])){
                        if($trow["transaction_type"] == "Out"){
                            // get the price
                            $p = DB::run("SELECT * FROM purchase_order_items WHERE riid = ?", [$trow["riid"]]);
                            $prow = $p->fetch();
                            $temp["total_cost"] = $prow["total_cost"];

                            $temp["itemid"] = $row["itemid"];
                            $temp["h"] = md5($row["itemid"]);
                            $temp["riid"] = $trow["riid"];
                            $temp["report_item_no"] = $trow["report_item_no"];
                            $temp["name_description"] = $row["item_name"] . "(" . $row["item_description"] . ")";
                            $temp["item_unit"] = $row["requested_unit"];
                            $temp["item_qty"] = $row["requested_qty"];
                            $temp["created_at"] = $row["created_at"];
                            $temp["issued_to"] = $trow["lname"] . ", " . $trow["fname"] . " " . $trow["midinit"];
                            
                            // initial date
                            $temp["transfer_date"] = 'N/A';
                            if($trow["remarks"] == "Request"){
                                if($trow["transaction_status"] == null){
                                    $temp["status"] = "Serviceable";
                                }else{
                                    if(strpos($trow["transaction_status"], "Pending") !== false){
                                        $tempText = explode("-", $trow["transaction_status"]);
                                        $temp["status"] = "Pending Transfer to " . $tempText[2] . " (" . $tempText[1] . ")";
                                    }elseif(strpos($trow["transaction_status"], "Disapproved") !== false){
                                        $temp["status"] = "Serviceable (Transfer Disapproved)";
                                    }else{
                                        $temp["status"] = $trow["transaction_status"];
                                    }
                                }
                            }elseif($trow["remarks"] == "Transfer"){
                                $tempText = explode("-", $trow["transaction_status"]);
                                $temp["status"] = "Transferred from " . $tempText[2];
                                $temp["transfer_date"] = $trow["created_at"];
                            }
                            $temp["from_uid"] = $trow["destination_uid"];
                            $temp["stid"] = $trow["stid"];
    
                            array_push($rows, $temp);
                        } 
                    }
                }

                $output["info"] = $rows;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getQRCode"){
                $stid = $_POST["stid"];

                // get the qr code
                $q = DB::run("SELECT * FROM supplies_equipment_transaction_qr_collection WHERE stid = ?", [$stid]);
                $qrow = $q->fetchAll();

                $output["info"] = $qrow;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "history"){
                $riid = $_POST["riid"];
                $rows = [];
                // retrieve the history of items
                $st = DB::run("SELECT * FROM supplies_equipment_transaction st JOIN user_accounts ua ON st.destination_uid = ua.uid WHERE st.riid = ?", [$riid]);
                while($strow = $st->fetch()){
                    $temp["date_issued"] = $strow["created_at"];
                    $temp["name"] = $strow["lname"] . ", " . $strow["fname"] . " " . $strow["midinit"] . ".";
                    if($strow["remarks"] == "Request"){
                        $temp["acquisition"] = "Via Request";
                    }elseif($strow["remarks"] == "Transfer"){
                        $tempData = explode("-", $strow["transaction_status"]);
                        $temp["acquisition"] = "Via Transfer (" . $tempData[1] . ")<br/>(From: " . $tempData[2] . ")";
                    }

                    array_push($rows, $temp);
                }

                $output["info"] = $rows;
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "transfer"){
            if($_POST["operation"] == "getAllItems"){
                $id = $_POST["id"];

                // retrieve transaction details
                $t = DB::run("SELECT * FROM supplies_equipment_transaction st JOIN request_items ri ON st.riid = ri.riid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE stid = ?", [$id]);
                $trow = $t->fetch();

                $output["info"] = [$trow];
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "qr"){
            if($_POST["operation"] == "getItem"){
                $qr_code = $_POST["qr_code"];

                // retrieve item
                $q = DB::run("SELECT * FROM supplies_equipment_transaction_qr_collection qr JOIN supplies_equipment_transaction st ON qr.stid = st.stid JOIN user_accounts ua ON st.destination_uid = ua.uid JOIN request_items ri ON st.riid = ri.riid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE qr.item_number = ?", [$qr_code]);
                if($qrow = $q->fetch()){
                    $output["info"] = $qrow;
                }

                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "ppmp"){
            if($_POST["operation"] == "getAll"){
                // retrieve all ppmps
                $rows = [];

                $p = DB::run("SELECT * FROM ppmp p JOIN user_accounts ua ON p.uid = ua.uid ORDER BY p.ppmp_year ASC");
                while($prow = $p->fetch()){
                    $prow["hash"] = md5($prow["pid"]);
                    array_push($rows, $prow);
                }

                $output["info"] = $rows;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getAllItems"){
                $pid = $_POST["pid"];

                $p = DB::run("SELECT * FROM ppmp_items pi JOIN item_dictionary id ON pi.itemid = id.itemid WHERE pi.pid = ?", [$pid]);
                $prow = $p->fetchAll();

                $output["info"] = $prow;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "consolidate"){
                $app_year = $_POST["app_year"];
                
                // retrieve all unique items
                $r = DB::run("SELECT pi.itemid, SUM(pi.requested_qty) as total, pi.requested_unit, id.item_name, id.item_description FROM ppmp_items pi JOIN ppmp p ON pi.pid = p.pid JOIN item_dictionary id ON id.itemid = pi.itemid WHERE p.ppmp_year = ? GROUP BY pi.itemid", [$app_year]);
                $rrow = $r->fetchAll();

                $output["info"] = $rrow;
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "app"){
            if($_POST["operation"] == "getAll"){
                $rows = [];
                $a = DB::run("SELECT * FROM app ORDER BY app_year");
                while($arow = $a->fetch()){
                    $arow["hash"] = md5($arow["aid"]);
                    array_push($rows, $arow);
                }

                $output["info"] = $rows;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getAllItems"){
                $aid = $_POST["aid"];

                $a = DB::run("SELECT * FROM app_items ai JOIN item_dictionary id ON ai.itemid = id.itemid WHERE ai.aid = ?", [$aid]);
                $arow = $a->fetchAll();

                $output["info"] = $arow;
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "purchase_orders"){
            if($_POST["operation"] == "getAll"){
                $items = [];

                // retrieve all purchase orders
                $p = DB::run("SELECT * FROM purchase_order ORDER BY po_number");
                while($prow = $p->fetch()){
                    $prow["total_amount"] = number_format($prow["total_amount"], 2);
                    $prow["h"] = md5($prow["poid"]);

                    array_push($items, $prow);
                }

                $output["info"] = $items;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getAllItems"){
                $poid = $_POST["poid"];
                $items = [];

                $pi = DB::run("SELECT * FROM purchase_order_items poi JOIN request_items ri ON poi.riid = ri.riid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE poi.poid = ?", [$poid]);
                while($pirow = $pi->fetch()){
                    $pirow["unit_cost"] = number_format($pirow["unit_cost"], 2);
                    $pirow["total_cost"] = number_format($pirow["total_cost"], 2);
                    array_push($items, $pirow);
                }

                $output["info"] = $items;
                $output["msg"] = true;
            }
        }
    }

    echo json_encode($output);
?>