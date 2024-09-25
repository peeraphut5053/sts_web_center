<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
//
//$CM = new CallModel();
//$CM->SyteLine_Models();
//$ARtran = new ArTran();
//$ARtran->setConn($ConnSL);
//$DocTypes = $ARtran->GetDocType();
//$docs = "" ;
//foreach($DocTypes as $ii=>$rr){
//    $doc = $rr["doc"] ; 
//    $docs.="  <label class='checkbox-inline'>
//      <input type='checkbox' checked name='cb_doc' value='$doc'>$doc
//    </label>" ;
//    
//}
$temp->setReplace("{content}", $temp->getTemplate("./template/RP_InvItem_EX_test/index.html"));
//$temp->setReplace("{docs}", $docs);


//if (strpos("ALLP1", 'ALL') !== false) {
//    $RR="ALL_A1";
//    $ep = explode("_",$RR);
//   echo $ep[1] ;
//} else {
//     echo "2" ;
//}

//$temp->setReplace("{options_thickness}", $result2);
// $temp->setReplace("{ConSlVal}", $ConnWebAppSL);
