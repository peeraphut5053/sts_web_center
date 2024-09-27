<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";
if ($load == "form") {

} else if ($load == "checkInv") {
    $result ="" ;
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Inv = new Invoice();
    $Inv->setConn($ConnSL) ;
    $Inv->_inv_num = $inv_num ;
    $Invoice = $Inv->CheckInv();
    if ( count($Invoice) >=1){     
        $result = $Inv->Consolidate_Commit_Inv($inv_num);
    }else{
        $result = "Not Found";
    }
    echo $result;
}
