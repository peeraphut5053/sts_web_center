<?php

foreach ($_GET as $key => $value) {
  $$key = trim($value);
}

foreach ($_POST as $key => $value) {
  if (is_array($value)) {
      // ถ้าเป็น array ให้วนลูปอีกครั้งเพื่อ trim แต่ละ element
      foreach ($value as $subKey => $subValue) {
          $$key[$subKey] = trim($subValue);
      }
  } else {
      $$key = trim($value);
  }
}
include "../initial.php";
$arrMapElement = $_POST["arrMapElement"] ;
$arrMapColumn = $_POST["arrMapColumn"] ;
//$eleItemDesc = $_POST["eleItemDesc"] ;
$temp = new ReplaceHtml("../../template/_GLOBAL_SEARCH/item.html");
//print_r($arrMapElement);
$strMapElement ="" ;
$strMapColumn ="" ;
$loop = count($arrMapElement) -1;
 
 for($i=0;$i<=$loop;$i++ ){
    
      $strMapElement.=$arrMapElement[$i].",";
      $strMapColumn.=$arrMapColumn[$i].",";
 }

 $strMapElement=substr($strMapElement, 0, -1);
  $strMapColumn=substr($strMapColumn, 0, -1);
 
$temp->setReplace("{arrMapElement}", $strMapElement);
$temp->setReplace("{arrMapColumn}", $strMapColumn);
 
 
echo $temp->getReplace();
sqlsrv_close($ConnSL);


 