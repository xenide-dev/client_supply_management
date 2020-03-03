<?php
    include "../../connection/connection.php";
    include "../../tcpdf_master/tcpdf.php";

    session_start();
  
    if(!isset($_SESSION["username"])){
      header("Location: ../../login.php");
    }

    if(isset($_GET["pid"]) && isset($_GET["h"])){
        if($_GET["h"] != md5($_GET["pid"])){
            header("Location: ../../index.php");
        }
    }else{
        header("Location: ../../index.php");
    }

    // ---------------------------------------------------------

    // create new PDF document
    $pdf = new TCPDF("L", PDF_UNIT, 'LEGAL', true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Department of Tourism');
    $pdf->SetTitle('PPMP');
    $pdf->SetSubject('PPMP');

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
    $r = DB::run("SELECT * FROM ppmp p JOIN user_accounts ua ON p.uid = ua.uid WHERE p.pid = ?", [$_GET["pid"]]);
    $row = $r->fetch();

    $fullname = $row["lname"] . ", " . $row["fname"] . " " . $row["midinit"] . ".";
    // =================================== get the data ===================================

    $pdf->MultiCell( 0, 0, "DEPARTMENT OF TOURISM REGION V", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->SetFont('dejavusans', 'I', 9, '', true);
    $pdf->MultiCell( 0, 0, "Legazpi City", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->SetFont('dejavusans', 'B', 9, '', true);

    $pdf->ln(5);
    
    $pdf->MultiCell( 0, 0, "PROJECT PROCUREMENT MANAGEMENT PLAN (PPMP)", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    
    $pdf->ln(5);
    
    $pdf->MultiCell( 0, 0, "END-USER/UNIT: " . $fullname, $border = 0, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    $pdf->ln(3);

    $pdf->MultiCell( 91, 10, "General Description", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 40, 10, "Quantity (Unit)", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 204, 0, "Schedule/Milestone of Activities", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 131, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Jan.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Feb.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Mar.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Apr.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "May.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Jun.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Jul.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Aug.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Sep.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Oct.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Nov.", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 17, 0, "Dec.", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);

    // retrieve all items
    $pi = DB::run("SELECT * FROM ppmp_items pi JOIN item_dictionary id ON pi.itemid = id.itemid WHERE pi.pid = ?", [$_GET["pid"]]);
    while($pirow = $pi->fetch()){
        $pdf->MultiCell( 91, 0, $pirow["item_name"] . " (" . $pirow["item_description"] . ")", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 40, 0, $pirow["requested_qty"] . " (" . $pirow["requested_unit"] . ")", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_jan"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_feb"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_mar"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_apr"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_may"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_jun"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_jul"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_aug"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_sep"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_oct"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_nov"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 17, 0, $pirow["mon_dec"], $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    }

    $pdf->ln(5);
    
    $pdf->MultiCell( 0, 0, "Prepared By: ", $border = 0, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 100, 10, $fullname, $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 100, 0, "", $border = "T", $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 5, $valign = 'M', $fitcell = true);


    
    $pdf->SetFont('dejavusans', 'R', 9, '', true);

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $filename = "ppmp_" . $row["ppmp_year"] . "_" . $row["lname"] . "_" . $row["fname"] . "_" . $row["midinit"] . "_" . date("Y_m_d_H_i_s") . ".pdf"; 
    $pdf->Output($filename, 'I');
?>