<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

header('Content-Type: text/html; charset=utf-8');
if ($load == "ajax_savedata") {
    $FieldList = $_POST["FieldList"];
    $ValList = $_POST["ValList"];
  //  $GetTableName = $_POST["tableName"] ;
//    echo print_r($ValList);
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $ItemModel = new ItemSyteLine();
    $ItemModel->setConn($ConnSL);
    $ItemModel->_FieldList = $FieldList;
    $ItemModel->_ValList = $ValList;
   // $ItemModel->_tableName = $tableName;
    $Result = $ItemModel->BatchUpdate();
    $CM = null;
echo $Result;
   // echo $GetTableName;
} else if ($load == "read_excel") {



    require_once '../../PHPExcel-1.8/Classes/PHPExcel.php';
    include '../../PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';

    $inputFileName = '../../uploads/' . $file_name;
    if (file_exists($inputFileName)) {

        $objPHPExcel = new PHPExcel();
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($inputFileName);

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

        echo json_encode($namedDataArray);
    } else {
        echo "File Not Found";
    }
}
   

