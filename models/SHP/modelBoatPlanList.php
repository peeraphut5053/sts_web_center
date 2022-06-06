<?php

class BoatPlanList {

    var $StrConn = "";
    public $_HeadCode = "";
    public $_itemPosition = "";
    public $_ItemTag = "";
    public $_Bundles = 0;
    public $_CoNum = "";
    public $HeadCode = "";
    public $IdRun = 0;
    public $ietmPosition = "";
     public $ItemDescription = "";
     public $ItemQty = 0;
    public $ItemTag =0;
    public $CoNum ="";
    private $Query1 = "SELECT STS_BoatPlan_List.IdRun,position, STS_BoatPlan_List.HeadCode, itemPosition, itemTag,Position,co_num  "
            . "FROM STS_BoatPlan_List " 
            . "LEFT JOIN STS_BoatPlan_Head ON STS_BoatPlan_List.HeadCode = STS_BoatPlan_Head.HeadCode " 
            . "LEFT JOIN STS_MT_ShipPosition ON STS_BoatPlan_List.itemPosition = STS_MT_ShipPosition.IdRun "  ;
//            . "LEFT JOIN Mv_Bc_Tag ON STS_BoatPlan_List.itemTag = Mv_Bc_Tag.id"  ;
    private $Query2 = "SELECT count(HeadCode) as CountRecs  FROM STS_BoatPlan_List"  ;
    private $Query3 = " SELECT co_num FROM STS_BoatPlan_List "
            . "LEFT JOIN STS_BoatPlan_Head ON STS_BoatPlan_List.HeadCode = STS_BoatPlan_Head.HeadCode "
            . "LEFT JOIN STS_MT_ShipPosition ON STS_BoatPlan_List.itemPosition = STS_MT_ShipPosition.IdRun" ;
    function setConn($c) {
        $this->StrConn = $c;
    }

    function setHeadCode($v) {
        $this->_HeadCode = $v;
    }


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
        $this->itemPosition = $rs0[1]["Position"];
        $this->itemTag = $rs0[1]["itemTag"];      
        $this->ItemDescription = $rs0[1]["item"];     
        $this->ItemQty = $rs0[1]["qty1"];      
        $this->CoNum = $rs0[1]["co_num"];      
        }
    }

    function CountRowsWithHeader($HeadCode) {
        $cSql = new SqlSrv();
        $sql0 = "SELECT count(idRun) as CountRecs FROM STS_BoatPlan_List WHERE HeadCode= '$HeadCode' ";
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
        $sql0 = "SELECT * FROM  STS_BoatPlan_List WHERE STS_BoatPlan_List.HeadCode=  '".$this->_HeadCode."' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function GetRowsCO_WithCond($sWhere ) {
        $cSql = new SqlSrv();
        $sql0 = $this->Query3 . "  WHERE $sWhere ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
function GetRowsWithCond($sWhere ) {
        $cSql = new SqlSrv();
        $sql0 = $this->Query1 . "  WHERE $sWhere ";
       // echo "<br>**Model Debug $sql0 " ;
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function insert() {
        $cSql = new SqlSrv();
        $sql = "INSERT INTO STS_BoatPlan_List (HeadCode, itemPosition ,itemTag,co_num) "
                . "VALUES ('" . $this->_HeadCode . "'," . $this->_itemPosition . ",'" . $this->_ItemTag . "','" . $this->_CoNum . "') ";
  $cSql->IsUpDel($this->StrConn, $sql);
        return $cSql;
    }

    function deleteWithHeader() {
        $cSql = new SqlSrv();
        $sql = "DELETE FROM STS_BoatPlan_List WHERE HeadCode = '" . $this->_HeadCode . "' ";
        $cSql->IsUpDel($this->StrConn, $sql);
        return $cSql;
    }

}
