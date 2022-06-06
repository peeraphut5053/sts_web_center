<?php

class ShipToSyteline {


    var $StrConn = "";
    public $_item = "";
    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetItemToDropdown() {
        session_start();
        $CustNum = $_SESSION["login_link_cust_num"];
        $query = "SELECT * FROM shipto_mst    ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "" ;
        $tempValue = "" ;
        $returnArray[""] = "" ;
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["drop_ship_no"];
            $tempValue = $rows["addr##1"]. " " .$rows["addr##2"]. " " .$rows["addr##3"]. " " .$rows["addr##4"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

}
