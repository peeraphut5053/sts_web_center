<?php

//===================================================//
//===================================================//
//===================================================//
//=============== /SOD/SaleOrders Module   ===============//
//===================================================//
//===================================================//
//===================================================//
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}


//============== Render Page Normal ================//
if (!isset($_POST["ajax"])) {
//    $temp->setReplace("{crumb}", "");
//    $temp->setReplace("{PageName}", "Sale Orders");
//    $temp->setReplace("{content}", $temp->getTemplate("./template/SOD/SaleOrders.html"));
} else {
//============== Render Ajax =======================//
    include "./initial.php";
    if (!isset($_SESSION)) {
        session_start();
    }

    if ($_POST["action"] == "GetCustomerSyteline_") {
        $cust_num = "";
        isset($_POST["cust_num"]) ? $cust_num = $_POST["cust_num"] : $cust_num = "";
        $str_query = "select cust_num_sl FROM SO_Customer  WHERE cust_num ='$cust_num' ";
        $cSql = new SqlSrv();
        $result = $cSql->SqlQuery($ConnWebApp, $str_query);
        array_splice($result, count($result) - 1, 1);
        $link_cust_num = $result[0]["cust_num_sl"];

        $result2 = array();
        if (count($result) > 0) {
            $str_query = "select *,addr##1 as addr1,addr##2 as addr2,addr##3 as addr3,addr##4 as addr4 FROM custaddr_mst  WHERE cust_num ='$link_cust_num' ";
            $cSql = new SqlSrv();
            $result2 = $cSql->SqlQuery($ConnSL, $str_query);
            array_splice($result2, count($result2) - 1, 1);
        }
        echo json_encode($result2);
    }

    if ($_POST["action"] == "GetCustomerSyteline") {
        $cust_num_sl = $_POST["cust_num_sl"];
        $str_query = "select *,addr##1 as addr1,addr##2 as addr2,addr##3 as addr3,addr##4 as addr4 FROM custaddr_mst  WHERE cust_num ='$cust_num_sl' ";
        $cSql = new SqlSrv();
        $result2 = $cSql->SqlQuery($ConnSL, $str_query);
        array_splice($result2, count($result2) - 1, 1);

//        }
        echo json_encode($result2);
    }
}