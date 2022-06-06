<?php
class Customer {
    var $StrConn = "";
    public $_site_ref = "";
    public $_criteria = "";
     public $_criteria2 = "";
    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetRows($sWhere) {
        $query = "SELECT * FROM customer_mst $sWhere " ;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsAddr() {
        $query = "select cust_num ,name as cust_name,addr##1 as addr1 ,addr##2 as addr2 ,addr##3 as addr3    "
                . " from custaddr_mst "
                . " where cust_seq = 0  "
                . " AND  concat(cust_num ,name ,addr##1  ,addr##2  ,addr##3 ) like '%".$this->_criteria."%'   ".$this->_criteria2 ;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
