<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}


//============== Render Page Normal ================//
if (!isset($_POST["ajax"])) {
    // $temp->setReplace("{crumb}", "");
    // $temp->setReplace("{PageName}", "Sale Orders");
    // $temp->setReplace("{content}", $temp->getTemplate("./template/SOD/SaleOrders.html"));
} else {
   if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//============== Render Ajax =======================//
    include "./initial.php";
     if ($_POST["action"] == "ShipFromCustomer") {
        $CustAddr = new CustomerAddrSyteline();
        $CustAddr->setConn($ConnSL);
        $C_List = $CustAddr->GetItemToDropdownByCustNum();
        echo json_encode($C_List);
    }
      if ($_POST["action"] == "ShipTo") {
        $S = new ShipToSyteline();
        $S->setConn($ConnSL);
        $SS = $S->GetItemToDropdown();
        echo json_encode($SS);
    }
    // ท่าเรือ ขึ้น 0  ลง 1 
    if ($_POST["action"] == "GetHarborToDropdown_From") {
        $Harbor = new Harbor();
        $Harbor->setConn($ConnWebApp);
        $HarborList = $Harbor->GetItemToDropdown(" WHERE HB_Status = 2 OR HB_Status = 0 ");
        echo json_encode($HarborList);
    } else if ($_POST["action"] == "GetHarborToDropdown_To") {
        $Harbor = new Harbor();
        $Harbor->setConn($ConnWebApp);
        $HarborList = $Harbor->GetItemToDropdown(" WHERE HB_Status = 2 OR HB_Status = 1 ");
        echo json_encode($HarborList);
    } else if ($_POST["action"] == "GetHarborToDropdown_All") {
        $Harbor = new Harbor();
        $Harbor->setConn($ConnWebApp);
        $HarborList = $Harbor->GetItemToDropdown("");
        echo json_encode($HarborList);
    } else if ($_POST["action"] == "GetShipToDropdown_All") {
        $Ship = new Ship();
        $Ship->setConn($ConnWebApp);
        $ShipList = $Ship->GetItemToDropdown("");
        echo json_encode($ShipList);
    } else if ($_POST["action"] == "GetItemCategoryToDropdown_All") {
        $Cat = new ItemCategory();
        $Cat->setConn($ConnSL);
        $CatList = $Cat->GetItemToDropdown("");
        echo json_encode($CatList);
    } else if ($_POST["action"] == "GetItemToDropdown") {
        $Item = new ItemSyteLine();
        $Cate = $_POST["Cate"];
        $Item->_item_category = $Cate;
        $Item->setConn($ConnSL);
        $List = $Item->GetItemToDropdown();
        echo json_encode($List);
    }
     else if ($_POST["action"] == "GetUFOptionFromSyteline") {
        $G = new UserDefinedTypeValuesSyteline();
        $G->setConn($ConnSL);
        $List = $G->GetUFOptionList("TYPE");
        echo json_encode($List);
    }
     else if ($_POST["action"] == "GetCustomerSCHED") {
        $G = new CustomerSCHED();
        $G->setConn($ConnWebApp);
        $G->_cust_num = $_SESSION["login_cust_num"];
        $List = $G->GetItemToDropdownByCustNum();
        $G=null;
        echo json_encode($List);
    }
    else if ($_POST["action"] == "GetCustomerNPS") {
        $G = new CustomerNPS();
        $G->setConn($ConnWebApp);
        $G->_cust_num = $_SESSION["login_cust_num"];
        $List = $G->GetItemToDropdownByCustNum();
        $G=null;
        echo json_encode($List);
    }
    else if ($_POST["action"] == "GetItemPropertyForFilter") {
        $Item = new ItemSyteLine();
        $prop = $_POST["prop"];
        $cate = $_POST["cate"];
        $Item->setConn($ConnSL);
        $List = $Item->GetItemPropertyForFilter($prop, $cate);
        echo json_encode($List);
    } else if ($_POST["action"] == "GetItemFromSyteline") {
        $Items = new ItemSyteLine();
        $cate = $_POST["cate"];
        $nps = $_POST["nps"];
        $grade = $_POST["grade"];
        $sched = $_POST["sched"];
        
        $Items->setConn($ConnSL);
        $Items->_Uf_grade = $grade;
        $Items->_Uf_Nps= $nps;
        $Items->_Uf_sched = $sched;
        $Items->_item_category = $cate;
        $List = $Items->GetItems();
        echo json_encode($List);
    } else if ($_POST["action"] == "CheckDepDup") {
        $Dep = new Department();
        $Dep->setConn($ConnWebApp);
        $Dep->GetProperties(" WHERE dep_name = '" . $_POST["dep_name"] . "' ");
        $Result = "";
        $Result = $Dep->dep_name;
        $Result == "" ? $R = 0 : $R = 1;
        echo $R;
    } else if ($_POST["action"] == "CheckDepPosDup") {
        $Deppos = new DepartmentPosition();
        $Deppos->setConn($ConnWebApp);
        $Deppos->GetProperties(" WHERE dep.dep_id = " . $_POST["dep_id"] . " AND  dep_pos_name = '" . $_POST["pos_name"] . "' ");
        $Result = "";
        $Result = $Deppos->dep_pos_name;
        $Result == "" ? $R = 0 : $R = 1;
        echo $R;
    } else if ($_POST["action"] == "AddNewDep") {
        $Dep = new Department();
        $Dep->setConn($ConnWebApp);
        $Dep->_dep_name = $_POST["dep_name"];
        $Dep->_head_id = $_POST["head_id"];
        $Dep->_sec_id = $_POST["sec_id"];
        $Dep->_dep_tel = $_POST["dep_tel"];
        $Dep->_dep_fax = $_POST["dep_fax"];
        $Dep->_dep_email = $_POST["dep_email"];
        $R = $Dep->Insert();
        $Result = array();
        if ($R == 0) {
            $Result["result"] = 0;
            $Result["msg"] = "Error";
        } else {
            $Result["result"] = 0;
            $Result["msg"] = "Complete";
        }

        echo json_encode($Result);
    } else if ($_POST["action"] == "AddNewDepPos") {
        $Dep = new DepartmentPosition();
        $Dep->setConn($ConnWebApp);
        $Dep->_dep_id = $_POST["dep_id"];
        $Dep->_dep_pos_name = $_POST["dep_pos_name"];
        $Dep->_dep_pos_level = $_POST["dep_pos_level"];

        $R = $Dep->Insert();
        $Result = array();
        if ($R == 0) {
            $Result["result"] = 0;
            $Result["msg"] = "Error";
        } else {
            $Result["result"] = 0;
            $Result["msg"] = "Complete";
        }

        echo json_encode($Result);
    } else if ($_POST["action"] == "GetDepRows") {
        $Dep = new Department();
        $Dep->setConn($ConnWebApp);
        $R = array();
        $R = $Dep->GetRows("");
        echo json_encode($R);
    } else if ($_POST["action"] == "GetDepPosRows") {
        $Dep = new DepartmentPosition();
        $Dep->setConn($ConnWebApp);
        $R = array();
        $R = $Dep->GetRows(" WHERE deppos.dep_id = " . $_POST["dep_id"]);
        echo json_encode($R);
    } 
}
