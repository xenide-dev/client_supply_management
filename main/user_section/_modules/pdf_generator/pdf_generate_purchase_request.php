<?php
    include "../../../connection/connection.php";
    include "../../../tcpdf_master/tcpdf.php";

    session_start();
  
    if(!isset($_SESSION["username"])){
      header("Location: ../../../login.php");
    }

    if(isset($_GET["rid"]) && isset($_GET["h"])){
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
    $pdf->SetTitle('Purchase Request');
    $pdf->SetSubject('Purchase Request');

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

    // get the data
    $r = DB::run("SELECT * FROM request r JOIN user_accounts ua ON r.uid = ua.uid WHERE r.rid = ?", [$_GET["rid"]]);
    $row = $r->fetch();

    $rd = DB::run("SELECT * FROM user_accounts WHERE user_type = 'Regional Director'");
    if($rdrow = $rd->fetch()){
        $rdname = $rdrow["fname"] . " " . $rdrow["midinit"] . ". " . $rdrow["lname"];
    }else{
        $rdname = "(No regional director user on your system)";
    }

    // get the data

    $pdf->MultiCell( 193, 0, "", $border = "LTR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->SetFont('dejavusans', 'B', 11, '', true);
    $pdf->MultiCell( 193, 0, "PURCHASE REQUEST", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->SetFont('dejavusans', 'B', 9, '', true);
    $pdf->MultiCell( 193, 0, "Department of Tourism", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 193, 0, "Regional Office No. V, Rawis, Legazpi City", $border = "LR", $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 193, 0, "", $border = "LBR", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    
    $pdf->MultiCell( 25, 0, "Department:", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 58, 0, "", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 20, 0, "PR No.:", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 30, 0, $row["request_no"], $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 20, 0, "Date:", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 40, 0, date("F d, Y", strtotime($row["created_at"])), $border = "R", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 25, 0, "Section:", $border = "L", $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 58, 0, "", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 20, 0, "SAI No.:", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 30, 0, "", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 20, 0, "Date:", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 40, 0, "", $border = "R", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 25, 0, "Stock No.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 25, 0, "Unit", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 70, 0, "Item Description", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 25, 0, "Quantity", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 25, 0, "Cost", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 23, 0, "Total Amount", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $ri = DB::run("SELECT * FROM request_items ri JOIN item_dictionary id ON ri.itemid = id.itemid WHERE ri.rid = ?", [$row["rid"]]);
    while($rirow = $ri->fetch()){
        $pdf->MultiCell( 25, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, $rirow["requested_unit"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 70, 0, $rirow["item_name"] . "(" . $rirow["item_description"] . ")", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, $rirow["requested_qty"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 23, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    }

    $pdf->MultiCell( 193, 25, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 193, 0, "Purpose: " . $row["request_purpose"], $border = 1, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 193, 0, "REQUESTED BY:", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    
    $pdf->MultiCell( 50, 10, "Signature", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 143, 10, "", $border = 1, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 50, 0, "Printed Name", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 143, 0, $row["fname"] . " " . $row["midinit"] . ". " . $row["lname"], $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 50, 0, "Designation", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 143, 0, "", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 193, 0, "APPROVED BY:", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->MultiCell( 50, 10, "Signature", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 143, 10, "", $border = 1, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 50, 0, "Printed Name", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 143, 0, $rdname, $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 50, 0, "Designation", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 143, 0, "Regional Director", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);



    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output('purchase_request.pdf', 'I');
?>