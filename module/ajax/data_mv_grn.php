<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}
include "./initial.php";

if ($action == "GetUFmanu") {
        $V = new V_MV_GRN();
        $lot = $_POST["lot"];
        $grn_num = $_POST["grn_num"];
        $V->setConn($ConnSL);
        $V->_grn_num = $grn_num ;
        $V->_lot = $lot ;
        $V->GetProperties();        
        echo $V->UF_manu ;

}


