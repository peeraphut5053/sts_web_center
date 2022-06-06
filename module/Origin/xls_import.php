<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");
$temp->setReplace("{content}", $temp->getTemplate("./template/xls_import.html"));
$target_file = "Not Select";
$file_tmp = "";
$errCount = 0;
$CurrTemp = "";
$errText = "";
$FileName = "";
$FileNameShow = "";
if (!empty($_FILES["fileToUpload"]["name"])) {
    $CurrTemp = sys_get_temp_dir();
//    echo $_FILES["fileToUpload"]["name"];
//if (isset($_POST["submit"])) {

    if (isset($_POST["submit"])) {

        $target_dir = '/uploads/';
        $target_file = dirname(__FILE__) . $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if file already exists
//    if (file_exists($target_file)) {
//        $errCount++;
//        $errText = $errText . "<br>-Sorry, file already exists.";
//    }
// Check file size
        $maxFileSize = 5 * 1024 * 1024;  // 5mb
        if ($_FILES["fileToUpload"]["size"] > $maxFileSize) {
            $errCount++;
            $errText = $errText . "<br>-ขนาดไฟล์ใหญ่เกินที่กำหนดไว้ (5 mb)";
        }
// Allow certain file formats
        if ($imageFileType != "xls" && $imageFileType != "xlsx") {
            $errCount++;
            $errText = $errText . "<br>-กรุณาเลือกไฟล์ xls , xlsx เท่านั้น";
        }
        if ($errCount == 0) {
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            $FileNameShow = $_FILES["fileToUpload"]["name"];
        }
    }
}
//    if (move_uploaded_file($_FILES["filepath"]["tmp_name"], $target_file);) {
//        
//    } else {
//        $errCount++;
//        $errText = $errText . "Sorry, there was an error uploading your file.";
//    }
//} else {
//
//}
//}
$fileExists = "";
$Reader = "";
if (isset($_POST["FileNameShow"])) {
    $target_file = $_POST["FilePath"];
    $FileNameShow = $_POST["FileNameShow"];
}
if (file_exists($target_file) == 1) {
    $fileExists = "Found";
    /** PHPExcel */
    require_once './Classes/PHPExcel.php';

    /** PHPExcel_IOFactory - Reader */
    include './Classes/PHPExcel/IOFactory.php';


//    $target_file = "myData.xls";
    $inputFileType = PHPExcel_IOFactory::identify($target_file);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($target_file);

    /*
      // for No header
      $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
      $highestRow = $objWorksheet->getHighestRow();
      $highestColumn = $objWorksheet->getHighestColumn();

      $r = -1;
      $namedDataArray = array();
      for ($row = 1; $row <= $highestRow; ++$row) {
      $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
      if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
      ++$r;
      $namedDataArray[$r] = $dataRow[$row];
      }
      }
     */

    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();

    $headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
    $headingsArray = $headingsArray[1];

    $r = -1;
    $namedDataArray = array();
    for ($row = 2; $row <= $highestRow; ++$row) {
        $dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
        if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
            ++$r;
            foreach ($headingsArray as $columnKey => $columnHeading) {
                $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
            }
        }
    }
    $x = 0;
    foreach ($namedDataArray as $index => $result) {
        $x = $index + 1;
        $Reader = $Reader . "<tr>"
                . "<td>$x</td>"
                . "<td>" . $result["col1"] . "</td>"
                . "<td>" . $result["col2"] . "</td>"
                . "<td>" . $result["col3"] . "</td>"
                . "</tr>";
        if (isset($_POST["FileNameShow"])) { /// IF PROCESS
            $Reader = $Reader . "<tr>"
                    . "<td></td>"
                    . "<td> Upload </td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "</tr>";
        }
    }
//echo '<pre>';
//var_dump($namedDataArray);
//echo '</pre><hr />';
}

$Msg = "";
if ($errCount > 0) {
    $Msg = "<div class='row'><div class='col-12'><div class='alert alert-danger'><strong>ผิดพลาด ! </strong>$errText</div></div></div>";
}
if (isset($_POST["FileNameShow"])) { /// IF PROCESS
    if (file_exists($_POST["FilePath"])) {
        unlink($_POST["FilePath"]);
    } else {
        $FileNameShow = "";
    }
}

$temp->setReplace("{Msg}", $Msg);
$temp->setReplace("{Reader}", $Reader);
$temp->setReplace("{FilePath}", $target_file);
$temp->setReplace("{FileNameShow}", $FileNameShow);
//$temp->setReplace("{FileExists}", $fileExists);

