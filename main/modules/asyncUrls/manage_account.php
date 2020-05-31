<?php
    include '../../connection/connection.php';

    $output["msg"] = false;

    if(isset($_REQUEST["uid"])){
        // retrieve user info
        $r = DB::run("SELECT * FROM user_accounts WHERE uid = ?", [$_REQUEST["uid"]]);
        if($row = $r->fetch()){
            $output["info"] = $row;
            $output["msg"] = true;
        }
    }

    if(isset($_POST["type"])){
        if($_POST["type"] == "account"){
            if($_POST["operation"] == "status"){
                $status = $_POST["status"];
                $uid = $_POST["uid"];
                
                $isActive = ($status == 'deactivate') ? 0 : 1;

                $u = DB::run("UPDATE user_accounts SET isActive = ? WHERE uid = ?", [$isActive, $uid]);
                if($u->rowCount() > 0){
                    $output["msg"] = true;
                }
            }
        }
    }

    echo json_encode($output);
?>