<?php
	// include 'Classes/PHPExcel.php';
	// $fileType = 'Excel2007';
	// $fileName = 'example.xlsx';

	// // Read the file
	// $objPHPExcel = PHPExcel_IOFactory::load("csc.xlsx");

	// // Change the file
	// $objPHPExcel->setActiveSheetIndex(0)
	//             ->setCellValue('D10', 'New')
	//             ->setCellValue('D11', 'New')
	//             ->setCellValue('D12', 'New');

	// // // redirect output to client browser
	// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// header('Content-Disposition: attachment;filename="myfile.xlsx"');
	// header('Cache-Control: max-age=0');

	// // Write the file
	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
	// $objWriter->save('php://output');

	header("Location: main/index.php");
?>