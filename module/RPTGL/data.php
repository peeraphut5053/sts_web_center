<?php

header("Access-Control-Allow-Origin: *");

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
include "../../initial.php";

if ($load == "ajax") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GL = new GeneralLedger();
    $GL->setConn($ConnSL);
    $AcctSplit = explode(",", $Acct);
    $NewArrGLS2 = [];
    for ($h = 0; $h < count($AcctSplit) - 1; $h++) {
        $NewAcct = $AcctSplit[$h] . ",";

        $GLS = $GL->GetRows_SP($selYear, $selMonth, $selMonth2, $NewAcct);
        $ArrGLS = [];
        $distant = $selMonth2 - $selMonth + 1;
        for ($i = 0; $i < $distant; $i++) {
            $GLS = $GL->GetRows_SP($selYear, $selMonth + $i, $selMonth2, $NewAcct);
            array_push($ArrGLS, $GLS);
        }
        $NewArrGLS = [];
        for ($j = 0; $j < $distant; $j++) {
            for ($i = 0; $i < count($ArrGLS[$j]); $i++) {
                array_push($NewArrGLS, $ArrGLS[$j][$i]);
            }
        }
        array_push($NewArrGLS2, $NewArrGLS);
    }
    
    
    $FinalArrGLS = [];
    for ($a = 0; $a < count($NewArrGLS2); $a++) {
        for ($b = 0; $b < count($NewArrGLS2[$a]); $b++) {
            array_push($FinalArrGLS, $NewArrGLS2[$a][$b]);
        }
    }
    //$NewAcct = $AcctSplit[1].",";
    echo json_encode($FinalArrGLS);
    //echo json_encode($GLS);
    $CM = null;
    $GL = null;

//    $NewArr = [];
//    array_push($NewArr, $ArrGLS[0]);
    //echo json_encode($NewArr[0]);
} elseif ($load == "diary") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GL = new GeneralLedger();
    $GL->setConn($ConnSL);
    $GL->_StartDate = $txtStartDate;
    $GL->_EndDate = $txtEndDate;
    $GL->_AcctDiary = $AcctDiary;

    $GLS = $GL->GetRowsGLDairy();
    $CM = null;
    $GL = null;

    echo json_encode($GLS);
} elseif ($load == "ajax2") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GL = new GeneralLedger();
    $GL->setConn($ConnSL);

    $GLS = $GL->GetRows_SP($selYear, $selMonth, $selMonth2, $Acct);

    $ArrGLS = [];
    $distant = $selMonth2 - $selMonth + 1;
    for ($i = 0; $i < $distant; $i++) {
        //echo "i".$i.",selYear".$selYear.",selMonth".($selMonth + $i).",selMonth2".$selMonth2."Acct".$Acct."<br>";
        $GLS = $GL->GetRows_SP($selYear, $selMonth + $i, $selMonth2, $Acct);
        array_push($ArrGLS, $GLS);
    }
    echo json_encode($ArrGLS);
    $CM = null;
    $GL = null;

} elseif ($load == 'list') {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GetModel = new Chart();
    $GetModel->setConn($ConnSL);
    $GetModelValue = $GetModel->GetRows();
    echo json_encode($GetModelValue);
}
