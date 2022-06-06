<?php

//============= initial module =====
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";
$temp = new ReplaceHtml("../../template/RPTINV_Deposit/index.html");
// //=============Get project name //
//$prj_code = GetProjectCode();
//$Prj = new CallModel();
//$Prj->SyteLine_Models();
//$Prj->setConn($ConnSL);
//$Prj = new Project();

//$Prj->GetProperties(" prj_code = '$prj_code' ");
//$prjDesc = $Prj->prj_description;
//$Prj = null;
//====================================================
    $CM = new CallModel();
    $CM->SyteLine_Models();
$CustCriteria = " AND ( left(cust_num,2) <> 'EX' and left(cust_num,2) <> 'EB' ) ";
$Customer = new Customer();
$Customer->setConn($ConnSL);
$Customer->_criteria2 = $CustCriteria;
$CustomerS = $Customer->GetRowsAddr();
$Customer = null;
$line = "";
foreach ($CustomerS as $ii => $rr) {
    $line .= "<option value='" . $rr["cust_num"] . "'>" . $rr["cust_name"] . "</option>";
}
//$temp = new ReplaceHtml("../../template/$prj_code/index.html");

//$temp->setReplace("{content}", $temp->getTemplate("./template/$prj_code/index.html"));
$temp->setReplace("{prjDesc}", "Deposit Report");
$temp->setReplace("{prj_code}", "RPTINV_Deposit");
$temp->setReplace("{customer_data}", $line);


echo $temp->getReplace();
sqlsrv_close($ConnSL);
