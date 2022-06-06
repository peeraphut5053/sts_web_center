<?php

class CustomerNPS{

    var $StrConn = "";
    public $id = "";
    public $nps = "";
    public $cust_num = "";
    public $_id = "";
    public $_nps = "";
    public $_cust_num = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetItemToDropdownByCustNum() {
//        session_start();
        $CustNum = $this->_cust_num;
        $query = "SELECT * FROM SO_CustomerNPS WHERE cust_num ='$CustNum' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "";
        $tempValue = "";
        $returnArray[""] = "";
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["nps"];
            $tempValue = $rows["nps"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function isDup() {
        $CustNum = $this->_cust_num;
        $nps = $this->_nps;
        $query = "SELECT * FROM SO_CustomerNPS WHERE cust_num ='$CustNum' AND nps = '$nps' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        return count($rs0) ;
    }

    function Insert() {
        $CustNum = $this->_cust_num;
        $nps = $this->_nps;
        $query = "Insert Into SO_CustomerNPS (cust_num,nps) VALUES ('$CustNum','$nps')  ";
        $cSql = new SqlSrv();
        $result = $cSql->IsUpDel($this->StrConn, $query);
        return $result;
    }

    function GetProperties() {
        $CustNum = $this->_cust_num;
        $nps = $this->_nps;
        $query = "SELECT id FROM SO_CustomerNPS  WHERE cust_num ='$CustNum' AND nps = '$nps'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);   
        $result= count($rs0);
        $this->id = $rs0[1]["id"] ;        
        return $result;
    }

}
