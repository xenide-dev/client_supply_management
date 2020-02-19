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

    echo json_encode($output);
?>