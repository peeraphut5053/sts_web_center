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
    $GL = new Factory();
    $GL->setConn($ConnSL);
    $GLS = $GL->RPT_STS_factory_fix_report_sub($ref,$acct_unit1);
    $CM = null;
    $GL = null;
    $tdDetail = "";
    foreach ($GLS as $index => $rows){
        $tdDetail = $tdDetail ."<tr>";
        $tdDetail = $tdDetail ."<td>" .$rows["ref"]."</td>";
        $tdDetail = $tdDetail ."<td>" .$rows["po_num"]."</td>";
        $tdDetail = $tdDetail ."<td>" .$rows["vend_name"]."</td>";
        $tdDetail = $tdDetail ."<td>" .$rows["item"]."</td>";
        $tdDetail = $tdDetail ."<td>" .$rows["item_name"]."</td>";
        $tdDetail = $tdDetail ."<td align='right' class='money'>" .number_format ($rows["qty_voucher"], 2, ".", ",")."</td>";
        $tdDetail = $tdDetail ."<td align='right' class='money'>" .number_format ($rows["item_cost_conv"], 2, ".", ",")."</td>";
        $tdDetail = $tdDetail ."<td align='right' class='money'>" .number_format ($rows["tot_rec_cost"], 2, ".", ",")."</td>";
        //$tdDetail = $tdDetail ."<td align='right' class='qty_kg'>" .$rows["qty_kg"]."</td>";
        $tdDetail = $tdDetail ."</tr>";
    }
     
    //echo json_encode($GLS);
    $temp = new ReplaceHtml("../../template/RPT_STS_factory_fix_report/load_form.html");
    $temp->setReplace("{ref_num}", $ref);
    $temp->setReplace("{acct_unit1}", $acct_unit1);
    $temp->setReplace("{tableDetail}", $tdDetail);
    echo $temp->getReplace();
}








