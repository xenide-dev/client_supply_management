<?php
    include '../../connection/connection.php';

    $output["msg"] = false;
    if(isset($_POST["type"])){
        if($_POST["type"] == "supplies"){
            if($_POST["operation"] == "getAll"){
                $r = DB::run("SELECT * FROM supplies_equipment se JOIN item_dictionary id ON se.itemid = id.itemid ORDER BY item_name ASC");
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
                // retrieve all request
                $r = DB::run("SELECT * FROM request r JOIN user_accounts u ON r.uid = u.uid ORDER BY r.created_at ASC");
                $row = $r->fetchAll();
                $output["info"] = $row;
                $output["msg"] = true;
            }
        }elseif($_POST["type"] == "purchase"){
            if($_POST["operation"] == "getAll"){
                // retrieve all purchase orders
                $r = DB::run("SELECT * FROM purchase_order po JOIN request r ON po.rid = r.rid JOIN user_accounts ua ON r.uid = ua.uid WHERE po.status = 'Pending' ORDER BY po.created_at ASC");
                $row = $r->fetchAll();
                $output["info"] = $row;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "getAllItems"){
                $id = $_POST["id"];
                $r = DB::run("SELECT * FROM purchase_order_items poi JOIN request_items ri ON poi.riid = ri.riid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE poi.poid = ?", [$id]);
                $row = $r->fetchAll();
                $output["info"] = $row;
                $output["msg"] = true;
            }
        }
    }

    echo json_encode($output);
?>