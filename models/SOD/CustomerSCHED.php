<?php

class CustomerSCHED{

    var $StrConn = "";
    public $id = "";
    public $sched = "";
    public $cust_num = "";
    public $_id = "";
    public $_sched = "";
    public $_cust_num = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetItemToDropdownByCustNum() {
//        session_start();
        $CustNum = $this->_cust_num;
        $query = "SELECT * FROM SO_CustomerSCHED WHERE cust_num ='$CustNum' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "";
        $tempValue = "";
        $returnArray[""] = "";
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["sched"];
            $tempValue = $rows["sched"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function isDup() {
        $CustNum = $this->_cust_num;
        $sched = $this->_sched;
        $query = "SELECT * FROM SO_CustomerSCHED WHERE cust_num ='$CustNum' AND sched = '$sched' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        return count($rs0) ;
    }

    function Insert() {
        $CustNum = $this->_cust_num;
        $sched = $this->_sched;
        $query = "Insert Into SO_CustomerSCHED (cust_num,sched) VALUES ('$CustNum','$sched')  ";
        $cSql = new SqlSrv();
        $result = $cSql->IsUpDel($this->StrConn, $query);
        return $result;
    }

    function GetProperties() {
        $CustNum = $this->_cust_num;
        $sched = $this->_sched;
        $query = "SELECT id FROM SO_CustomerSCHED  WHERE cust_num ='$CustNum' AND sched = '$sched'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);   
        $result= count($rs0);
        $this->id = $rs0[1]["id"] ;        
        return $result;
    }

}
