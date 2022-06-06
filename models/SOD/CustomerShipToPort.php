<?php

class CustomerShipToPort {

    var $StrConn = "";
    public $id = "";
    public $port = "";
    public $cust_num = "";
    public $_id = "";
    public $_port = "";
    public $_cust_num = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetItemToDropdownByCustNum() {
//        session_start();
        $CustNum = $this->_cust_num;
        $query = "SELECT * FROM SO_CustomerShipToPort WHERE cust_num ='$CustNum' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "";
        $tempValue = "";
        $returnArray[""] = "";
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["id"];
            $tempValue = $rows["port"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function isDup() {
        $CustNum = $this->_cust_num;
        $Port = $this->_port;
        $query = "SELECT * FROM SO_CustomerShipToPort WHERE cust_num ='$CustNum' AND port = '$Port' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        return count($rs0) ;
    }

    function Insert() {
        $CustNum = $this->_cust_num;
        $Port = $this->_port;
        $query = "Insert Into SO_CustomerShipToPort (cust_num,port) VALUES ('$CustNum','$Port')  ";
        $cSql = new SqlSrv();
        $result = $cSql->IsUpDel($this->StrConn, $query);
        return $result;
    }

    function GetProperties() {
        $CustNum = $this->_cust_num;
        $Port = $this->_port;
        $query = "SELECT id FROM SO_CustomerShipToPort  WHERE cust_num ='$CustNum' AND port = '$Port'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);   
        $result= count($rs0);
        $this->id = $rs0[1]["id"] ;
        
        return $result;
    }

}
