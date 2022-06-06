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
    $temp->setReplace("{crumb}", "");
    $temp->setReplace("{PageName}", "Sale Orders");
    $temp->setReplace("{content}", $temp->getTemplate("./template/SOD/SaleOrders.html"));
} else {
//============== Render Ajax =======================//
    include "./initial.php";
    if (!isset($_SESSION)) {
        session_start();
    }
    if ($_POST["action"] == "CheckSizeDup") {
        $P = new CustomerNPS();
        $P->setConn($ConnWebApp);
        $P->_cust_num = $_SESSION["login_cust_num"];
        $P->_nps = $_POST["nps"];
        $isDup = $P->isDup();
        $P = null;
        echo $isDup;
    }
    if ($_POST["action"] == "AddNewSize") {
        $P = new CustomerNPS();
        $P->setConn($ConnWebApp);
        $P->_cust_num = $_SESSION["login_cust_num"];
        $P->_nps = $_POST["nps"];
        $ins = $P->Insert();
        $ret = "";
        // get last id 
        if ($ins == "1") {
            $L = new CustomerNPS();
            $L->setConn($ConnWebApp);
            $L->_cust_num = $_SESSION["login_cust_num"];
            $L->_nps = $_POST["nps"];
            $L->GetProperties();
            $ret = $L->id;
        } else {
            $ret = "";
        }
        $P = null;
        $L ? $L = null : $L;
        echo $ret;
    }


    if ($_POST["action"] == "CheckSchedDup") {
        $P = new CustomerSCHED();
        $P->setConn($ConnWebApp);
        $P->_cust_num = $_SESSION["login_cust_num"];
        $P->_sched = $_POST["Sched"];
        $isDup = $P->isDup();
        $P = null;
        echo $isDup;
    }
    if ($_POST["action"] == "AddNewSched") {
        $P = new CustomerSCHED();
        $P->setConn($ConnWebApp);
        $P->_cust_num = $_SESSION["login_cust_num"];
        $P->_sched = $_POST["Sched"];
        $ins = $P->Insert();
        $ret = "";
        // get last id 
        if ($ins == "1") {
            $L = new CustomerSCHED();
            $L->setConn($ConnWebApp);
            $L->_cust_num = $_SESSION["login_cust_num"];
            $L->_sched = $_POST["Sched"];
            $L->GetProperties();
            $ret = $L->id;
        } else {
            $ret = "";
        }
        $P = null;
        $L ? $L = null : $L;
        echo $ret;
    }


    if ($_POST["action"] == "CheckPortDup") {
        $P = new CustomerShipToPort();
        $P->setConn($ConnWebApp);
        $P->_cust_num = $_SESSION["login_cust_num"];
        $P->_port = $_POST["Port"];
        $isDup = $P->isDup();
        $P = null;
        echo $isDup;
    }
    if ($_POST["action"] == "AddNewPort") {
        $P = new CustomerShipToPort();
        $P->setConn($ConnWebApp);
        $P->_cust_num = $_SESSION["login_cust_num"];
        $P->_port = $_POST["Port"];
        $ins = $P->Insert();
        $ret = "";
        // get last id 
        if ($ins == "1") {
            $L = new CustomerShipToPort();
            $L->setConn($ConnWebApp);
            $L->_cust_num = $_SESSION["login_cust_num"];
            $L->_port = $_POST["Port"];
            $L->GetProperties();
            $ret = $L->id;
        } else {
            $ret = "";
        }
        $P = null;
        $L ? $L = null : $L;
        echo $ret;
    }
    if ($_POST["action"] == "GetPortToDropDown") {
//        session_start();
        $Cust = new CustomerShipToPort();
        $Cust->setConn($ConnWebApp);
        $Cust->_cust_num = $_SESSION["login_cust_num"];
        $CustList = $Cust->GetItemToDropdownByCustNum();
        echo json_encode($CustList);
    }
    if ($_POST["action"] == "GetItemToDropdown") {
        $Cust = new CustomorSaleOrder();
        $Cust->setConn($ConnWebApp);
        $CustList = $Cust->GetItemToDropdown("");
        echo json_encode($CustList);
    }

    if ($_POST["action"] == "GetCustomerInfo") {
//        session_start();
        $result = array();
        $cust_num="";

        if (!isset($_SESSION)) {
            $result["state"] = 0;
            $result["msg"] = "Error ! Not found user information ";
        } else {

            if (!$p_cust_num) {
                $User = new User();
                $User->setConn($ConnWebApp);
                $User->GetProperties(" WHERE user_id = " . $_SESSION["login_user_id"]);

                $cust_num = $User->ref_so_custnum;
            }else{
                $cust_num = $p_cust_num;
            }


            if (($cust_num == "") || ($cust_num == NULL)) {
                $result["state"] = 0;
                $result["msg"] = "Error ! This user is no custor information ";
            } else {
                $criteria = " WHERE cust_num = '$cust_num' ";
                $Cust = new CustomerSaleOrder();
                $Cust->setConn($ConnWebApp);
                $Cust->GetProperty($criteria);
                $addr = $Cust->addr1 . " " . $Cust->addr2 . " " . $Cust->addr3;
                $tel = "";
                $fax = "";
                $Cust->tel2 ? $tel = "Tel : " . $Cust->tel1 . " , " . $Cust->tel2 : $tel = "Tel : " . $Cust->tel1;
                $Cust->fax2 ? $fax = "Fax : " . $Cust->fax1 . " , " . $Cust->fax2 : $fax = "Fax : " . $Cust->fax1;

                $result["state"] = 1;
//                $result["msg"] = "Error ! Not found user information ";
                $result["name"] = $Cust->name;
                $result["addr"] = $addr;
                $result["tel"] = $tel;
                $result["fax"] = $fax;
            }
        }


        echo json_encode($result);
    }
    if ($_POST["action"] == "GetCustName") {
        $import_form = $_POST["import_form"];
        $C = new CustomerSaleOrder();
        $C->setConn($ConnWebApp);
        $spart = " WHERE import_form = $import_form " ;
        if(($import_form == 0) || ($import_form =="0")) {
           $spart = "" ;
        }
        
        $C_All = $C->GetItemToDropdown($spart);
        $C = null;
        echo json_encode($C_All);
    }
     if ($_POST["action"] == "SearchCustomer") {
        $name = $_POST["name"];
        
        $C = new CustomerSaleOrder();
        $C->setConn($ConnWebApp);
        $spart = " WHERE name like '%$name%' " ;
//        if(($import_form == 0) || ($import_form =="0")) {
//           $spart = "" ;
//        }
        
        $C_All = $C->GetRows($spart);
        $C = null;
        echo json_encode($C_All);
    }
    
}

// if($type=="page"){
//
// }else if($type =="ajax"){
//   if($_POST["action"]=="GetRows"){

//   }
//
// }


// $temp->setReplace("{ConSlVal}", $ConnSL);
