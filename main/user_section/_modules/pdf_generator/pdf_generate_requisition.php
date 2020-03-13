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
    $pdf->SetMargins("10", "35", "10");
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

    $pdf->Image( "../../../images/logopdf.png", $x = 15, $y = 5, $w = 25, $h = 25, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false, $alt = false, $altimgs = array() );

    $pdf->Image( "../../../images/logo-1.png", $x = 160, $y = 10, $w = 45, $h = 16, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false, $alt = false, $altimgs = array() );

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

    $pdf->MultiCell( 40, 0, "Name of Employee:", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 73, 0, $row["fname"] . " " . $row["mname"] . " " . $row["lname"], $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 40, 0, "Date of Request:", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 40, 0, date("d M. Y", strtotime($row["created_at"])), $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 40, 0, "Section:", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 153, 0, "N/A", $border = 0, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 40, 0, "Purpose:", $border = 0, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 153, 0, $row["request_purpose"], $border = 0, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->ln();

    $pdf->MultiCell( 193, 0, "REQUISITION SLIP", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->ln();

    $pdf->MultiCell( 25, 0, "Quantity", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 25, 0, "Unit", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 143, 0, "Particulars", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    // items
    $ri = DB::run("SELECT * FROM request_items ri JOIN item_dictionary id ON ri.itemid = id.itemid WHERE ri.rid = ?", [$row["rid"]]);
    while($rrow = $ri->fetch()){
        $pdf->MultiCell( 25, 0, $rrow["requested_qty"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 25, 0, $rrow["requested_unit"], $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
        $pdf->MultiCell( 143, 0, $rrow["item_name"] . " (" . $rrow["item_description"] . ")", $border = 1, $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    }

    $pdf->ln(10);

    $pdf->MultiCell( 50, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 93, 0, "", $border = "B", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 50, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 193, 0, "Employee's Signature", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->ln(5);

    $pdf->MultiCell( 193, 0, "Approved by:", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

    $pdf->ln(5);

    $pdf->MultiCell( 50, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 93, 0, "", $border = "B", $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 50, 0, "", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 193, 0, $rdname, $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
    $pdf->MultiCell( 193, 0, "Regional Director", $border = 0, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);



    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output('purchase_request.pdf', 'I');
?>