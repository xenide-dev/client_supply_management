<?php
    if(isset($_FILES["file"])){
        $output["offices"] = [];
        $fileName = $_FILES["file"]["tmp_name"];
    
        if ($_FILES["file"]["size"] > 0) {
            
            $file = fopen($fileName, "r");
            
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                array_push($output["offices"], implode(",", $column));
            }
        }
        
        echo json_encode($output);
    }
?>