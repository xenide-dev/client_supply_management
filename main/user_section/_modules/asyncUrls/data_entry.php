<?php
    include '../../../connection/connection.php';
    session_start();

    $output["msg"] = false;
    
    if(isset($_POST["type"])){
        if($_POST["type"] == "account"){
            if($_POST["operation"] == "update"){
                $fname = $_POST["fname"];
                $mname = $_POST["mname"];
                $lname = $_POST["lname"];
                $birthdate = $_POST["birthdate"];
                $gender = $_POST["gender"];
                $citizenship = $_POST["citizenship"];
                $religion = $_POST["religion"];
                $address = $_POST["address"];
                $contact_mobile = $_POST["contact_mobile"];
                $contact_email = $_POST["contact_email"];

                $u = DB::run("UPDATE user_accounts SET fname = ?, mname = ?, lname = ?, birthdate = ?, gender = ?, citizenship = ?, religion = ?, address = ?, contact_mobile = ?, contact_email = ? WHERE uid = ?", [$fname, $mname, $lname, $birthdate, $gender, $citizenship, $religion, $address, $contact_mobile, $contact_email, $_SESSION["uid"]]);
                if($u->rowCount() > 0){
                    $output["msg"] = true;
                }
            }
        }elseif($_POST["type"] == "equipments"){
            if($_POST["operation"] == "report"){
                $riid = $_POST["riid"];
                $status = $_POST["statusReport"];

                if($status == "Serviceable"){
                    $status = null;
                }
                // TODOIMP: FOR EQUIPMENT'S STATUS
                $s = DB::run("UPDATE supplies_equipment_transaction SET item_status = ? WHERE riid = ?", [$status, $riid]);
                if($s->rowCount() > 0){
                    $output["msg"] = true;
                }
            }
        }
    }

    echo json_encode($output);
?>