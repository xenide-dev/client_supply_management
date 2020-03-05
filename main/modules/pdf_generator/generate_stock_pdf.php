<?php
    include "../../connection/connection.php";
    include "../../tcpdf_master/tcpdf.php";

    session_start();
  
    if(!isset($_SESSION["username"])){
      header("Location: ../../login.php");
    }

    if(isset($_GET["itemid"]) && isset($_GET["h"])){
        if($_GET["h"] != md5($_GET["itemid"])){
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
    $pdf->SetTitle('Stock Card');
    $pdf->SetSubject('Stock Card');

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
    $r = DB::run("SELECT * FROM item_dictionary id JOIN item_category ic ON id.catid = ic.catid WHERE id.itemid = ?", [$_GET["itemid"]]);
    $row = $r->fetch();

    $e = DB::run("SELECT * FROM supplies_equipment WHERE itemid = ?", [$_GET["itemid"]]);
    $erow = $e->fetch();
    // =================================== get the data ===================================

    $pdf->MultiCell( 0, 8, "Republic of the Philippines", $border = "LTR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->SetFont('dejavusans', 'B', 9, '', true);
    $pdf->MultiCell( 0, 0, "DEPARTMENT OF TOURISM", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->SetFont('dejavusans', 'B', 8, '', true);
    $pdf->MultiCell( 0, 0, "Regional Office No. V", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 0, 0, "Rawis, Legazpi City", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 0, 12, "STOCK CARD", $border = "LBR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'B', $fitcell = true);
    $pdf->MultiCell( 65, 0, "Cluster: " . $row["cat_name"], $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 9, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 66, 0, "Description: ", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 9, $valign = 'T', $fitcell = true);
    $pdf->MultiCell( 65, 0, "Stock No.: ", $border = "LR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 9, $valign = 'T', $fitcell = true);
    $pdf->MultiCell( 65, 0, "Item: " . $row["item_name"], $border = "LB", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 9, $valign = 'T', $fitcell = true);
    $pdf->MultiCell( 66, 0, $row["item_description"], $border = "LB", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 9, $valign = 'T', $fitcell = true);
    $pdf->MultiCell( 65, 0, "Reorder Point: " . $erow["reorder_point"] . "%", $border = "LBR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 9, $valign = 'T', $fitcell = true);

    // header
    $pdf->MultiCell( 21, 10, "Date" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 22, 10, "Reference" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 22, 0, "Receipt" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 66, 0, "Issuance" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 20, 0, "Balance" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 45, 0, "No. of Days to Consume" , $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 43, 0, "" , $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 22, 0, "Quantity" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 22, 0, "Quantity" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 44, 0, "Office/Name" , $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 20, 0, "Quantity" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 45, 0, "" , $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    // rows
    $ei = DB::run("SELECT * FROM supplies_equipment_transaction st LEFT JOIN request_items ri ON st.riid = ri.riid LEFT JOIN user_accounts ua ON st.destination_uid = ua.uid WHERE st.sid = ? OR ri.itemid = ? ORDER BY created_at ASC", [$erow["sid"], $_GET["itemid"]]);
    $curBal = 0;
    while($eirow = $ei->fetch()){
        
        $pdf->MultiCell( 21, 0, DB::formatDateTime($eirow["created_at"]) , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 22, 0, "Stockroom" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        // check if in
        if($eirow["transaction_type"] == "In"){
            // get the quantity
            $g = DB::run("SELECT * FROM purchase_order_items poi JOIN request_items ri ON poi.riid = ri.riid WHERE ri.itemid = ?", [$_GET["itemid"]]);
            $grow = $g->fetch();
            $curBal += $grow["requested_qty"];

            $pdf->MultiCell( 22, 0, $grow["requested_qty"] , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 22, 0, "" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 44, 0, "" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 20, 0, $curBal , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 45, 0, "" , $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        }else{
            if($eirow["remarks"] != "Transfer"){
                $curBal -= $eirow["item_qty"];
            }
            $pdf->MultiCell( 22, 0, "" , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 22, 0, $eirow["item_qty"] , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 44, 0, $eirow["fname"] . " " . $eirow["midinit"] . " " . $eirow["lname"] , $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 20, 0, $curBal , $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 45, 0, "" , $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
        }
    }




    $pdf->ln(3);


    $pdf->ln(5);
    

    
    $pdf->SetFont('dejavusans', 'R', 9, '', true);

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $filename = "stock_card_" . $row["item_code"] . "_" . date("Y_m_d_H_i_s") . ".pdf"; 
    $pdf->Output($filename, 'I');
?>