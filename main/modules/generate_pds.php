<?php
	include "../connection/connection.php";
	include '../../Classes/PHPExcel.php';

	if(!isset($_GET["employeeid"]) || $_GET["employeeid"] == ""){
	    header("Location: index.php");
	}

	$fileType = 'Excel2007';
	$fileName = 'generated_file_excel.xlsx';

	// Read the file
	$objPHPExcel = PHPExcel_IOFactory::load("../template/csc.xlsx");

	// ------------------------ Retrieved the Data ------------------------
	// Main Information
	$m = DB::run("SELECT * FROM employee WHERE employeeid = ?", [$_GET["employeeid"]]);
	$mr = $m->fetch();
	// Educational Background
	$e = DB::run("SELECT * FROM educbackground WHERE employeeid = ? ORDER BY itemno ASC", [$_GET["employeeid"]]);

	// ------------------------ Retrieved the Data ------------------------


	// Change the file
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('D10', $mr["lname"])
	            ->setCellValue('D11', $mr["fname"])
	            ->setCellValue('D12', $mr["midname"])
	            ->setCellValue('D13', date('m/d/Y',strtotime($mr["birthdate"])))
	            ->setCellValue('D15', $mr["birthplace"])
	            ->setCellValue('D16', ($mr["gender"] == 'M') ? "MALE" : "FEMALE")
	            ->setCellValue('D17', $mr["civilstatus"])
	            ->setCellValue('D22', $mr["height"])
	            ->setCellValue('D24', $mr["weight"])
	            ->setCellValue('D25', $mr["bloodtype"])
	            ->setCellValue('D27', $mr["gsisno"])
	            ->setCellValue('D29', $mr["pagibigno"])
	            ->setCellValue('D31', $mr["philhealthno"])
	            ->setCellValue('D32', $mr["sssno"])
	            ->setCellValue('D33', $mr["tinno"])
	            ->setCellValue('D34', $mr["agencyemployeeno"])
	            ->setCellValue('I24', $mr["reszipcode"])
	            ->setCellValue('I31', $mr["permzipcode"])
	            ->setCellValue('I32', $mr["telno"])
	            ->setCellValue('I33', $mr["mobileno"])
	            ->setCellValue('I34', $mr["emailaddr"])
	            ->setCellValue('D36', $mr["spouselname"])
	            ->setCellValue('D37', $mr["spousefname"])
	            ->setCellValue('D38', $mr["spousemname"])
	            ->setCellValue('D39', $mr["sp_occupation"])
	            ->setCellValue('D40', $mr["sp_employer"])
	            ->setCellValue('D41', $mr["sp_empraddr"])
	            ->setCellValue('D42', $mr["sp_emprtelno"])
	            ->setCellValue('D43', $mr["fatherlname"])
	            ->setCellValue('D44', $mr["fatherfname"])
	            ->setCellValue('D45', $mr["fathermname"])
	            ->setCellValue('D47', $mr["motherlname"])
	            ->setCellValue('D48', $mr["motherfname"])
	            ->setCellValue('D49', $mr["mothermname"]);
	while($er = $e->fetch()){
		if($er["educlevel"] == "elementary"){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D54', $er["schoolname"])
				->setCellValue('G54', $er["degree"])
				->setCellValue('J54', $er["periodfrom"])
				->setCellValue('K54', $er["periodto"])
				->setCellValue('L54', $er["unitsearned"])
				->setCellValue('M54', $er["yrgraduate"])
				->setCellValue('N54', $er["honors"]);
		}else if($er["educlevel"] == "secondary"){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D55', $er["schoolname"])
				->setCellValue('G55', $er["degree"])
				->setCellValue('J55', $er["periodfrom"])
				->setCellValue('K55', $er["periodto"])
				->setCellValue('L55', $er["unitsearned"])
				->setCellValue('M55', $er["yrgraduate"])
				->setCellValue('N55', $er["honors"]);
		}else if($er["educlevel"] == "vocational"){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D56', $er["schoolname"])
				->setCellValue('G56', $er["degree"])
				->setCellValue('J56', $er["periodfrom"])
				->setCellValue('K56', $er["periodto"])
				->setCellValue('L56', $er["unitsearned"])
				->setCellValue('M56', $er["yrgraduate"])
				->setCellValue('N56', $er["honors"]);
		}else if($er["educlevel"] == "college"){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D57', $er["schoolname"])
				->setCellValue('G57', $er["degree"])
				->setCellValue('J57', $er["periodfrom"])
				->setCellValue('K57', $er["periodto"])
				->setCellValue('L57', $er["unitsearned"])
				->setCellValue('M57', $er["yrgraduate"])
				->setCellValue('N57', $er["honors"]);
		}else if($er["educlevel"] == "graduate"){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D58', $er["schoolname"])
				->setCellValue('G58', $er["degree"])
				->setCellValue('J58', $er["periodfrom"])
				->setCellValue('K58', $er["periodto"])
				->setCellValue('L58', $er["unitsearned"])
				->setCellValue('M58', $er["yrgraduate"])
				->setCellValue('N58', $er["honors"]);
		}
	}


	// // redirect output to client browser
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $fileName . '"');
	header('Cache-Control: max-age=0');

	// Write the file
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
	$objWriter->save('php://output');
?>
