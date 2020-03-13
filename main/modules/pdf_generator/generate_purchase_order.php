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
    $pdf->SetTitle('Purchase Order');
    $pdf->SetSubject('Purchase Order');

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
    $r = DB::run("SELECT * FROM purchase_order WHERE poid = ?", [$_GET["poid"]]);
    $row = $r->fetch();

    $ad = DB::run("SELECT * FROM user_accounts WHERE user_type = 'Regional Director'");
    if($adrow = $ad->fetch()){
        $adname = $adrow["fname"] . " " . $adrow["midinit"] . ". " . $adrow["lname"];
    }else{
        $adname = "(No administrator on your system)";
    }

    // =================================== get the data ===================================

    $pdf->MultiCell( 196, 0, "PURCHASE ORDER", $border = "LTR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 8, $valign = 'B', $fitcell = true);
    $pdf->MultiCell( 196, 0, "Department of Tourism", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 196, 0, "Regional Office No. V, Rawis, Legazpi City", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 8, $valign = 'T', $fitcell = true);
    $pdf->SetFont('dejavusans', 'B', 9, '', true);
    $pdf->MultiCell( 140, 0, "Supplier Name: " . $row["supplier_name"], $border = "LR", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 56, 0, "P.O. No. " . $row["po_number"], $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 140, 0, "Address: " . $row["supplier_address"], $border = "LR", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 56, 0, "Date: " . date("F d, Y", strtotime($row["created_at"])), $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 140, 0, "TIN: ", $border = "LBR", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 56, 0, "Mode of Procurement: ", $border = "LBR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 196, 0, "Gentlemen:", $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 196, 0, "Please furnish this office the following article subject to the term and condition contained herein:", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 140, 0, "Place of Delivery: ", $border = "LR", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 56, 0, "Delivery Term: ", $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 140, 0, "Date of Delivery: ", $border = "LBR", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 56, 0, "Payment Term: ", $border = "LBR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 23, 0, "Stock No.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 20, 0, "Unit", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 80, 0, "Description", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 20, 0, "Quantity", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 23, 0, "Unit Cost", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 30, 0, "Amount", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    // data
    $pi = DB::run("SELECT * FROM purchase_order_items poi JOIN request_items ri ON poi.riid = ri.riid JOIN item_dictionary id ON ri.itemid = id.itemid WHERE poi.poid = ?", [$_GET["poid"]]);

    $totalAmount = 0;
    while($pirow = $pi->fetch()){
        $pdf->MultiCell( 23, 0, "", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 20, 0, $pirow["requested_unit"], $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 80, 0, $pirow["item_name"] . " (" . $pirow["item_description"] . ")", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 20, 0, $pirow["requested_qty"], $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 23, 0, number_format($pirow["unit_cost"], 2), $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 30, 0, number_format($pirow["total_cost"], 2), $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

        $totalAmount += $pirow["total_cost"];
    }

    
    $pdf->MultiCell( 23, 0, "", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 25, $valign = 'T', $fitcell = true);
    $pdf->MultiCell( 20, 0, "", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 25, $valign = 'T', $fitcell = true);
    $pdf->MultiCell( 80, 0, "*** Nothing Follows ***", $border = "LB", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 25, $valign = 'T', $fitcell = true);
    $pdf->MultiCell( 20, 0, "", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 25, $valign = 'T', $fitcell = true);
    $pdf->MultiCell( 23, 0, "", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 25, $valign = 'T', $fitcell = true);
    $pdf->MultiCell( 30, 0, "", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 25, $valign = 'T', $fitcell = true);

    $pdf->MultiCell( 166, 0, "(TOTAL AMOUNT IN WORDS)", $border = "LBR", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 30, 0, number_format($totalAmount, 2), $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 196, 18, "In case of failure to make the full delivery within the time specified above, penalty of one-tenth (1/10) of one percent per day of delay shall be imposed on the undelivered item/s", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 123, 0, "Conforme: ", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, "Very truly yours,", $border = "R", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    
    $pdf->MultiCell( 123, 10, "", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 10, "", $border = "R", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    
    $pdf->MultiCell( 123, 0, $row["supplier_name"], $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, $adname, $border = "R", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    
    $pdf->MultiCell( 123, 0, "Signature Over Printed Name of Supplier", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, "Regional Director", $border = "R", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 123, 0, "Date: " . DB::formatDateTime($row["created_at"]), $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, "Authorized Official", $border = "RB", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    
    $pdf->MultiCell( 123, 10, "Fund Cluster: ______________________________________", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 10, "ORS/BURS No. : _________________________", $border = "RL", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 123, 0, "Funds Available: ___________________________________", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, "Date of the ORS/BURS. : _______________", $border = "RL", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 123, 0, "", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, "Amount: _________________________________", $border = "RL", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    
    $pdf->MultiCell( 123, 0, "Cristian A. Siaton, CPA", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, "", $border = "RL", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 123, 0, "Accountant II", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, "", $border = "RL", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    
    $pdf->MultiCell( 123, 0, "", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, "", $border = "RBL", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    


    $pdf->SetFont('dejavusans', 'R', 9, '', true);

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $filename = "purchase_order_" . $row["po_number"] . "_" . date("Y_m_d_H_i_s") . ".pdf"; 
    $pdf->Output($filename, 'I');
?>