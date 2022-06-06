<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
if(!$_SESSION){
    session_start();
}


//============== Render Page Normal ================//
if (!isset($_POST["ajax"])) {
    $loadPage = "home.html";
    $User = new User();
    $User->setConn($ConnWebApp);
    $User->GetProperties(" where user_id = ".$_SESSION["login_user_id"]) ;
    
    $temp->setReplace("{content}", $temp->getTemplate("./template/ChangePassword.html"));
    $temp->setReplace("{userId}", $User->id);
    $temp->setReplace("{oldPass}", $User->password);
//    $temp->setReplace("{PoNum}",$po_num);
} else {
//============== Render Ajax =======================//
    include "./initial.php";
    if ($_POST["action"] == "GetSite") {
        $Site = new Site();
        $Site->setConn($ConnWebApp);
        $GetSite = $Site->GetRows("");
        $data = "";
        $data = $data . "<option value='0'>.. Please select site ..</option>";
        foreach ($GetSite as $index => $rows) {
            $data = $data . "<option value='" . $rows["site_id"] . "'>" . $rows["site_name"] . " (" . $rows["site_measure"] . " ) </option>";
        }

        echo $data;
    }
    if ($_POST["action"] == "GetItems") {

        $Item = new ItemSyteline();
        $Item->setConn($ConnSL);
        $GetItem = $Item->GetRows("");
        $data = "";

        foreach ($GetItem as $index => $rows) {
            $data = $data . $rows["item"] . ",";
        }
        echo $data;
    }
    if ($_POST["action"] == "GetNewPo") {
        $Item = new OrderHead();
        $Item->setConn($ConnWebApp);
        $GetItem = $Item->GenNewPoNum();
       
        echo $GetItem;
    }
    
    if ($_POST["action"] == "SavePO") {
        $result = array();
        $Head = new OrderHead();
        $Head->setConn($ConnWebApp);
        $Head->_po_num = $_POST["po_num"]; 
        $Head->_po_date = $_POST["po_date"]; 
        $Head->_ship_from = $_POST["ship_from"]; 
        $Head->_ship_from_seq = $_POST["ship_from_seq"]; 
        $Head->_ship_to = $_POST["ship_to"]; 
        $Head->_ship_to_seq = $_POST["ship_to_seq"]; 
        $Head->_ShipExFrom = $_POST["ShipExFrom"]; 
        $Head->_ShipExTo = $_POST["ShipExTo"]; 
        $Head->_spec = $_POST["spec"]; 
        $Head->_attn = $_POST["attn"]; 
        $Head->_terms = $_POST["terms"]; 
        $Head->_remark = $_POST["remark"]; 
        $Head->Insert();
       
        $Detail = new OrderDetail();
        $Detail->setConn($ConnWebApp);
        $Detail->_po_num = $_POST["po_num"]; 
        $Detail->_lot =$_POST["lot"] ;
        $Detail->_item =$_POST["item"] ;
        $Detail->_item_desc =$_POST["item_desc"] ;
        $Detail->_nps =$_POST["nps"] ;
        $Detail->_wall =$_POST["wall"] ;
        $Detail->_length =$_POST["length"] ;
        $Detail->_per_measure =$_POST["per_measure"] ;
        $Detail->_end_val =$_POST["end_val"] ;
        $Detail->_grade =$_POST["grade"] ;
        $Detail->_qty_ft =$_POST["qty_ft"] ;
        $Detail->_qty_mt =$_POST["qty_mt"] ;
        $Detail->_us_per_ft =$_POST["us_per_ft"] ;
        $Detail->_us_per_mt =$_POST["us_per_mt"] ;
        $Detail->_cost_ext =$_POST["cost_ext"] ;
        $Detail->Insert();
        
//        
//        $result["state"]=1;
//        $result["msg"]="Save PO Complete";
        echo json_encode($result);
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
