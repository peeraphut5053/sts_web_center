<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

include "../initial.php";

if (isset($_GET["SwitchSale"])){
    $SwitchSale = trim($_GET["SwitchSale"]);
}  else {
    $SwitchSale = "IN";
}



$CM = new CallModel();
$CM->SyteLine_Models();

$CustCriteria = "";

if ($SwitchSale == "IN") {
    $SaleDescription = "รายงานประวัติการขายสินค้า (ในประเทศ)";
    $CustCriteria = " AND ( left(cust_num,2) <> 'EX' and left(cust_num,2) <> 'EB' ) ";
} else {
    $SaleDescription = "รายงานประวัติการขายสินค้า (ต่างประเทศ)";
    $CustCriteria = " AND ( left(cust_num,2) = 'EX' or left(cust_num,2) = 'EB' ) ";
}
//echo $CustCriteria;
$Customer = new Customer();


$Customer->setConn($ConnSL);
$Customer->_criteria2 = $CustCriteria;
$CustomerS = $Customer->GetRowsAddr();
$line = "";
foreach ($CustomerS as $ii => $rr) {
    $line .= "<option value='" . $rr["cust_num"] . "'>" . $rr["cust_name"] . "</option>";
}
$temp = new ReplaceHtml("../../template/RPTINV_ITEM/index.html");

//$temp->setReplace("{content}", $temp->getTemplate("./template/RPTINV_ITEM/index.html"));
$temp->setReplace("{SwitchSale}", $SwitchSale);
$temp->setReplace("{SaleDescription}", $SaleDescription);
$temp->setReplace("{customer_data}", $line);

echo $temp->getReplace();
sqlsrv_close($ConnSL);


