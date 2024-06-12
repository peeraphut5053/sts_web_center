<?php
header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";




if ($load == "form") {
    
} else if ($load == "ajax") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $AT = new ArTran();
    $AT->setConn($ConnSL);
    $AT->_StartDate = $StartDate;
    $AT->_EndDate = $EndDate;
    $AT->_docs = ["IN", "CN", "DN"];
    $Artrans = $AT->GetRows();
    echo json_encode($Artrans);
//    $lines = "";
//    $sum_amount = 0;
//    $sum_tax = 0;
//    $i = 0;
//    $tmpAmount = 0;
//    $tmpTax = 0;
//    $tmpNet = 0;
//    $grandTotal = 0;
//    $tmpCnVat = 0;
//    $tmpCnAmt = 0;
//    $tmpCnNet = 0;
//    foreach ($Artrans as $ii => $rr) {
//        $i++;
//        if (substr($rr["inv_num"], 0, 2) == "CN") {
//            $tmpCnAmt = $rr["amount"];
//            $tmpCnVat = $tmpCnAmt * 7 / 107;
//            $tmpCnNet = $tmpCnAmt - $tmpCnVat;
//
//            $tmpAmount = $tmpCnNet * -1;
//            $tmpTax = $tmpCnVat * -1;
//            $tmpNet =$rr["amount"] * -1;
//        } else {
//
//
//            $tmpAmount = $rr["amount"];
//            $tmpTax = $rr["sales_tax"];
//            $tmpNet = $rr["amount"] + $rr["sales_tax"];
//        }
//        $sum_amount += $tmpAmount;
//        $sum_tax += $tmpTax;
//        $lines .= "<tr>"
//                . "<td width='5%'>$i</td>"
//                . "<td align='center'>" . $rr["inv_date"]->format('Y-m-d') . "</td>"
//                . "<td align='center'>" . $rr["inv_num"] . "</td>"
//                . "<td align='left'>" . $rr["cust_num"] . "</td>"
//                . "<td align='left'>" . $rr["cust_name"] . "</td>"
//                . "<td align='right'>" . number_format($tmpAmount, 2) . "</td>"
//                . "<td align='right'>" . number_format($tmpTax, 2) . "</td>"
//                . "<td align='right'>" . number_format($tmpNet, 2) . "</td>"
//                . "<td align='left'>" . $rr["description"] . "</td>"
//                . "<td align='left'>" . $rr["apply_to_inv_num"] . "</td>"
//                . "<td align='center'>" . $rr["post_from_co"] . "</td>"
////                . "<td align='center'>" . $rr["due_date"]->format('Y-m-d') . "</td>"
//                . "</tr>";
//    }
//    $AT = null;
//    $grandTotal = $sum_amount + $sum_tax;
//    $lines .= "<tr><td ></td><td ></td><td ></td><td ></td><td ></td><td ><b>TOTAL</b></td><td ><b>" . number_format($sum_amount, 2) . "</b></td><td><b>" . number_format($sum_tax, 2) . "</b></td><td ><b>" . number_format($grandTotal, 2) . "</b></td><td ></td><td ></td></tr>";
//    echo $lines;
} else {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $AT = new ArTran();
    $AT->setConn($ConnSL);
    $AT->_StartDate = $StartDate;
    $AT->_EndDate = $EndDate;
    $AT->_docs = [];

    if ($IN == "true") {
        $AT->_docs = array_merge($AT->_docs, ["IN"]);
    } 
    if ($CN == "true") {
        $AT->_docs = array_merge($AT->_docs, ["CN"]);
    }
    if ($DN == "true") {
        $AT->_docs = array_merge($AT->_docs, ["DN"]);
    }

    $Artrans = $AT->GetRows();
    echo json_encode($Artrans);
}

