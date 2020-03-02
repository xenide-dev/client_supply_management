<?php
    include "../../connection/connection.php";
    include "../../tcpdf_master/tcpdf.php";

    session_start();
  
    if(!isset($_SESSION["username"])){
      header("Location: ../../login.php");
    }

    if(isset($_GET["rid"]) && isset($_GET["h"]) && isset($_GET["type"])){
        if($_GET["h"] != md5($_GET["rid"])){
            header("Location: ../../index.php");
        }
    }else{
        header("Location: ../../index.php");
    }

    // ---------------------------------------------------------

    // create new PDF document
    $pdf = new TCPDF("P", PDF_UNIT, 'LEGAL', true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Department of Tourism');
    $pdf->SetTitle('Issuance Report');
    $pdf->SetSubject('Issuance Report');

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins("10", "10", "10");
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, "13");

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set default font subsetting mode
    $pdf->setFontSubsetting(true);

    // turn off
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

    $pdf->SetFont('dejavusans', 'B', 9, '', true);

    // =================================== get the data ===================================
    $r = DB::run("SELECT * FROM request r JOIN user_accounts ua ON r.uid = ua.uid WHERE r.rid = ?", [$_GET["rid"]]);
    $row = $r->fetch();

    $ad = DB::run("SELECT * FROM user_accounts WHERE user_type = 'Administrator'");
    if($adrow = $ad->fetch()){
        $adname = $adrow["fname"] . " " . $adrow["midinit"] . ". " . $adrow["lname"];
    }else{
        $adname = "(No administrator on your system)";
    }

    $in = DB::run("SELECT * FROM request_items ri JOIN item_dictionary id ON ri.itemid = id.itemid WHERE ri.rid = ?", [$_GET["rid"]]);
    $par_no = "";
    while($rirow = $in->fetch()){
        // get the last transaction of item
        $t = DB::run("SELECT * FROM supplies_equipment_transaction WHERE riid = ? AND transaction_type = 'Out' AND report_type = 'par' ORDER BY created_at DESC", [$rirow["riid"]]);
        $trow = $t->fetch();
        if($trow !== false){
            $par_no = $trow["report_overall_no"];
        }
    }
    // =================================== get the data ===================================

    if($_GET["type"] == "par"){
        $pdf->MultiCell( 0, 0, "PROPERTY ACKNOWLEDGEMENT RECEIPT", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 0, 0, "Entity Name: DEPARTMENT OF TOURISM REGION V", $border = 0, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 30, 0, "Fund Cluster: ", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 65, 0, "", $border = "B", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 51, 0, "", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 20, 0, "PAR No.:", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 30, 0, $par_no, $border = 0, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->ln();

        // table
        $pdf->MultiCell( 20, 8, "Quantity", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 15, 8, "Unit", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 86, 8, "Description", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 8, "Property Number", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 8, "Date Acquired", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 8, "Amount", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        // table rows

        $ri = DB::run("SELECT * FROM request_items ri JOIN item_dictionary id ON ri.itemid = id.itemid WHERE ri.rid = ?", [$_GET["rid"]]);
        $occupied = 0;
        while($rirow = $ri->fetch()){
            // get the last transaction of item
            $t = DB::run("SELECT * FROM supplies_equipment_transaction WHERE riid = ? AND transaction_type = 'Out' AND report_type = 'par' ORDER BY created_at DESC", [$rirow["riid"]]);
            $trow = $t->fetch();
            if($trow !== false){
                if($trow["remarks"] != "Transfer"){
                    $pdf->MultiCell( 20, 0, $trow["item_qty"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 15, 0, $rirow["requested_unit"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 86, 0, $rirow["item_name"] . " (" . $rirow["item_description"] . ")", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 25, 0, $trow["report_item_no"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 25, 0, $trow["created_at"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 25, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $occupied++;
                }
            }
        }

        // adding extra unoccupated rows
        for ($i=0; $i < 42 - $occupied; $i++) { 
            $pdf->MultiCell( 20, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 15, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 86, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 25, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 25, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 25, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        }
        
        // for signatories
        $pdf->MultiCell( 121, 0, "", $border = "LRT", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "", $border = "LRT", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        
        $pdf->MultiCell( 35, 0, "Received by:", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 86, 0, "", $border = "R", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, "Issued By:", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 50, 0, "", $border = "R", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 121, 0, "", $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, $row["fname"] . " " . $row["midinit"] . ". " . $row["lname"], $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, $adname, $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, "Signature over printed name", $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, "", $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, "Position/Office", $border = "LBR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);


    }else{
        $pdf->MultiCell( 0, 0, "INVENTORY CUSTODIAN SLIP", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 0, 0, "Entity Name: DEPARTMENT OF TOURISM REGION V", $border = 0, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 30, 0, "Fund Cluster: ", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 65, 0, "", $border = "B", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 51, 0, "", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 20, 0, "ICS No.:", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 30, 0, $par_no, $border = 0, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->ln();

        // table
        $pdf->MultiCell( 20, 8, "Quantity", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 15, 8, "Unit", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 20, 8, "Unit Cost", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 20, 8, "Total Cost", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 71, 8, "Description", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 8, "Inventory Number", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 8, "Estimated Useful Life", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        // table rows

        $ri = DB::run("SELECT * FROM request_items ri JOIN item_dictionary id ON ri.itemid = id.itemid JOIN purchase_order_items poi ON ri.riid = poi.riid WHERE ri.rid = ?", [$_GET["rid"]]);
        $occupied = 0;
        $date_issued = "";
        while($rirow = $ri->fetch()){
            // get the last transaction of item
            $t = DB::run("SELECT * FROM supplies_equipment_transaction WHERE riid = ? AND transaction_type = 'Out' AND report_type = 'ics' ORDER BY created_at DESC", [$rirow["riid"]]);
            $trow = $t->fetch();
            if($trow !== false){
                if($trow["remarks"] != "Transfer"){
                    $date_issued = date("d-M-Y", strtotime($trow["created_at"]));
                    $pdf->MultiCell( 20, 8, $trow["item_qty"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 15, 8, $rirow["requested_unit"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 20, 8, $rirow["unit_cost"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 20, 8, $rirow["total_cost"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 71, 8, $rirow["item_name"] . "\n(" . $rirow["item_description"] . ")", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 25, 8, $trow["report_item_no"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 25, 8, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

                    $occupied++;
                }
            }
        }

        // adding extra unoccupated rows
        for ($i=0; $i < 40 - $occupied; $i++) {
            $pdf->MultiCell( 20, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 15, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 20, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 20, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 71, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 25, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 25, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        }
        
        // for signatories
        $pdf->MultiCell( 121, 0, "", $border = "LRT", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "", $border = "LRT", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        
        $pdf->MultiCell( 35, 0, "Received from:", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 86, 0, "", $border = "R", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, "Received by:", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 50, 0, "", $border = "R", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 121, 0, "", $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, $adname, $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, $row["fname"] . " " . $row["midinit"] . ". " . $row["lname"], $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, "Signature Over Printed Name", $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "Signature Over Printed Name", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, "Supply & Property Officer", $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, "Position/Office", $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "Position/Office", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, $date_issued, $border = "LR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, $date_issued, $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 121, 0, "Date", $border = "LBR", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 75, 0, "Date", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    }

    


    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    if($_GET["type"] == "par"){
        $filename = "par_" . date("Y_m_d_H_i_s") . ".pdf"; 
    }else{
        $filename = "ics_" . date("Y_m_d_H_i_s") . ".pdf"; 
    }
    $pdf->Output($filename, 'I');
?>