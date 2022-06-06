<?php

class BoatPlanHead {

    var $StrConn = "";
    public $_HeadCode = "";
    public $_StartDate = "";
    public $_EndDate = "";
    public $_Boat_Id = "";
    public $_Berthed_Id = "";
    public $_Cancel = 0;
    public $HeadCode = "";
    public $Boat_Id = "";
    public $Ship_MV = "";
    public $Ship_LighterNo = "";
    public $Berthed_Id = "";
    public $HB_Name = "";
    public $StartDate = "";
    public $EndDate = "";
    public $Cancel = 0;
    private $Query1 = "SELECT STS_BoatPlan_Head.HeadId, HeadCode,Boat_Id, Berthed_Id , 
            IsNull(Ship_MV,'N/A') AS Ship_MV,
            IsNull(Ship_LighterNo,'N/A') AS Ship_LighterNo ,
            IsNull(HB_Name,'N/A') AS HB_Name , 
            Convert(nvarchar(30),StartDate) as StartDate ,
            Convert(nvarchar(30),EndDate) AS EndDate
            ,Cancel
             FROM STS_BoatPlan_Head
             LEFT JOIN STS_MT_Ship ON (STS_BoatPlan_Head.Boat_Id = STS_MT_Ship.IdRun)
             LEFT JOIN STS_MT_Harbor ON (STS_BoatPlan_Head.Berthed_Id = STS_MT_Harbor.IdRun )  ";
    private $QueryCount = 'SELECT count(HeadCode) as CountRecs FROM STS_BoatPlan_Head
            LEFT JOIN STS_MT_Ship ON (STS_BoatPlan_Head.Boat_Id = STS_MT_Ship.IdRun)
            LEFT JOIN STS_MT_Harbor ON (STS_BoatPlan_Head.Berthed_Id = STS_MT_Harbor.IdRun )';

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetProperty() {

        $cSql = new SqlSrv();
        $sql0 = $this->Query1 . "  WHERE Cancel=" . $this->_Cancel . " AND  HeadCode = '" . $this->_HeadCode . "'   ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        $tDate1 = new DateTime($rs0[1]["StartDate"]);
//        $tDate2 = new DateTime($rs0[1]["EndDate"]);
        $StartDate = $tDate1->format('Y-m-d H:i:s');
//        $EndDate = $tDate2->format('Y-m-d H:i:s');
        $this->HeadCode = $rs0[1]["HeadCode"];
        $this->Boat_Id = $rs0[1]["Boat_Id"];
        $this->Ship_MV = $rs0[1]["Ship_MV"];
        $this->Ship_LighterNo = $rs0[1]["Ship_LighterNo"];
        $this->Berthed_Id = $rs0[1]["Berthed_Id"];
        $this->HB_Name = $rs0[1]["HB_Name"];
        $this->StartDate = $StartDate;
//        $this->EndDate = $EndDate;
        $this->Cancel = $rs0[1]["Cancel"];
    }

    function CountRows() {
        $cSql = new SqlSrv();
        $sql0 = $this->QueryCount . "  WHERE Cancel=" . $this->_Cancel;
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        return $rs0[1]["CountRecs"];
    }

    function CountRowsWithCond($sWhere) {
        $cSql = new SqlSrv();
        $sql0 = $this->QueryCount . "  WHERE Cancel=" . $this->_Cancel . " AND  $sWhere ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        return $rs0[1]["CountRecs"];
    }

    function CountRowsWithHeader() {
        $cSql = new SqlSrv();
        $sql0 = "Select count(idRun) as CountRecs FROM STS_BoatPlan_List  WHERE HeadCode = '" . $this->_HeadCode . "' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        return $rs0[1]["CountRecs"];
    }

    function GetRowsAll() {
        $cSql = new SqlSrv();
        $sql0 = $Query1 . "  WHERE Cancel=" . $this->_Cancel . " ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsWithCond($sWhere) {
        $cSql = new SqlSrv();
        $sql = "SELECT * FROM STS_BoatPlan_Head WHERE 1=1 $sWhere";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GenNewHeadCode() {
        //================Gen Head Code ===============//
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 HeadCode FROM  STS_BoatPlan_Head ORDER BY HeadCode DESC  ";
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        $tmpDocNo = "";
        $arrDocNo = array();
        $CurrDate = date("Ymd");
        $val = "";
        if (count($rs) == 1) {
            return $CurrDate . "_0001";
        } else {
            $tmpDocNo = $rs[1]["HeadCode"];
            $arrDocNo = explode("_", $tmpDocNo);
            if ($CurrDate == $arrDocNo[0]) {
                $val = str_pad(strval($arrDocNo[1]) + 1, 4, '0', STR_PAD_LEFT);
                return $CurrDate . "_" . $val;
            } else {
                return $CurrDate . "_0001";
            }
        }
//================Gen Head Code ===============//
    }

    function isDup() {
        $cSql = new SqlSrv();
        $sqlCheck = "SELECT count(HeadCode) as CountHeadCode  FROM  STS_BoatPlan_Head WHERE Cancel=" . $this->_Cancel . " AND HeadCode = '" . $this->_HeadCode . "' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sqlCheck);
        if ($rs0[1]["CountHeadCode"] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function updateStatus() {
        $cSql = new SqlSrv();
//        $ChangeCancel = 0 ;
//        $this->_Cancel == 0 ? $ChangeCancel =1 : $ChangeCancel =0;
        $sql = "UPDATE  STS_BoatPlan_Head SET "
                . "Cancel=  " . $this->_Cancel
                . "  WHERE HeadCode = '" . $this->_HeadCode . "' ";
        $cSql->IsUpDel($this->StrConn, $sql);
        return $cSql;
    }

    function update() {
        $cSql = new SqlSrv();
        $sql = "";
        $sql2 = "";
        $isDup = $this->isDup();
        if ($isDup == false) {
            $sql = "INSERT INTO STS_BoatPlan_Head  (HeadCode, Boat_Id ,Berthed_Id,StartDate ,EndDate,Cancel ) "
                    . "VALUES ('" . $this->_HeadCode . "'," . $this->_Boat_Id . "," . $this->_Berthed_Id . ",'" . $this->_StartDate . "','" . $this->_EndDate . "',0) ";
            $cSql->IsUpDel($this->StrConn, $sql);
        } else {
            $sql = "UPDATE  STS_BoatPlan_Head SET "
                    . "Boat_id=" . $this->_Boat_Id . " ,"
                    . "Berthed_id=" . $this->_Berthed_Id . ","
                    . "StartDate ='" . $this->_StartDate . "',"
                    . "EndDate ='" . $this->_EndDate . "', "
                    . "Cancel= " . $this->_Cancel . " "
                    . "WHERE HeadCode = '" . $this->_HeadCode . "' ";
            $cSql->IsUpDel($this->StrConn, $sql);
        }
        return $cSql;
    }

    function delete() {
        $cSql = new SqlSrv();
        $sql = "DELETE FROM  STS_BoatPlan_Head WHERE  HeadCode = '" . $this->_HeadCode . "' ";
        $cSql->IsUpDel($this->StrConn, $sql);
        return $cSql;
    }

}
