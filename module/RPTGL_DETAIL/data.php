<?php

header("Access-Control-Allow-Origin: *");

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";

if ($load == "ajax") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GL = new GeneralLedger();
    $GL->setConn($ConnSL);
    $GLS = $GL->GetRowsGLDetail($selYear, $selMonth, $selMonth2, $Acct, $Unit1);
    echo json_encode($GLS);
    }
    


// } elseif ($load == "diary") {
//     $CM = new CallModel();
//     $CM->SyteLine_Models();
//     $GL = new GeneralLedger();
//     $GL->setConn($ConnSL);
//     $GL->_StartDate = $txtStartDate;
//     $GL->_EndDate = $txtEndDate;
//     $GL->_AcctDiary = $AcctDiary;

//     $GLS = $GL->GetRowsGLDairy();
//     $CM = null;
//     $GL = null;

//     echo json_encode($GLS);
// } elseif ($load == "ajax2") {
//     $CM = new CallModel();
//     $CM->SyteLine_Models();
//     $GL = new GeneralLedger();
//     $GL->setConn($ConnSL);

//     $GLS = $GL->GetRows_SP($selYear, $selMonth, $selMonth2, $Acct);

//     $ArrGLS = [];
//     $distant = $selMonth2 - $selMonth + 1;
//     for ($i = 0; $i < $distant; $i++) {
//         //echo "i".$i.",selYear".$selYear.",selMonth".($selMonth + $i).",selMonth2".$selMonth2."Acct".$Acct."<br>";
//         $GLS = $GL->GetRows_SP($selYear, $selMonth + $i, $selMonth2, $Acct);
//         array_push($ArrGLS, $GLS);
//     }
//     echo json_encode($ArrGLS);
//     $CM = null;
//     $GL = null;
// }
