<?php

class BoatNoteList {

    var $StrConn = "";
    public $_HeadCode = "";
    public $_LotNo = "";
    public $_ShipTo = 0;
    public $_Bundles = 0;
    public $_Description = "";
    public $_GrossWeight = 0;
    public $_UseTable = "STS_BoatNote_List";
    public $HeadCode = "";
    public $IdRun = 0;
    public $LotNo = "";
    public $ShipTo =0;
    public $Bundles = 0;
    public $Description = "";
    public $GrossWeight = 0;
    private $Query1 = "SELECT STS_BoatNote_List.IdRun, HeadCode, LotNo, ShipTo, Bundles, Description, GrossWeight,IsNull(HB_Name,'N/A') AS HB_Name "
            . "FROM STS_BoatNote_List "
            . "LEFT JOIN STS_MT_Harbor ON STS_BoatNote_List.ShipTo =STS_MT_Harbor.IdRun"  ;
    private $Query2 = "SELECT count(HeadCode) as CountRecs  FROM STS_BoatNote_List"  ;
    function setConn($c) {
        $this->StrConn = $c;
    }

    function setHeadCode($v) {
        $this->_HeadCode = $v;
    }

//    function getRowsOne() {
//        $cSql = new SqlSrv();
//        $sql0 = "SELECT count(HeadCode) as " . $this->_UseTable . " FROM " . $this->_UseTable . "  ";
//        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
//        return $rs0[1]["CountRecs"];
//    }

    function CountRowsAll() {
        $cSql = new SqlSrv();
        $sql0 = $this->Query2 ;
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        return $rs0[1]["CountRecs"];
    }

    function GetPropertyWithCond($sWhere) {
        $cSql = new SqlSrv();
        $sql0 = $this->Query1. "  WHERE $sWhere ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        if(count($rs0)>=1){
        $this->HeadCode = $rs0[1]["HeadCode"];
        $this->IdRun = $rs0[1]["IdRun"];
        $this->LotNo = $rs0[1]["LotNo"];
        $this->ShipTo = $rs0[1]["ShipTo"];
        $this->Bundles = $rs0[1]["Bundles"];
        $this->Description = $rs0[1]["Description"];
        $this->GrossWeight = $rs0[1]["GrossWeight"];
        }
//        array_splice($rs0, count($rs0) - 1, 1);
//        return $rs0;
    }

    function CountRowsWithHeader($HeadCode) {
        $cSql = new SqlSrv();
        $sql0 = $this->Query2. "  WHERE HeadCode= '$HeadCode' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        return $rs0[1]["CountRecs"];
    }

    function GetRowsAll() {
        $cSql = new SqlSrv();
        $sql0 =$this->Query1;
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsWithHeader() {
        $cSql = new SqlSrv();
        $sql0 = $this->Query1 . "  WHERE HeadCode=  '".$this->_HeadCode."' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function insert() {
        $cSql = new SqlSrv();
        $sql = "INSERT INTO " . $this->_UseTable . " (HeadCode, LotNo ,ShipTo,Bundles,Description ,GrossWeight) "
                . "VALUES ('" . $this->_HeadCode . "','" . $this->_LotNo . "'," . $this->_ShipTo . ",'" . $this->_Bundles . "','" . $this->_Description . "','" . $this->_GrossWeight . "') ";
//        $sql2 = "INSERT INTO " . $this->_UseTable . "_Copy (HeadCode, LotNo ,ShipTo,Bundles,Description ,GrossWeight) "
//                . "VALUES ('" . $this->_HeadCode . "','" . $this->_LotNo . "'," . $this->_ShipTo . ",'" . $this->_Bundles . "','" . $this->_Description . "','" . $this->_GrossWeight . "') ";
        $cSql->IsUpDel($this->StrConn, $sql);
//        $cSql->IsUpDel($this->StrConn, $sql2);
        return $cSql;
    }

    function deleteWithHeader() {
        $cSql = new SqlSrv();
        $sql = "DELETE FROM " . $this->_UseTable . " WHERE HeadCode = '" . $this->_HeadCode . "' ";
        $cSql->IsUpDel($this->StrConn, $sql);
        return $cSql;
    }

}
