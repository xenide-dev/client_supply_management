<?php
    include "../../connection/connection.php";
    include "../../tcpdf_master/tcpdf.php";

    session_start();
  
    if(!isset($_SESSION["username"])){
      header("Location: ../../login.php");
    }

    if(isset($_GET["poid"]) && isset($_GET["h"])){
        if($_GET["h"] != md5($_GET["poid"])){
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
    $pdf->SetTitle('Inspection and Acceptance');
    $pdf->SetSubject('Inspection and Acceptance');

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

    $pdf->SetFont('dejavusans', 'B', 8, '', true);

    // =================================== get the data ===================================
    $r = DB::run("SELECT * FROM purchase_order po JOIN request r ON po.rid = r.rid WHERE po.poid = ?", [$_GET["poid"]]);
    $row = $r->fetch();

    // =================================== get the data ===================================
    
    $pdf->SetFont('dejavusans', 'B', 11, '', true);
    $pdf->MultiCell( 196, 8, "INSPECTION AND ACCEPTANCE", $border = "LTR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->SetFont('dejavusans', 'B', 8, '', true);
    $pdf->MultiCell( 196, 0, "Department of Tourism", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 196, 8, "Regional Office No. V, Rawis, Legazpi City", $border = "LRB", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'T', $fitcell = true);

    $pdf->MultiCell( 196, 0, "", $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 98, 0, "Supplier: " . $row["supplier_name"], $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 98, 0, "No.: ", $border = "R", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 98, 0, "P.O. No.: " . $row["po_number"], $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 98, 0, "Date: " . date("F d, Y", strtotime($row["created_at"])), $border = "R", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 98, 0, "Requisitioning Office/Department: DOT V", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 98, 0, "Mode of Procurement:", $border = "R", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 196, 0, "", $border = "LRB", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 25, 10, "UNIT", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 146, 10, "DESCRIPTION", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 25, 10, "QUANTITY", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 25, 10, "", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 96, 10, "", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 25, 10, "Unit Price", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 25, 10, "SUBTOTAL", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 25, 10, "", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    // data
    $pi = DB::run("SELECT * FROM purchase_order_items poi JOIN request_items ri ON poi.riid = ri.riid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE poi.poid = ?", [$row["poid"]]);
    $totalAmount = 0;
    while($pirow = $pi->fetch()){
        if($pirow["isDelivered"] === 0){
            $pdf->SetTextColor(255, 0, 0);
        }else{
            $pdf->SetTextColor(0, 0, 0);
        }
        $pdf->MultiCell( 25, 0, $pirow["requested_unit"], $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 96, 0, $pirow["item_name"] . " (" . $pirow["item_description"] . ")", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, number_format($pirow["unit_cost"], 2, ".", ","), $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, number_format($pirow["total_cost"], 2), $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, $pirow["requested_qty"], $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

        $totalAmount += $pirow["total_cost"];
    }
    $pdf->SetTextColor(0, 0, 0);


    $pdf->MultiCell( 25, 15, "", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->MultiCell( 96, 15, "GRAND TOTAL AMOUNT(in PHP)", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->MultiCell( 25, 15, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->MultiCell( 25, 15, number_format($totalAmount, 2), $border = "B", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->MultiCell( 25, 15, "", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);

    // get purpose
    $r = DB::run("SELECT * FROM request WHERE rid = ?", [$row["rid"]]);
    $rrow = $r->fetch();

    $pdf->MultiCell( 25, 10, "", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 146, 10, "For: " . $rrow["request_purpose"], $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 25, 10, "", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    
    $pdf->MultiCell( 96, 0, "INSPECTION", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 100, 0, "ACCEPTANCE", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    
    // get inspection
    $i = DB::run("SELECT * FROM request_tracer WHERE rid = ? AND destination_uid_type = 'Inspector'", [$row["rid"]]);
    $irow = $i->fetch();

    
    if($irow["status"] == "Inspected"){
        $pdf->MultiCell( 96, 10, "Date Inspected: " . date("F d, Y", strtotime($irow["created_at"])), $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

        // get accepted
        $a = DB::run("SELECT * FROM request_tracer WHERE rid = ? AND destination_uid_type = 'Administrator' ORDER BY tracer_no DESC", [$row["rid"]]);
        $arow = $a->fetch();
        if($arow["status"] == "Accepted"){
            $pdf->MultiCell( 100, 10, "Date Received: " . date("F d, Y", strtotime($arow["created_at"])), $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        }else{
            $pdf->MultiCell( 100, 10, "Date Received: N/A", $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        }
    }else{
        $pdf->MultiCell( 96, 10, "Date Inspected: N/A", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 100, 10, "Date Received: N/A", $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    }

    $pdf->MultiCell( 96, 10, "* Inspected, verified and found in order as to quantity and specifications", $border = "L", $align = 'J', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 100, 10, "* Complete \n* Partial", $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    
    // get inspector
    $ins = DB::run("SELECT * FROM user_accounts WHERE uid = ?", [$_SESSION["uid"]]);
    $insrow = $ins->fetch();

    // get admin
    $ad = DB::run("SELECT * FROM user_accounts WHERE user_type = 'Administrator'");
    $adrow = $ad->fetch();

    $pdf->MultiCell( 96, 10, $insrow["fname"] . " " . $insrow["midinit"] . ". " . $insrow["lname"], $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->MultiCell( 100, 10, $adrow["fname"] . " " . $adrow["midinit"] . ". " . $adrow["lname"], $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->MultiCell( 96, 0, "Inspection Officer", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->MultiCell( 100, 0, "Supply & Property Officer", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);


    $pdf->ln(3);


    $pdf->ln(5);
    

    
    $pdf->SetFont('dejavusans', 'R', 9, '', true);

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $po_no = $row["po_number"];
    $filename = "inspection_" . $po_no . "_" . date("Y_m_d_H_i_s") . ".pdf"; 
    $pdf->Output($filename, 'I');
?>