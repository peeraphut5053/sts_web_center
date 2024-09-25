<?php
header("Access-Control-Allow-Origin: *");
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
require_once "../initial.php";

$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$OrderProcessing = new CustomerOrder();
$OrderProcessing->setConn($ConnSL);

if ($load == "ajax") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/CER_DO/index.html"));
} else if ($load == "itemList") {
    $Item->_ItemSearch = $ItemSearch;
    $Item = $Item->GetDoHdr();
    echo json_encode($Item);
} else if ($load == "SP_WebApp_OrderProcessing_Overall") {
    $OrderProcessing_Overall = $OrderProcessing->SP_WebApp_OrderProcessing_Overall($StartDate,$EndDate,$StartOrdNum,$EndOrdNum);
    echo json_encode($OrderProcessing_Overall);
} else if ($load == "OrderProcessing_Line") {
     $OrderProcessing_Line = $OrderProcessing->OrderProcessing_Line($ord_num);
    echo json_encode($OrderProcessing_Line);
} else if ($load == "OrderProcessing_Detail") {
     $OrderProcessing_Line = $OrderProcessing->OrderProcessing_Detail($ord_num,$ord_line);
    echo json_encode($OrderProcessing_Line);
} else if ($load == "V_WebApp_OrderProcessing") {
     $V_WebApp_OrderProcessing = $OrderProcessing->V_WebApp_OrderProcessing($cust_po,$name,$Port);
    echo json_encode($V_WebApp_OrderProcessing);
}



