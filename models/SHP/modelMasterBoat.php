<?php

class Boat {

    Public $StrConn = "";
    public $_IdRun = 0;
    public $_Ship_MV = "";
    public $_Ship_LighterNo = "";
    public $IdRun = 0;
    public $Ship_MV = "";
    public $Ship_LighterNo = "";
    private $table = "STS_MT_Ship";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetProperty() {
        $cSql = new SqlSrv();
        $sql0 = "SELECT IdRun , Ship_MV,Ship_LighterNo  FROM " . $this->table . "  WHERE IdRun = " . $this->_IdRun . "  ";

        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);

        $this->IdRun = $rs0[1]["IdRun"];
        $this->Ship_MV = $rs0[1]["Ship_MV"];
        $this->Ship_LighterNo = $rs0[1]["Ship_LighterNo"];
    }

    function GetRowsWithCond($sWhere) {
        $cSql = new SqlSrv();
        $sql0 = "SELECT IdRun , Ship_MV,Ship_LighterNo  FROM " . $this->table . "  WHERE $sWhere ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function isDup() {
        $cSql = new SqlSrv();
        $sql0 = "SELECT count(IdRun) as CountRecs  FROM " . $this->table . "  WHERE Ship_LighterNo = '" . $this->_Ship_LighterNo . "' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        if ($rs0[1]["CountRecs"] >= 1) {
            return 1;
        } else {
            return 0;
        }
    }

    function isDupWhenEdit($Old) {
        $cSql = new SqlSrv();
        $sql0 = "SELECT count(IdRun) as CountRecs  FROM " . $this->table . "  WHERE Ship_LighterNo !='$Old' AND Ship_LighterNo= '" . $this->_Ship_LighterNo . "' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        if ($rs0[1]["CountRecs"] >= 1) {
            return 1;
        } else {
            return 0;
        }
    }

    function Insert() {
        $cSql = new SqlSrv();
        $sql0 = "INSERT INTO " . $this->table . " (Ship_MV,Ship_LighterNo) VALUES ('" . $this->_Ship_MV . "','" . $this->_Ship_LighterNo . "')  ";
        $rs0 = $cSql->IsUpDel($this->StrConn, $sql0);
        return $rs0;
    }

    function Update() {
        $cSql = new SqlSrv();
        $sql0 = "UPDATE " . $this->table . " SET Ship_MV='" . $this->_Ship_MV . "' ,Ship_LighterNo ='" . $this->_Ship_LighterNo . "' WHERE IdRun =" . $this->_IdRun;
        $rs0 = $cSql->IsUpDel($this->StrConn, $sql0);
        return $rs0;
    }

    function Delete() {
        $cSql = new SqlSrv();
        $sql0 = "DELETE FROM " . $this->table . " WHERE IdRun =   " . $this->_IdRun;
        $rs0 = $cSql->IsUpDel($this->StrConn, $sql0);
        return $rs0;
    }

    function PopulateAll($DisPlay, $SelectedId) {
        //0 = display MV
        //1 = Display LighterNo
        //SelectedId  = IdRun
        $cSql = new SqlSrv();
        $ToDisplay = "";
        $ToSelected = "";
        $itemStr = "";
        $sql0 = "SELECT IdRun , Ship_MV,Ship_LighterNo  FROM " . $this->table . "  ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
//     $itemStr = $itemStr . "<option  value='0'>..Select..</option>";
        if ($DisPlay == 0) {
            $ToDisplay = "Ship_MV";
        } else if ($DisPlay == 1) {
            $ToDisplay = "Ship_LighterNo";
        } else {
            return "N/A";
        }
//        $itemStr = $itemStr . "<option  value='0'>..Select..</option>";
        foreach ($rs0 as $index => $rows) {
            if ($SelectedId == $rows["IdRun"]) {
                $ToSelected = "selected";
            } else {
                $ToSelected = "";
            }
            $itemStr = $itemStr . "<option $ToSelected value='" . $rows["IdRun"] . "'>" . $rows[$ToDisplay] . "</option>";
            // $itemStr = $itemStr . "  $ToSelected value='".$rows["IdRun"]." ".$rows[$ToDisplay]." " ;
        }
        return $itemStr;
    }

}
