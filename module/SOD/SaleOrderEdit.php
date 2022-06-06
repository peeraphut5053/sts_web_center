<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

if (isset($_POST["ShowTableOnl"]) && ($ShowTableOnly == "Y")) {
    /// Replace when show in dialog 
    include "./initial.php";
    session_start();
    $temp = new ReplaceHtml("../template/SOD/SaleOrderEdit.html");
} else {

    /// Replace when show normal
    $temp->setReplace("{content}", $temp->getTemplate("./template/SOD/SaleOrderEdit.html"));
}

$u_name = $_SESSION["login_username"];
$User = new User();
$User->setConn($ConnWebApp);
$User->GetProperties(" WHERE username = '$u_name' ");
$temp->setReplace("{cust_measure}", $User->measure_desc);
$temp->setReplace("{doc_date}", date('Y-m-d'));
$Head = new OrderHead();
$Head->setConn($ConnWebApp);
$Head->_doc_no = $doc_no;
$Head->GetPropertiesByDocNo();

$temp->setReplace("{head_doc_no}", $Head->doc_no);
$temp->setReplace("{head_po_num}", $Head->po_num);
$temp->setReplace("{head_doc_date}", $Head->doc_date);
$temp->setReplace("{head_po_date}", $Head->po_date);
$temp->setReplace("{head_ship_from}", $Head->ship_from);
$temp->setReplace("{head_ship_to}", $Head->ship_to_text);
$temp->setReplace("{head_ship_by}", $Head->ship_by_name);
$temp->setReplace("{head_spec}", $Head->spec);
$temp->setReplace("{head_terms}", $Head->term_of_po);
$temp->setReplace("{head_attn}", $Head->attn);
$temp->setReplace("{head_shipment}", $Head->ShipExTo ." - ".$Head->ShipExTo);
$temp->setReplace("{head_remark}", $Head->remark);

$Vend = new CustomerSaleOrder();
$Vend->setConn($ConnWebApp);
$Vend->_cust_num = $Head->cust_num;
$Vend->GetPropertyByVendnum();




$temp->setReplace("{head_cust_name}", $Vend->name);
$temp->setReplace("{head_cust_addr}", $Vend->addr1 . ' ' . $Vend->addr2 . ' ' . $Vend->addr3);
$temp->setReplace("{head_cust_tel}", $Vend->tel1 . ' ' . $Vend->tel2);
$temp->setReplace("{head_cust_fax}", $Vend->fax1 . ' ' . $Vend->fax2);
$approve_button = "";
// === Check User Level and System level ===//
$S = new STS_Sys_Config();
$S->setConn($ConnWebApp);
$S->GetProperties();
// === Check User Level and System level ===//


if ($User->sys_level >= $S->SO_Approve_Level_OverOrEqual) {
    if ($Head->status == "P") {

        $approve_button = "<a OnClick='Approve(this.id);' data-approve='A'  href='#' id='btnApprove'  class='btn btn-info'><i class='fas fa-check-circle'></i> APPROVE</a>";
    } else if ($Head->status == "A") {
        $approve_button = "<a OnClick='Approve(this.id);' data-approve='P' href='#' id='btnApprove'  class='btn btn-danger'><i class='fas fa-times'></i> CANCEL APPROVE</a>";
    }
}
//$temp->setReplace("{approve_button}", $approve_button);
$temp->setReplace("{cust_num}", $_SESSION["login_cust_num"]);
$temp->setReplace("{ExDateFrom}", $Head->ShipExFrom );
$temp->setReplace("{ExDateTo}", $Head->ShipExTo );
$temp->setReplace("{firstSelected}", $Head->ship_to );
if (isset($_POST["fltStartDate"])) {
    $temp->setReplace("{fltStartDate}", $fltStartDate);
    $temp->setReplace("{fltEndDate}", $fltEndDate);
    $temp->setReplace("{fltStatus}", $fltStatus);
}
if (isset($_POST["ShowTableOnl"]) && ($ShowTableOnly == "Y")) {

    $temp->setReplace("{fixDialogStyle1}", "style='display:none;'");
    $temp->setReplace("{fixDialogStyle2}", "style='margin-left:20px;margin-right:20px;height:200px;overflow-y:scroll;''");
    echo $temp->getReplace();
} else {
    $temp->setReplace("{fixDialogStyle1}", "style=''");
    $temp->setReplace("{fixDialogStyle2}", "style='display:none;'");
}
