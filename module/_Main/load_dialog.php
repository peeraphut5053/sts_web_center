<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//ini_set('display_startup_errors', true);
//error_reporting(E_ALL);
//ini_set('display_errors', true);
//
//include "initial.php";
include "../../initial.php";
if ($load == "CustomerSearchForm") {
    $temp = new ReplaceHtml("../../template/_Main/CustomerSearch.html");
//    $temp->setReplace("{content}", $temp->getTemplate("./template/AxTag/index.html"));
//    $temp->setReplace("{table_list}", $tblLines);
    echo $temp->getReplace();
} else if ($load == "CustomerSearch") {
//    echo "123";
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Customer = new Customer();
    $Customer->setConn($ConnSL);
    $Customer->_criteria = $txtSearch;
    $CustomerS = $Customer->GetRowsAddr();
    $line = "";
    foreach ($CustomerS as $ii => $rr) {
        $line .= "<tr>"
                . "<td>" . $rr["cust_num"] . "</td>"
                . "<td>" . $rr["cust_name"] . "</td>"
                . "<td>" . $rr["addr1"] . "</td>"
                . "<td>" . $rr["addr2"] . "</td>"
                . "<td>" . $rr["addr3"] . "</td>"
                . "</tr>";
    }
    echo $line;
//    echo json_encode($CustomerS);   
}




//sqlsrv_close($ConnSL);
