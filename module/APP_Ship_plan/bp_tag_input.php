<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
$Item = new Item();
$Item->setConn($conn_tag);
$Item->_id = $id ;
//echo $Item->isDup() ;
if($Item->isDup()==false ){
    echo "Error ! Item not found ." ;
}else{
    $Item->GetProperty();
//    $arr = array();

    echo $Item->item ."_".$Item->qty1 ;
}
//echo "ttttqqqq.123";

sqlsrv_close($conn);



