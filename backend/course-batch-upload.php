<?php
    require('db_conn.php');
    if($conn){
        $fileName = $_FILES["file"]["tmp_name"];
        $type = $_FILES["file"]["type"];
        require 'vendor/autoload.php';
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $obj = $reader->load($fileName);
        $errorFlag;
        $worksheet=$obj->getActiveSheet();
        $highestRow=$worksheet->getHighestRow();
        $algorithm = "sha512";
        $querySessions = mysqli_query($conn, "SELECT year FROM academic_session ORDER BY year DESC LIMIT 1") or die(mysqli_error($conn));
        if(mysqli_num_rows($querySessions)>0){
            $currentSession = mysqli_fetch_assoc($querySessions);
            for($row=5;$row<=$highestRow; $row++){
                if($worksheet->getCellByColumnAndRow(1,$row)){
                    $regNewCourseQuery = "INSERT INTO course(
                        code,
                        session_taken, 
                        title, 
                        units, 
                        level_taken, 
                        semester, 
                        taken_by, 
                        status, 
                        min_pass_score
                        ) VALUES(
                    '".mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(2,$row))."',
                    ".$currentSession['year'].",
                    '".mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(3,$row))."',
                    ".$worksheet->getCellByColumnAndRow(4,$row).",
                    ".$worksheet->getCellByColumnAndRow(5,$row).",
                    '".mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(6,$row))."',
                    '".mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(7,$row))."',
                    '".mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(8,$row))."',
                    ".$worksheet->getCellByColumnAndRow(9,$row).")";
                    if(mysqli_query($conn, $regNewCourseQuery)){
                    $errorFlag =true;
                    }
                    else{
                        $errorFlag=false;
                    }
                }
            }
        }
        if($errorFlag == true){
            $uploadedRows = $highestRow-4;
            echo $uploadedRows." row(s) uploaded successfully";
        }
        else{
            echo mysqli_error($conn);
        }
    }
?>