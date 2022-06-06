<?php

class CustomerAddrSyteline {


    var $StrConn = "";
    public $_item = "";
    public $_CustNum = "" ;
    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetItemToDropdownByCustNum() {
        session_start();
        $CustNum = $_SESSION["login_link_cust_num"];
        $query = "SELECT * FROM custaddr_mst  where cust_num ='$CustNum' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "" ;
        $tempValue = "" ;
        $returnArray[""] = "" ;
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["cust_seq"];
            $tempValue = $rows["addr##1"]. " " .$rows["addr##2"]. " " .$rows["addr##3"]. " " .$rows["addr##4"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

}
