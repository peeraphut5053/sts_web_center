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

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}


    $CM = new CallModel();
    $CM->SyteLine_Models();
    
   
    
    $Customer = new Customer();
    $Customer->setConn($ConnSL);
        $Customer->_criteria2 = " AND ( left(cust_num,2) <> 'EX' or left(cust_num,2) <> 'EB' ) ";
    $CustomerS = $Customer->GetRowsAddr();
    $line="";
//    $line = "<option value=''>ทั้งหมด</option>";
    foreach ($CustomerS as $ii => $rr) {
        $line .= "<option value='" . $rr["cust_num"] . "'>" . $rr["cust_name"] . "</option>";
    }
//    echo $line;
    

$temp->setReplace("{content}", $temp->getTemplate("./template/RPTINV_ITEM_IN/index.html"));
    
$temp->setReplace("{customer_data}", $line);


