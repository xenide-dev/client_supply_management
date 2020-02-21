<?php
    include '../../../connection/connection.php';

    $output["msg"] = false;
    if(isset($_POST["type"])){
        if($_POST["type"] == "item"){
            if($_POST["operation"] == "getAll"){
                $r = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid ORDER BY id.item_name ASC");
                $row = $r->fetchAll();
                $output["info"] = $row;
                $output["msg"] = true;
            }elseif($_POST["operation"] == "get"){
                $id = $_POST["id"];

                $r = DB::run("SELECT * FROM item_dictionary id LEFT JOIN item_category ic ON id.catid = ic.catid WHERE itemid = ?", [$id]);
                $row = $r->fetch();
                $output["info"] = $row;
                $output["msg"] = true;
            }
        }
    }

    echo json_encode($output);
?>