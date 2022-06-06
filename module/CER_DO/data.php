<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";
if ($load == "form") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/CER_DO/index.html"));
} else if ($load == "ajax") {
//    $CallModel = new CallModel();
//    $CallModel->SyteLine_Models();
//    $DOSEQ = new DeliveryOrder();
//    $DOSEQ->setConn($ConnSL);
//    $DOSEQ->_CerDate = $txtCerDate;
//    $DOPENDING = $DOSEQ->GetCER_DO();
//    $lines = "";
//    $tStat = "";
//    $tLoc = "";
//    $tItem = "";
//    foreach ($DOPENDING as $idp => $rdp) {
//        $rdp["item_desc"] == "" ? $tItem = $rdp["item"] : $tItem = $rdp["item_desc"];
//        $rdp["loc_desc"] == "" ? $tLoc = $rdp["loc"] : $tLoc = $rdp["loc_desc"];
//
//        $lines .= "<tr>"
//                . "<td align='center'>" . $rdp["ship_date"]->format('Y-m-d G:i:s') . "</td>"
//                . "<td align='center'>" . $rdp["ref_num"] . "</td>"
//                . "<td align='left'>" . $rdp["cust_name"] . "</td>"
//                . "<td align='left'>$tItem</td>"
//                . "<td align='right'>" . number_format($rdp["qty_shipped"], 2) . "</td>"
//                . "<td align='left'>$tLoc</td>"
//                . "<td align='left'>" . $rdp["lot"] . "</td>"
//                . "</tr>";
//    }
//    echo $lines;
} else if ($load == "ajax2") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DOSEQ = new DeliveryOrder();
    $DOSEQ->setConn($ConnSL);
    $DOSEQ->_CerDate = $txtCerDate;
    $DOPENDING = $DOSEQ->GetCER_DO();
    echo json_encode($DOPENDING);
    
} else if ($load == "MultiLot") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DOSEQ = new DeliveryOrder();
    $DOSEQ->setConn($ConnSL);
    $DOSEQ->_CoNum = $CoNum;
    $DOSEQ->_CoLine = $CoLine;
    $DOSEQ->_CerDate = $txtCerDate;
    $MultiLot= $DOSEQ->GetMultipleLotDoSeq();
//    echo $MultiLot;
    echo json_encode($MultiLot);
} 


