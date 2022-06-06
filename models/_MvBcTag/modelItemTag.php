<?php

class Item {

    public $StrConn = "";
    public $_id = "";
    public $_item = "";
    public $_qty1 = "";
    public $id = "";
    public $item = "";
    public $qty1 = "";
    private $Query1 = "SELECT id,item ,qty1 FROM Mv_Bc_Tag  ";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetProperty() {
        $ebits = ini_get('error_reporting');
        error_reporting($ebits ^ E_NOTICE);
        $cSql = new SqlSrv();
        $sql0 = $this->Query1 . "  WHERE id='" . $this->_id . "'   ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        if ($rs0[1]) {
            $this->id = $rs0[1]["id"];
            $this->item = $rs0[1]["item"];
            $this->qty1 = $rs0[1]["qty1"];
        }
    }

    function GetRowsWithCond($sWhere) {
        $cSql = new SqlSrv();
        $sql0 = $this->Query1 . "  WHERE $sWhere  ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function isDup() {
        $cSql = new SqlSrv();
        $sqlCheck = "SELECT count(id) as countrecs  FROM  Mv_Bc_Tag WHERE id='" . $this->_id . "' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sqlCheck);
        return $rs0;
//        if ($rs0[1]["countrecs"] > 0) {
//            return true;
//        } else {
//            return false;
//        }
    }

}
