<?php
    include "../../connection/connection.php";
    include "../../tcpdf_master/tcpdf.php";

    session_start();
  
    if(!isset($_SESSION["username"])){
      header("Location: ../../login.php");
    }

    if(isset($_GET["h"])){
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
    $pdf->SetTitle('Requisition and Issue Slip');
    $pdf->SetSubject('Requisition and Issue Slip');

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

    

    // =================================== get the data ===================================
    $ad = DB::run("SELECT * FROM user_accounts WHERE user_type = 'Administrator'");
    if($adrow = $ad->fetch()){
        $adname = $adrow["fname"] . " " . $adrow["midinit"] . ". " . $adrow["lname"];
    }else{
        $adname = "(No administrator on your system)";
    }

    $rd = DB::run("SELECT * FROM user_accounts WHERE user_type = 'Regional Director'");
    if($rdrow = $rd->fetch()){
        $rdname = $rdrow["fname"] . " " . $rdrow["midinit"] . ". " . $rdrow["lname"];
    }else{
        $rdname = "(No administrator on your system)";
    }
    

    $r = DB::run("SELECT * FROM request r JOIN user_accounts ua ON r.uid = ua.uid WHERE r.rid = ?", [$_GET["rid"]]);
    if($row = $r->fetch()){
        $in = DB::run("SELECT * FROM request_items ri JOIN item_dictionary id ON ri.itemid = id.itemid WHERE ri.rid = ?", [$row["rid"]]);
        $par_no = $ics_no = "";
        while($rirow = $in->fetch()){
            // get the last transaction of item
            $t = DB::run("SELECT * FROM supplies_equipment_transaction WHERE riid = ? AND transaction_type = 'Out' AND report_type = 'par' ORDER BY created_at DESC", [$rirow["riid"]]);
            $trow = $t->fetch();
            if($trow !== false){
                $par_no = $trow["report_overall_no"];
            }

            $t = DB::run("SELECT * FROM supplies_equipment_transaction WHERE riid = ? AND transaction_type = 'Out' AND report_type = 'ics' ORDER BY created_at DESC", [$rirow["riid"]]);
            $trow = $t->fetch();
            if($trow !== false){
                $ics_no = $trow["report_overall_no"];
            }
        }

        // generate contents
        // Add a page
        $pdf->AddPage();

        $pdf->SetFont('dejavusans', 'B', 9, '', true);
        
        $pdf->MultiCell( 0, 0, "Republic of the Philippines", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 4, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 0, 0, "DEPARTMENT OF TOURISM", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 4, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 0, 0, "Regional Office 5", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 4, $valign = 'M', $fitcell = true);

        $pdf->ln(5);

        $pdf->MultiCell( 0, 0, "REQUISITION AND ISSUE SLIP", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 4, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 65, 0, "Section: ", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 9, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 66, 0, "Responsibility Center Code: ", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 9, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 65, 0, "RIS No.: " . $ics_no, $border = 1, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 9, $valign = 'M', $fitcell = true);


        // table
        $pdf->MultiCell( 20, 8, "Stock No.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 15, 8, "Unit", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 80, 8, "Description", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 8, "Quantity", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 8, "Quantity", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 31, 8, "Remarks", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        
        // table rows

        $ri = DB::run("SELECT * FROM request_items ri JOIN item_dictionary id ON ri.itemid = id.itemid WHERE ri.rid = ?", [$row["rid"]]);
        $occupied = 0;
        while($rirow = $ri->fetch()){
            // get the last transaction of item
            $t = DB::run("SELECT * FROM supplies_equipment_transaction WHERE riid = ? AND transaction_type = 'Out' ORDER BY created_at DESC", [$rirow["riid"]]);
            $trow = $t->fetch();
            if($trow !== false){
                if($trow["remarks"] != "Transfer"){
                    $pdf->MultiCell( 20, 0, "", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 15, 0, $rirow["requested_unit"], $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 80, 0, $rirow["item_name"] . " (" . $rirow["item_description"] . ")", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 25, 0, $trow["item_qty"], $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 25, 0, $trow["item_qty"], $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    $pdf->MultiCell( 31, 0, "", $border = "R", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                    
                    $occupied++;
                }
            }
        }

        $pdf->MultiCell( 20, 0, "", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 15, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 80, 0, "*** Nothing follows ***", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 31, 0, "", $border = "R", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        
        // adding extra unoccupated rows
        for ($i=0; $i < 2; $i++) { 
            $pdf->MultiCell( 20, 0, "", $border = "L", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 15, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 80, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 25, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 25, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 31, 0, "", $border = "R", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        }

        $pdf->MultiCell( 20, 0, "", $border = "LB", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 15, 0, "", $border = "B", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 80, 0, "", $border = "B", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, "", $border = "B", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, "", $border = "B", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 31, 0, "", $border = "BR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        
        $pdf->MultiCell( 0, 0, "Purpose: " . $row["request_purpose"], $border = 1, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        // for signatories
        $pdf->MultiCell( 35, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 53, 0, "Requested by:", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 52, 0, "Approved by:", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 56, 0, "Issued by:", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 35, 0, "Signature", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 53, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 52, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 56, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 35, 0, "Printed Name", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 53, 0, strtoupper($row["fname"] . " " . $row["midinit"] . ". " . $row["lname"]), $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 52, 0, strtoupper($rdname), $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 56, 0, strtoupper($adname), $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 35, 0, "Designation", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 53, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 52, 0, "Regional Director", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 56, 0, "Supply and Property Officer", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 35, 0, "Date", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 53, 0, date("m/d/Y"), $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 52, 0, date("m/d/Y"), $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 56, 0, date("m/d/Y"), $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        // for signatories 2
        $pdf->MultiCell( 35, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 161, 0, "Requested by:", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 35, 0, "Signature", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 161, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 35, 0, "Printed Name", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 161, 0, strtoupper($row["fname"] . " " . $row["midinit"] . ". " . $row["lname"]), $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 35, 0, "Designation", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 161, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

        $pdf->MultiCell( 35, 0, "Date", $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 161, 0, date("m/d/Y"), $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    }
    // =================================== get the data ===================================
    


    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $name = strtolower($row["fname"] . "_" . $row["mname"] . "_" . $row["lname"]);
    $filename = "ris_" . $name . "_" . date("Y_m_d") . ".pdf"; 
    $pdf->Output($filename, 'I');
?>