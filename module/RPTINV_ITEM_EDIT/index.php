<?php

//================Get PROJECT CODE =================
//if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
//    $link = "https"; 
//else
//    $link = "http";   
//$link .= "://";  
//$link .= $_SERVER['HTTP_HOST'];   
//$link .= $_SERVER['REQUEST_URI'];
//$links = explode("/",$link);
//$link_2 = explode("?",$links[4]);
//
//$prj_code= $link_2[1]; 
//echo $prj_code ;
//====================================================

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
 $SwitchSale = trim($_GET["SwitchSale"]) ;
// $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 


$CM = new CallModel();
$CM->SyteLine_Models();

$CustCriteria = "";

if ($SwitchSale == "IN") {
    $SaleDescription = "รายงานประวัติการขายสินค้า (ในประเทศ) Edit Mode  ก่อนวันที่ 15 พ.ค. 62";
    $CustCriteria = " AND ( left(cust_num,2) <> 'EX' and left(cust_num,2) <> 'EB') ";
    
} else {
    $SaleDescription = "รายงานประวัติการขายสินค้า (ต่างประเทศ) Edit Mode  ก่อนวันที่ 15 พ.ค. 62";
    $CustCriteria = " AND ( left(cust_num,2) = 'EX' or left(cust_num,2) = 'EB' )  ";
    
}
//echo $CustCriteria;

$temp->setReplace("{content}", $temp->getTemplate("./template/RPTINV_ITEM_EDIT/index.html"));
$temp->setReplace("{SwitchSale}", $SwitchSale);
$temp->setReplace("{SaleDescription}", $SaleDescription);


//$temp->setReplace("{customer_data}", $line);


