<?php

class CustomerOrders {

    public $StrConn = "";
    public $_CoNum = "";
    public $_CustName = "";
    public $_CustPO = "";
    
    public $CoNum = "";
    private $Query1 = "SELECT co_num FROM co_mst  ";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetProperty() {
        $cSql = new SqlSrv();
        $sql0 = $this->Query1 . "  WHERE co_num='" . $this->_CoNum . "'   ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        $this->CoNum = $rs0[1]["co_num"];
//        $this->item = $rs0[1]["item"];
//        $this->qty1 = $rs0[1]["qty1"];
    }

    function GetRowsAll() {
        $cSql = new SqlSrv();
        $sql0 = $this->Query1;
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        $this->CoNum = $rs0[1]["co_num"];
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsWithCond2() {
        $cSql = new SqlSrv();        
        $sql0 = "select co_num , cust_po , order_date ,stat ,a.cust_num as cust_num ,a.cust_seq, b.name as cust_name  ,addr##1 as addr1   "
                . "from co_mst a "
                . "left join custaddr_mst b on (a.cust_num = b.cust_num and a.cust_seq = b.cust_seq) "
                . "where CONCAT(a.cust_num , ' ',b.name) like '%".$this->_CustName."%' "
                . "and cust_po like '%".$this->_CustPO."%' "
                . "and co_num like '%".$this->_CoNum."%' ";
        
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
//        $this->CoNum = $rs0[1]["co_num"];
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsWithCond($sWhere) {
        $cSql = new SqlSrv();
        $sql0 = $this->Query1 . " WHERE $sWhere";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        $this->CoNum = $rs0[1]["co_num"];
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsAll_Ajax() {
        $cSql = new SqlSrv();
        $sql0 = $this->Query1;
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
//        $this->CoNum = $rs0[1]["co_num"];
        array_splice($rs0, count($rs0) - 1, 1);
        $arr = array();
        foreach ($rs0 as $index => $rows) {
            array_push($arr, $rows["co_num"]);
        }

        return $arr;
    }

//    function isDup() {
//        $cSql = new SqlSrv();
//        $sqlCheck = "SELECT count(id) as countrecs  FROM  Mv_Bc_Tag WHERE id='" . $this->_id . "' ";
//        $rs0 = $cSql->SqlQuery($this->StrConn, $sqlCheck);
//        if ($rs0[1]["countrecs"] > 0) {
//            return true;
//        } else {
//            return false;
//        }
//    }
}
