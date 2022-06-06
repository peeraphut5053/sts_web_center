<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";
if ($load == "form") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/RPTDO_PENDING/index.html"));
} else if ($load == "ajax") {

    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DOSEQ = new DeliveryOrder();
    $DOSEQ->setConn($ConnSL);
    $DOSEQ->_txtDoDateFrom = $txtFromDate;
    $DOSEQ->_txtDoDateTo = $txtToDate;
    $DOSEQ->_FromDate = $selFromDate;
    $DOPENDING = $DOSEQ->GetDoPending();
    $lines = "";
    $tStat = "";
    foreach ($DOPENDING as $idp => $rdp) {
        $rdp["stat"] == "A" ? $tStat = "Approved" : $tStat = "In Process";
        $lines .= "<tr>"
                . "<td align='center'>" . $rdp["do_num"] . "</td>"
                . "<td align='center'>" . $rdp["cust_num"] . "</td>"
                 . "<td align='center'>" . $rdp["cust_name"] . "</td>"
                . "<td align='right'>" . number_format($rdp["total_price"], 2) . "</td>"
                . "<td align='center'>$tStat</td>"
                . "<td align='center'>" . $rdp["do_hdr_date"]->format("Y-m-d") . "</td>"
                . "<td align='center'>" . $rdp["ref_num"] . "</td>"
                . "<td align='center'>" . $rdp["ref_line"] . "</td>"
                . "<td align='center'>" . $rdp["ship_date"]->format("Y-m-d G:i:s") . "</td>"               
                . "<td align='center'>" . $rdp["uf_um2"] . "</td>"
                . "<td align='center'>" . $rdp["cust_po"] . "</td>"
                . "<td align='right'>" . number_format($rdp["price"], 2) . "</td>"
                 . "<td align='right'>" . number_format($rdp["qty_shipped"], 2) . "</td>"
                . "<td align='right'>" . number_format($rdp["qty_invoiced"], 2) . "</td>"
                . "<td align='left'>" . $rdp["lot"] . "</td>"
                . "<td align='left'>" . $rdp["loc_desc"] . "</td>"
                . "</tr>";
    }



    echo $lines;
} 
