<?php

class BoatPosition {

    Public $StrConn = "";
    public $_IdRun = 0;
    public $_Position = "";
    public $IdRun = 0;
    public $Position = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetProperty() {
        $cSql = new SqlSrv();
        $sql0 = "SELECT IdRun , Position   FROM STS_MT_ShipPosition WHERE IdRun = " . $this->_IdRun . "  ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        $this->IdRun = $rs0[1]["IdRun"];
        $this->Position = $rs0[1]["Position"];
    }

    function GetRowsWithCond($sWhere) {
        $cSql = new SqlSrv();
        $sql0 = "SELECT  IdRun , HB_Name ,HB_Status  FROM STS_MT_ShipPosition WHERE $sWhere ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function isDup() {
        $cSql = new SqlSrv();
        $sql0 = "SELECT count(IdRun) as CountRecs  FROM STS_MT_ShipPosition WHERE HB_Name = '" . $this->_HB_Name . "' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        if ($rs0[1]["CountRecs"] >= 1) {
            return 1;
        } else {
            return 0;
        }
    }

    function isDupWhenEdit($Old) {
        $cSql = new SqlSrv();
        $sql0 = "SELECT count(IdRun) as CountRecs  FROM STS_MT_ShipPosition   WHERE Position !='$Old' AND Position= '" . $this->HB_Name . "' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        if ($rs0[1]["CountRecs"] >= 1) {
            return 1;
        } else {
            return 0;
        }
    }

    function Insert() {
        $cSql = new SqlSrv();
        $sql0 = "INSERT INTO STS_MT_ShipPosition (Position) VALUES ('" . $this->Position . "')  ";
        $rs0 = $cSql->IsUpDel($this->StrConn, $sql0);
        return $rs0;
    }

    function Update() {
        $cSql = new SqlSrv();
        $sql0 = "UPDATE STS_MT_ShipPosition SET Position='" . $this->_HB_Name . "'  WHERE IdRun =" . $this->_IdRun;
        $rs0 = $cSql->IsUpDel($this->StrConn, $sql0);
        return $rs0;
    }

    function Delete() {
        $cSql = new SqlSrv();
        $sql0 = "DELETE FROM STS_MT_ShipPosition WHERE IdRun =   " . $this->_IdRun;
        $rs0 = $cSql->IsUpDel($this->StrConn, $sql0);
        return $rs0;
    }

    function Populate($sWhere, $DisPlay, $SelectedId) {
        //0 = display Name
        //1 = Display Status
        //SelectedId  = IdRun
        $cSql = new SqlSrv();
        $ToDisplay = "";
        $ToSelected = "";
        $itemStr = "";
        $sql0 = "SELECT IdRun , Position   FROM STS_MT_ShipPosition WHERE $sWhere ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        if ($DisPlay == 0) {
            $ToDisplay = "Position";
        } else if ($DisPlay == 1) {
            $ToDisplay = "IdRun";
        } else {
            return "N/A";
        }
         $itemStr = $itemStr . "<option  value='0'>..Select..</option>";
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

    function PopulateAll($DisPlay, $SelectedId) {
        //0 = display Name
        //1 = Display Status
        //SelectedId  = IdRun
        $cSql = new SqlSrv();
        $ToDisplay = "";
        $ToSelected = "";
        $itemStr = "";
        $sql0 = "SELECT IdRun , Position   FROM STS_MT_ShipPosition  ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        if ($DisPlay == 0) {
            $ToDisplay = "Position";
        } else if ($DisPlay == 1) {
            $ToDisplay = "IdRun";
        } else {
            return "N/A";
        }
        $itemStr = $itemStr . "<option  value='0'>..Select..</option>";
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
