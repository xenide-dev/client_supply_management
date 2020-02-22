<?php
    include '../../connection/connection.php';

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
                    if($request_type == "Requisition"){
                        $status = "Processing";
                    }elseif($request_type == "Purchase Request"){
                        $status = "Approved";
                    }
                }else{
                    $status = $action;
                }

                // update the request entry status
                DB::run("UPDATE request SET status = ? WHERE rid = ?", [$status, $rid]);

                // update the previous trace
                DB::run("UPDATE request_tracer SET destination_uid = ?, status = ? WHERE tid = ?", [$uid, $status, $tid]);

                // create another trace entry
                DB::run("INSERT request_tracer(tracer_no, rid, source_uid, destination_uid_type, status) VALUES(?, ?, ?, ?, ?)", [intval($tracer_no) + 1, $rid, $uid, 'Administrator', 'Pending']);

                $output["msg"] = true;
            }
        }
    }

    echo json_encode($output);
?>