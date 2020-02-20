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
            }
        }
    }

    echo json_encode($output);
?>