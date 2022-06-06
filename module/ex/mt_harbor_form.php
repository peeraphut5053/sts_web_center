<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//
include "./initial.php";
$temp = new ReplaceHtml("../template/mt_harbor_form.html");
// $listPost = "" ;
// foreach($_POST as $key => $val ){
// $listPost =$listPost."<br> $key => $val ";
// }
// echo $listPost ;
$SetIdRun = "" ;
$SetHN ="";
$cBoth= "";
$cBerth = "" ;
$cTo ="" ;


if($_POST["form"] =="create"){
  $SetIdRun = "" ;
  $SetHN ="";
  $cBoth= "checked";
}else if($_POST["form"] =="edit"){
     $temp = new ReplaceHtml("../template/mt_harbor_form.html");
     $Harbor = new Harbor();
     $Harbor->StrConn = $conn ;
     $Harbor->_IdRun = $_POST["IdRun"]  ;
     $Harbor->GetProperty();
     $SetIdRun = $Harbor->IdRun ;
     $SetHN =$Harbor->HB_Name;
     $cBoth= "";
     $cBerth = "" ;
     $cTo ="" ;
     if($Harbor->HB_Status == 2 ){
       $cBoth= "checked";
     }else if ($Harbor->HB_Status == 1 ) {
       $cTo= "checked" ;
     }else if ($Harbor->HB_Status == 0 ) {
       $cBerth = "checked" ;
     }
}
$temp->setReplace("{IdRun}",$SetIdRun);
$temp->setReplace("{HN}",$SetHN);
$temp->setReplace("{cBoth}",$cBoth);
$temp->setReplace("{cBerth}",$cBerth);
$temp->setReplace("{cTo}",$cTo);
echo $temp->getReplace();
sqlsrv_close($conn);

 ?>
