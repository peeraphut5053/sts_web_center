<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
session_start();
if (empty($_SESSION["login_username"])) {
    header("location: ?login");
}
//============== Render Page Normal ================//
include "./initial.php";

$u_name = $_SESSION["login_username"];
$temp = new ReplaceHtml("../template/SOD/SaleOrderPrint.html");

$temp->setReplace("{doc_no}", $doc_no);
$STS = new STS_Info();
$STS->setConn($ConnWebApp);
$STS->GetProperties();

$Head = new OrderHead();
$Head->setConn($ConnWebApp);
$Head->_doc_no = $doc_no;

$Head->GetPropertiesByDocNo();
$temp->setReplace("{cust_name}", $Head->vend_name);
$temp->setReplace("{cust_addr1}", $Head->vend_addr1 . " " . $Head->vend_addr2);
$temp->setReplace("{cust_addr2}", $Head->vend_addr3);
$temp->setReplace("{cust_email}", $Head->vend_email);
$temp->setReplace("{po_date}", $Head->po_date);
$temp->setReplace("{sts_info}", $STS->fullname);
$temp->setReplace("{ship_attn}", $Head->attn);
$temp->setReplace("{po_num}", $Head->po_num);
$temp->setReplace("{port_from}", $Head->ship_to_text);
$temp->setReplace("{shipment}", $Head->shipment);

$Detail = new OrderDetail();
$Detail->setConn($ConnWebApp);
$Detail->_doc_no = $Head->doc_no;
$Details = $Detail->GetRowsByDocNo();
$tblLines = "";
$CountGridRow = 0;
$lotOut = "";
$tLot = "";
$lotShow="";
 $s_pieces = 0;
        $s_length_total = 0;
        $s_net_tons = 0;
        $s_m_tons = 0;
        $s_cfr_lo_mt = 0;
        $s_cfr_lo_ft = 0;
        $s_ext_price = 0;
foreach ($Details as $i => $r) {
    $CountGridRow++;
     $tLot = $r["lot"];
     $lotShow="";
    if ($tLot != $lotOut) {
        $lotShow = "<tr><td class='text-left' colspan='15'><i><u><b>Lot : $tLot</i></u></b></td></tr>";
    }
    $tblLines = $tblLines . "$lotShow<tr >";
    $tblLines = $tblLines . "<td colspan='7'><u>" . $r["item"] . " / " . $r["item_desc"];
    $tblLines = $tblLines . "</u></td></tr>";
    $tblLines = $tblLines . "</tr>";
    $tblLines = $tblLines . "<tr>";

    $lotOut = $tLot;
//    $tblLines = $tblLines . "<td id='rowLine_" . $CountGridRow . "'>" . $CountGridRow . "</td>";
//        $tblLines =$tblLines. "<td width='10%' id='rowItem_" . $CountGridRow . "'>" . $r["item"] . "</td>";
//        $tblLines =$tblLines. "<td width='10%' id='rowDesc_" . $CountGridRow . "'>" . $r["item_desc"] . "</td>";
    $tblLines = $tblLines . "<td id='rowSIZE_" . $CountGridRow . "' align='right'>" . $r["size"] . "</td>";
//                                $tblLines .= "<td id='rowOD_" . $CountGridRow . "' align='right'>" . OD[t] . "</td>";
    $tblLines = $tblLines . "<td id='rowTW_" . $CountGridRow . "'align='right'>" . $r["theo_weight"] . "</td>";
    $tblLines = $tblLines . "<td id='rowUL_" . $CountGridRow . "'align='right'>" . $r["length"] . "</td>";
    $tblLines = $tblLines . "<td id='rowCATE_" . $CountGridRow . "'align='center'>" . $r["category"] . "</td>";
    $tblLines = $tblLines . "<td id='rowSCHED_" . $CountGridRow . "'align='right'>" . $r["sched"] . "</td>";
    $tblLines = $tblLines . "<td id='rowPCSPERBNDL_" . $CountGridRow . "'align='right'>" . $r["pcs_per_bundle"] . "</td>";
    $tblLines = $tblLines . "<td id='rowBNDL_" . $CountGridRow . "'align='right'>" . $r["bundles"] . "</td>";
    $tblLines = $tblLines . "<td id='rowPCS_" . $CountGridRow . "'align='right'>" . $r["pieces"] . "</td>";
    $tblLines = $tblLines . "<td id='rowTTLEN_" . $CountGridRow . "' align='right'>" . $r["length_total"] . "</td>";
    $tblLines = $tblLines . "<td id='rowNTONS_" . $CountGridRow . "'align='right'>" . $r["net_tons"] . "</td>";
    $tblLines = $tblLines . "<td id='rowMTONS_" . $CountGridRow . "'align='right'>" . $r["m_tons"] . "</td>";
    $tblLines = $tblLines . "<td id='rowCFRMT_" . $CountGridRow . "'align='right'>" . $r["cfr_lo_mt"] . "</td>";
    $tblLines = $tblLines . "<td id='rowCFRFT_" . $CountGridRow . "' align='right'>" . $r["cfr_lo_ft"] . "</td>";
    $tblLines = $tblLines . "<td id='rowEXTPRICE_" . $CountGridRow . "'  align='right'>" . $r["ext_price"] . "</td>";
//        $tblLines .= "<td align='center'><a id='btnRemoveGridLines_" . $CountGridRow . "' OnClick='RemoveThis(this.id);'  href='#' class='btn btn-danger' ><i class='fas fa-times'></i></a></td>";
    $tblLines = $tblLines . "</tr>";
//            $s_bndl = $s_bndl + (float)str_replace(",", "", $r["bundles"]);
            $s_pieces = $s_pieces + (float)str_replace(",", "", $r["pieces"]);
            $s_length_total = $s_length_total + (float)str_replace(",", "", $r["length_total"]);
//            $s_net_tons = $s_net_tons + $r["net_tons"];
            $s_m_tons = $s_m_tons + (float)str_replace(",", "", $r["m_tons"]);
            $s_cfr_lo_mt = $s_cfr_lo_mt + (float)str_replace(",", "", $r["cfr_lo_mt"]);
            $s_cfr_lo_ft = $s_cfr_lo_ft + (float)str_replace(",", "", $r["cfr_lo_ft"]);
            $s_ext_price = $s_ext_price + (float)str_replace(",", "", $r["ext_price"]);
}
  $tblLines = $tblLines . "<tr>";
        $tblLines = $tblLines . "<td align = 'right' colspan='7'><b>Total : </b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_pieces, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_length_total, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_net_tons, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_m_tons, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_cfr_lo_mt, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_cfr_lo_ft, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_ext_price, 2) . "</b></td>";
        $tblLines = $tblLines . "</tr>";
$temp->setReplace("{table_list}", $tblLines);
$temp->setReplace("{doc_no}", $doc_no);
$temp->setReplace("{cust_num}", $Head->cust_num);

$temp->setReplace("{cust_measure}", $_SESSION["login_ms_desc"]);
echo $temp->getReplace();
sqlsrv_close($ConnWebApp);
