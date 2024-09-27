<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
//echo $conn;
$Boat = new Boat();
$Boat->setConn($conn);
$Boat->_IdRun = $idRun ;
$Boat->GetProperty();
if($field == "id"){
    return $Boat->IdRun ;
}else if($field == "mv"){
        echo $Boat->Ship_MV ;
}else if($field == "ln"){
        echo $Boat->Ship_LighterNo ;
}else {
        echo "N/A";
}
sqlsrv_close($conn);