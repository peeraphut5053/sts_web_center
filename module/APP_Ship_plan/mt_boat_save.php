<?php
include "./initial.php";
//$temp = new ReplaceHtml("../template/_mt_boat_form.html");
// $listPost = "" ;
// foreach($_POST as $key => $val ){
// $listPost =$listPost."<br> $key => $val ";
// }
// echo $listPost ;
// echo $_GET["actionsave"] ;



if(isset($_POST["action"])){
  if($_POST["action"] =="delete"){
  $Boat = new Boat();
  $Boat->StrConn = $conn ;
  $Boat->_IdRun = $_POST["IdRun"] ;
  echo $Boat->Delete();

}else{
  echo "Error For unlnoen process ";
}

}else{

  $IdRun = $_POST['IdRun'] ;
  $MV =  $_POST["txtMV"] ;
  $LN = $_POST["txtLighterNo"] ;
  $Old = "" ;
  if($IdRun==""){
      $txtMV = $MV;
      $txtLN = $LN;
      $Boat = new Boat();
      $Boat->StrConn = $conn ;
      $Boat->_Ship_MV = $txtLN ;
      $Boat->_Ship_LighterNo = $txtMV ;
      // echo $Boat->isDup();
      if($Boat->isDup()==1){
        echo "Error ! ทะเบียนเรือซ้ำ" ;
      }else{
        $Boat->Insert();
        echo 1;
      }
  }else{
      $Boat = new Boat();
      $Boat->StrConn = $conn ;
      $Boat->_IdRun = $IdRun ;
      $Boat->GetProperty();
      $Old = $Boat->Ship_LighterNo ; /// Set Old value
      $Boat->_Ship_MV = $MV ;
      $Boat->_Ship_LighterNo = $LN ;
      if($Boat->isDupWhenEdit($Old) >= 1 ){
        echo "Error ! ทะเบียนเรือซ้ำ" ;
      }else{
        $Boat->Update();
        echo 1;
      }
  }

}

// if($_POST["IdRun"]==""){

// }else{

 ?>
