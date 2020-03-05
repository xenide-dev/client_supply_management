<?php
    include '../../connection/connection.php';
    session_start();

    $output["msg"] = false;
    if(isset($_POST["type"])){
        if($_POST["type"] == "requests"){
            if($_POST["operation"] == "request_per_month"){
                $rows = [];

                // for requisition
                $rdata = [];
                for ($i=1; $i <= 12; $i++) { 
                    $m = ($i < 10) ? "0" . $i : $i;
                    $my = date("Y") . "-" . $m;
                    $r = DB::run("SELECT COUNT(*) as total FROM request WHERE created_at LIKE ? AND request_type = 'Requisition'", ["%" . $my . "%"]);
                    if($rrow = $r->fetch()){
                        array_push($rdata, $rrow["total"]);
                    }else{
                        array_push($rdata, 0);
                    }
                }
                $temp["title"] = "Requisition";
                $temp["data"] = $rdata;
                array_push($rows, $temp);

                // for purchase request
                $pdata = [];
                for ($i=1; $i <= 12; $i++) { 
                    $m = ($i < 10) ? "0" . $i : $i;
                    $my = date("Y") . "-" . $m;
                    $p = DB::run("SELECT COUNT(*) as total FROM request WHERE created_at LIKE ? AND request_type = 'Purchase Request'", ["%" . $my . "%"]);
                    if($prow = $p->fetch()){
                        array_push($pdata, $prow["total"]);
                    }else{
                        array_push($pdata, 0);
                    }
                }
                $temp["title"] = "Purchase Request";
                $temp["data"] = $pdata;
                array_push($rows, $temp);

                // for transfer
                $tdata = [];
                for ($i=1; $i <= 12; $i++) { 
                    $m = ($i < 10) ? "0" . $i : $i;
                    $my = date("Y") . "-" . $m;
                    $t = DB::run("SELECT COUNT(*) as total FROM supplies_equipment_transaction WHERE created_at LIKE ? AND transaction_status LIKE '%Transfer%'", ["%" . $my . "%"]);
                    if($trow = $t->fetch()){
                        array_push($tdata, $trow["total"]);
                    }else{
                        array_push($tdata, 0);
                    }
                }
                $temp["title"] = "Transfer";
                $temp["data"] = $tdata;
                array_push($rows, $temp);




                $output["info"] = $rows;
                $output["msg"] = true;
            }
        }
    }

    echo json_encode($output);
?>