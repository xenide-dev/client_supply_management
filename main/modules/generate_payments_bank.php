<?php
    include "../connection/connection.php";
    include "../tcpdf_master/tcpdf.php";

    if(isset($_GET["bank"]) && isset($_GET["mon"]) && isset($_GET["yr"]) && isset($_GET["fund_type"])){
        if($_GET["bank"] != "" && $_GET["mon"] != "" && $_GET["yr"] != "" && $_GET["fund_type"] != ""){
            $rb = DB::run("SELECT * FROM adj_deduction WHERE de_id = ?", [$_GET["bank"]]);
            $bank = $rb->fetch()["de_name"];

            // Extend the TCPDF class to create custom Header and Footer
            class MYPDF extends TCPDF {
                //Page header
                public function Header() {
                    global $bank;
                    // Logo
                    $image_file = "../images/logo.jpg";
                    $this->Image($image_file, 9, 3, 20, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                    // Set font
                    $this->SetFont('helvetica', 'B', 12);

                    // ============================== Data ==============================
                    $a_date = $_GET["yr"] . "-" . ($_GET["mon"] < 10 ? "0" . $_GET["mon"] : $_GET["mon"]);
                    
                    $f = DB::run("SELECT * FROM fund_type WHERE fid = ?", [$_GET["fund_type"]]);
                    $frow = $f->fetch();
                    // ============================== Data ==============================

                    // Title
                    $this->writeHTMLCell( 100, 5, 32, 5, 'Republic of the Philippines', 0, 0, false, true, '', true);
                    $this->writeHTMLCell( 100, 5, 32, 11, "Province of Masbate" , 0, 0, false, true, '', true);
                    $this->writeHTMLCell( 100, 5, 32, 17, "Municipality of Aroroy", 0, 0, false, true, '', true);
                    $this->writeHTMLCell( 70, 5, 135, 17, $frow["fund_name"], 0, 0, false, true, 'R', true);
                    $this->writeHTMLCell( 0, 5, 32, 29, "Statement of Remittances to " . $bank, 0, 0, false, true, '', true);
                    $this->writeHTMLCell( 0, 5, 32, 35, "Period Cover: " . date("01-M-Y", strtotime($a_date)) . " to " . date("t-M-Y", strtotime($a_date)) , 0, 0, false, true, '', true);
                }
            }

            // create new PDF document
            $pdf = new MYPDF("P", PDF_UNIT, 'LEGAL', true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('LGU Aroroy');
            $pdf->SetTitle('Generated Reports');
            $pdf->SetSubject('Generated Reports');

            // set default header data
            $pdf->setFooterData(array(0,64,0), array(0,64,128));

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, "45", PDF_MARGIN_RIGHT);
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

            // set default font subsetting mode
            $pdf->setFontSubsetting(true);

            // ---------------------------------------------------------

            // Add a page
            $pdf->AddPage();

            $pdf->MultiCell( 150, 0, "Name of Employee / Borrower", $border = 1, $align = 'C', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
            $pdf->MultiCell( 0, 0, "Amount Paid", $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);

            // retrieve all the employee
            $e = DB::run("SELECT * FROM employee ORDER BY lname ASC");
            while($erow = $e->fetch()){
                $date = $_GET["yr"] . "-" . ($_GET["mon"] < 10 ? "0" . $_GET["mon"] : $_GET["mon"]);
                $total_amount = 0;

                // check if the employee's current office is under the requested fund type
                $r = DB::run("SELECT * FROM appointment a JOIN department d ON a.did = d.did WHERE a.employeeid = ? ORDER BY a.apptdate_start DESC", [$erow["employeeid"]]);
                if($rrow = $r->fetch()){
                    $fid = $rrow["fid"];

                    if($fid == $_GET["fund_type"]){
                        // retrieve the payroll entry
                        $pe = DB::run("SELECT * FROM payroll_entry WHERE employeeid = ? AND coverage_start LIKE ?",[$erow["employeeid"], "%$date%"]);
                        while($perow = $pe->fetch()){
                            $pei = DB::run("SELECT * FROM payroll_entry_items WHERE p_id = ? AND de_id = ?", [$perow["p_id"], $_GET["bank"]]);
                            while ($peirow = $pei->fetch()) {
                                $total_amount += doubleval($peirow["amount"]);
                            }
                        }

                        if($total_amount != 0){
                            $name = $erow["lname"] . ", " . $erow["fname"] . " " . $erow["midinit"]; 
                            $pdf->MultiCell( 150, 0, $name , $border = 1, $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                            $pdf->MultiCell( 0, 0, "Php. " . $total_amount, $border = 1, $align = 'C', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 6, $valign = 'M', $fitcell = true);
                        }
                    }
                }
            }

            // Close and output PDF document
            $pdf->Output('generated_report.pdf', 'I');
        }else{
            header("Location: ../index.php");    
        }
    }else{
        header("Location: ../index.php");
    }
?>