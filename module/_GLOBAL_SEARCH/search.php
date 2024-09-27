<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
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


 