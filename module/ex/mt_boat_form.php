<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//
include "./initial.php";
$temp = new ReplaceHtml("../template/mt_boat_form.html");
// $listPost = "" ;
// foreach($_POST as $key => $val ){
// $listPost =$listPost."<br> $key => $val ";
// }
// echo $listPost ;
$SetIdRun = "" ;
$SetMV ="";
$SetLN="";


if($_POST["form"] =="create"){
  $SetIdRun = "" ;
  $SetMV ="";
  $SetLN="";
}else if($_POST["form"] =="edit"){
     $temp = new ReplaceHtml("../template/mt_boat_form.html");
     $Boat = new Boat();
     $Boat->StrConn = $conn ;
     $Boat->_IdRun = $_POST["IdRun"]  ;
     $Boat->GetProperty();
     $SetIdRun = $Boat->IdRun ;
     $SetMV =$Boat->Boat_MV;
     $SetLN=$Boat->Boat_LighterNo;

// }
}
$temp->setReplace("{IdRun}",$SetIdRun);
$temp->setReplace("{MV}",$SetMV);
$temp->setReplace("{LN}",$SetLN);
echo $temp->getReplace();
sqlsrv_close($conn);

 ?>
