<?php
include "./initial.php";
//$temp = new ReplaceHtml("../template/SHP/_mt_boat_form.html");
// $listPost = "" ;
// foreach($_POST as $key => $val ){
// $listPost =$listPost."<br> $key => $val ";
// }
// echo $listPost ;
// echo $_GET["actionsave"] ;



if(isset($_POST["action"])){
  if($_POST["action"] =="delete"){
  $Harbor = new Harbor();
  $Harbor->StrConn = $ConnWebApp ;
  $Harbor->_IdRun = $_POST["IdRun"] ;
  echo $Harbor->Delete();

}else{
  echo "Error For unlnoen process ";
}

}else{

  $IdRun = $_POST['IdRun'] ;
  $HN =  $_POST["txtHN"] ;
  $HS = $_POST["rdHS"] ;
  $Old = "" ;
  if($IdRun==""){

      $Harbor = new Harbor();
      $Harbor->StrConn = $ConnWebApp ;
      $Harbor->_HB_Name = $HN ;
      $Harbor->_HB_Status = $HS ;
      // echo $Harbor->isDup();
      if($Harbor->isDup()==1){
        echo "Error ! ชื่อท่าเรือซ้ำ " ;
      }else{
        $Harbor->Insert();
        echo 1;
      }
  }else{
      $Harbor = new Harbor();
      $Harbor->StrConn = $ConnWebApp ;
      $Harbor->_IdRun = $IdRun ;
      $Harbor->GetProperty();
      $Old = $Harbor->HB_Name ; /// Set Old value
      $Harbor->_HB_Name = $HN ;
      $Harbor->_HB_Status = $HS ;
      if($Harbor->isDupWhenEdit($Old) >= 1 ){
        echo "Error ! ทะเบียนเรือซ้ำ" ;
      }else{
        $Harbor->Update();
        echo 1;
      }
  }

}

// if($_POST["IdRun"]==""){

// }else{

 ?>
