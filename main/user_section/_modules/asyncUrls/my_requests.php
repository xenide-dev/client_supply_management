<?php
    include '../../../connection/connection.php';
    session_start();

    $output["msg"] = false;
    if(isset($_POST["type"])){
        if($_POST["type"] == "request"){
            if($_POST["operation"] == "processDeliver"){
                $rid = $_POST["rid"];
                $action = $_POST["action"];
                
                // update request table
                DB::run("UPDATE request SET status = 'Delivered', updated_at = ? WHERE rid = ?", [DB::getCurrentDateTime(), $rid]);

                // get the last trace record
                $t = DB::run("SELECT * FROM request_tracer WHERE rid = ? ORDER BY tracer_no DESC", [$rid]);
                $trow = $t->fetch();
                $tracer_no = $trow["tracer_no"];

                // create trace entry
                DB::run("INSERT INTO request_tracer(tracer_no, rid, source_uid, destination_uid_type, status) VALUES(?, ?, ?, ?, ?)", [intval($tracer_no) + 1, $rid, $_SESSION["uid"], 'User', 'Delivered']);

                $output["msg"] = true;
            }
        }
    }

    
    echo json_encode($output);
?>