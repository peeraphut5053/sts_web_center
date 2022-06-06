<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$u_name = "";
if ($_SESSION["login_username"]) {
    $u_name = $_SESSION["login_username"];
} else {
    header("location: ?login");
}
if ($_SESSION["login_type"] != "A") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/error.html"));
    $temp->setReplace("{alert}", "warning");
    $temp->setReplace("{msg}", "<h4>...Sorry  This page for administrator or moderator ... </h4>");
} else {
    $temp->setReplace("{content}", $temp->getTemplate("./template/SOD/SaleOrder_Admin.html"));
//    $line = "";
//    $CustModel = new CustomerSaleOrder();
//    $CustModel->setConn($ConnWebApp);
//    $XlsForm = $CustModel->GetFormXlsListAll();
//
//    $tmpId = 0;
//    $tmpCountry = "";
//    $line2 = "";
//    $optForm = "";
//    $optCust = "";
//        $optForm = $optForm . "<option value='0' selected >Form Default</option>";
//           $line = $line . "<div class='row' style='border-bottom:solid 1px #cdcdcd;'>"
//                . "<div class='col-md-4 text-right'>"
//                . "<h4><b>Import Excel Form <font color='orange'> Default </font></b></h4>"
//                . "</div>"
//                . "<div class='col-md-8 text-left col-right'>"
//                . "<h5><b>Tag Country</b> : ALL </h5>"
//                . "<h5><b>Tag Customer</b> : ALL </h5>"
//                . "</div>"
//                . "</div>";
//    foreach ($XlsForm as $ii => $rr) {
//        $tmpId = $rr["id"];
//        $tmpCountry = $rr["country"];
//        $XlsForm2 = $CustModel->GetRows(" WHERE import_form = $tmpId ");
//        $line2 = "";
//        
//        foreach ($XlsForm2 as $iii => $rrr) {
//            $line2 = $line2 . $rrr["name"] . " , ";
//        }
//        $line2 = rtrim($line2, " , ");
//        $XlsForm2 = null;
//        $optForm = $optForm . "<option value='$tmpId'>Form $tmpId</option>";
//       
//          
//          
//        $line = $line . "<div class='row' style='border-bottom:solid 1px #cdcdcd;'>"
//                . "<div class='col-md-4 text-right'>"
//                . "<h4><b>Import Excel Form <font color='orange'> $tmpId </font></b></h4>"
//                . "</div>"
//                . "<div class='col-md-8 text-left col-right'>"
//                . "<h5><b>Tag Country</b> : $tmpCountry</h5>"
//                . "<h5><b>Tag Customer</b> : $line2</h5>"
//                . "</div>"
//                . "</div>";
//    }
//
//    $XlsForm = null;
//    $CustModel = null;


//    $temp->setReplace("{pagecontent}", $line);
//    $temp->setReplace("{selFormOptions}", $optForm);
}
//$temp->setReplace("{selCustOptions}",$optCust);
