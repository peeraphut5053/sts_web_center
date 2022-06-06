<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
$CallModel = new CallModel();
$CallModel->MGT_Models();
$CallModel->SyteLine_Models();
$CallModel->MvBcTag_Models();

if ($action == "NewLot") {
    $Lot = new Lot();
//    $Lot->setConn($ConnSL);
    $ret = "";
    $NewLot = $Lot->GenNewLotNum();
    echo $NewLot;
}
if ($action == "FindBlankActWeight") {
    $Lot = new Lot();
    $ret = "";
    $result = "";
    $po_QC = new PO_QC();
    $po_QC->setConn($var);
//    $start_date = "" ;
//    $end_date = "" ;
    $ArrActWeight = $Lot->FindBlankActWeight($start_date, $end_date);

//    $WL = 
    $x=0;
    foreach ($ArrActWeight as $ii => $rr) {
        $realweight = $po_QC->SearchRealWeight($rr["sts_no"]);
        $row_template = "<tr>"
                . "<td align='center' id='Lot_$x'>" . $rr["lot"] . "</td>"
                . "<td align='right'>" . $rr["rcvd_qty"] . "</td>"
                . "<td align='center' >" . $rr["sts_no"] . "</td>"
                . "<td align='center'>" . $rr["Uf_act_weight"] . "</td>"
                . "<td align='center' id='ActWeight_$x'>" . $realweight . "</td>"
                . "</tr>";
        $x++;
        $result = $result . $row_template;
    }
    $po_QC = null;
    echo $result;
}

if ($action == "PullActWeight") {
    $Lots=$_POST["Lots"] ;
    $ActWeights=$_POST["ActWeights"] ;
    $x = count($Lots);
    $Lot = new Lot();    
    
    for ($i = 0; $i < $x; $i++) {
        $Lot->UpdateActWeight($Lots[$i],$ActWeights[$i]);
    }
    $Lot = null;
    echo $x ." pull rows complete ";
}
sqlsrv_close($ConnSL);


