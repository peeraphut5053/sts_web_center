<?php

//===========initial file requirement=========//
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once '../../initial.php';

if (1 == 1) {

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $GL = new PurchaseOrder();
    $GL->setConn($ConnSL);
    $GL->_Year = $Year;
    $GL->_Month = $Month;
    $GL->_Saleside = $Saleside;
    $GL->_Type = "Detail";
    $GL->_Vend_num = $vend_num;
    $GL->_item_group = $item_group;
    $GLS = $GL->GetRows_SP_RPT_MAT_PURCHASE();
    $CM = null;
    $GL = null;
    $tdDetail = "";
    foreach ($GLS as $index => $rows){
        $tdDetail = $tdDetail ."<tr>";
        $tdDetail = $tdDetail ."<td>" .$rows["item"]."</td>";
        $tdDetail = $tdDetail ."<td>" .$rows["po_num"]."</td>";
        $tdDetail = $tdDetail ."<td>" .$rows["voucher"]."</td>";
        //$tdDetail = $tdDetail ."<td align='right' class='qty_kg'>" .$rows["qty_kg"]."</td>";
        $tdDetail = $tdDetail ."<td align='right' class='money'>" .number_format ($rows["money"], 2, ".", ",")."</td>";
        $tdDetail = $tdDetail ."</tr>";
    }
     
    //echo json_encode($GLS);
    $temp = new ReplaceHtml("../../template/RPT_MAT_PURCHASE/_From.html");
    $temp->setReplace("{vend_num}", $vend_num);
    $temp->setReplace("{vend_name}", $vend_name);
    $temp->setReplace("{item_group}", $item_group);
    $temp->setReplace("{total_amount}", $total_amount);
    $temp->setReplace("{tableDetail}", $tdDetail);
    echo $temp->getReplace();
}








