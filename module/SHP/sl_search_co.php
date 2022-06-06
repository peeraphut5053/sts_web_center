<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
//$temp->setReplace("{content}", $temp->getTemplate("./template/SHP/SHP/sl_search_co.html"));
//
//echo "1234";
if ($load == "form") {
    $temp = new ReplaceHtml("../../template/SHP/SHP/sl_search_co.html");
    echo $temp->getReplace();
} else {
    $CallModel = new CallModel();
    $CallModel->SHP_Models();
    $CO = new CustomerOrders();
    $CO->setConn($ConnSL);
    $CO->_CoNum = $txtCO;
    $CO->_CustName = $txtCust;
    $CO->_CustPO = $txtCustPO;
    $GetCO = $CO->GetRowsWithCond2();
    $lines = "";
    foreach ($GetCO as $ii => $rr) {
        $lines = $lines . "<tr>"
                . "<td class='text-center'><a class='btn btn-success'  OnClick='SelectCO_FromDialog(this.id);' id='sel_" . $rr["co_num"] . "'><i class='fa fa-check-circle'></i></a></td>"
                . "<td>" . $rr["co_num"] . "</td>"
                . "<td>" . $rr["cust_num"] . "</td>"
                . "<td>" . $rr["cust_po"] . "</td>"
                . "<td>" . $rr["addr1"] . "</td>"
                . "<td>" . $rr["cust_name"] . "</td>"
                . "<td>" . $rr["stat"] . "</td>"
                . "<td>" . $rr["order_date"]->format('d/m/Y G:i:s') . "</td>"
                . "</tr>";
    }
    echo $lines;
}
