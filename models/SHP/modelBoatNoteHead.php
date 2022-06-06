<?php

class BoatNoteHead {

    var $StrConn = "";
    public $_HeadCode = "";
    public $_MV = "";
    public $_Boat_Id = "";
    public $_Berthed_Id = "";
    public $_UseTable = "STS_BoatNote_Head";
    public $_CommencedDate = "";
    public $_CompleteDate = "";
    public $_Cancel = 0;
    public $HeadCode = "";
    public $Boat_Id = "";
    public $Ship_MV = "";
    public $Ship_LighterNo = "";
    public $Berthed_Id = "";
    public $HB_Name = "";
    public $CommencedDate = "";
    public $CompleteDate = "";
    public $Cancel = 0;
    private $Query1 = "SELECT HeadId, HeadCode,Boat_Id, Berthed_Id , IsNull(Ship_MV,'N/A') AS Ship_MV,IsNull(Ship_LighterNo,'N/A') AS Ship_LighterNo ,IsNull(HB_Name,'N/A') AS HB_Name , Convert(nvarchar(30),CommenceDate) as CMDate ,Convert(nvarchar(30),CompleteDate) AS CPDate,Cancel
             FROM STS_BoatNote_Head
             LEFT JOIN STS_MT_Ship ON (STS_BoatNote_Head.Boat_Id = STS_MT_Ship.IdRun)
             LEFT JOIN STS_MT_Harbor ON (STS_BoatNote_Head.Berthed_Id = STS_MT_Harbor.IdRun )  ";
    private $QueryCount = 'SELECT count(HeadCode) as CountRecs FROM STS_BoatNote_Head
            LEFT JOIN STS_MT_Ship ON (STS_BoatNote_Head.Boat_Id = STS_MT_Ship.IdRun)
            LEFT JOIN STS_MT_Harbor ON (STS_BoatNote_Head.Berthed_Id = STS_MT_Harbor.IdRun )';

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetProperty() {

        $cSql = new SqlSrv();
        $sql0 = $this->Query1 . "  WHERE Cancel=" . $this->_Cancel . " AND  HeadCode = '" . $this->_HeadCode . "'   ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        $tDate1 = new DateTime($rs0[1]["CMDate"]);
        $tDate2 = new DateTime($rs0[1]["CPDate"]);
        $CommenceDate = $tDate1->format('Y-m-d H:i:s');
        $CompleteDate = $tDate2->format('Y-m-d H:i:s');
        $this->HeadCode = $rs0[1]["HeadCode"];
        $this->Boat_Id = $rs0[1]["Boat_Id"];
        $this->Ship_MV = $rs0[1]["Ship_MV"];
        $this->Ship_LighterNo = $rs0[1]["Ship_LighterNo"];
        $this->Berthed_Id = $rs0[1]["Berthed_Id"];
        $this->HB_Name = $rs0[1]["HB_Name"];
        $this->CommencedDate = $CommenceDate;
        $this->CompleteDate = $CompleteDate;
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

    function GetRowsAll() {
        $cSql = new SqlSrv();
        $sql0 = $Query1 . "  WHERE Cancel=" . $this->_Cancel . " ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsWithCond($sWhere) {
        $cSql = new SqlSrv();
        $sql0 = $this->Query1 . " WHERE  Cancel=" . $this->_Cancel . " AND $sWhere ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsWithCondAndLimit($sWhere) {
        $cSql = new SqlSrv();
        $sql = "WITH QR_HEAD AS ( SELECT  ROW_NUMBER() OVER (ORDER BY HeadCode) AS [Row_No], * FROM  " . $this->_UseTable . "  WHERE  ( Cancel=" . $this->_Cancel . " ) ) "
                . " SELECT HeadId , HeadCode, Boat_Id,Berthed_Id ,
                    NULLIF(Ship_MV,'N/A') AS Ship_MV,
                    NULLIF(Ship_LighterNo,'N/A') AS Ship_LighterNo,
                    NULLIF(HB_Name,'N/A') AS  HB_Name,
                    CONVERT(nvarchar, CommenceDate) as CmDate,
                    CONVERT(nvarchar,CompleteDate) AS CpDate
                FROM QR_HEAD
                LEFT JOIN STS_MT_Ship ON QR_HEAD.Boat_Id = STS_MT_Ship.IdRun
                LEFT JOIN STS_MT_Harbor ON QR_HEAD.Berthed_Id = STS_MT_Harbor.IdRun"
                . " WHERE $sWhere  ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs0, count($rs0) - 1, 1);

        return $rs0;
    }

    function GenNewHeadCode() {
        //================Gen Head Code ===============//
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 HeadCode FROM  " . $this->_UseTable . " ORDER BY HeadCode DESC  ";
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        $tmpDocNo = "";
        $arrDocNo = array();
        $CurrDate = date("Ymd");
        $val = "";
        if (count($rs) == 0) {
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
        $sqlCheck = "SELECT count(HeadCode) as CountHeadCode  FROM  " . $this->_UseTable . " WHERE Cancel=" . $this->_Cancel . " AND HeadCode = '" . $this->_HeadCode . "' ";
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
        $sql = "UPDATE  " . $this->_UseTable . " SET "
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
            $sql = "INSERT INTO STS_BoatNote_Head  (HeadCode, Boat_Id ,Berthed_Id,CommenceDate ,CompleteDate,Cancel ) "
                    . "VALUES ('" . $this->_HeadCode . "'," . $this->_Boat_Id . "," . $this->_Berthed_Id . ",'" . $this->_CommencedDate . "','" . $this->_CompleteDate . "',0) ";
//            $sql2 = "INSERT INTO STS_BoatNote_Head_copy  (HeadCode, MV ,LighterNo,BerthedAt,CommenceDate ,CompleteDate ) "
//                    . "VALUES ('" . $this->_HeadCode . "','" . $this->_MV . "','" . $this->_Berthed_Id . "','" . $this->_CommencedDate . "','" . $this->_CompleteDate . "') ";

            $cSql->IsUpDel($this->StrConn, $sql);
//            $cSql->IsUpDel($this->StrConn, $sql2);
        } else {
            $sql = "UPDATE  " . $this->_UseTable . " SET "
                    . "Boat_id=" . $this->_Boat_Id . " ,"
                    . "Berthed_id=" . $this->_Berthed_Id . ","
                    . "CommenceDate ='" . $this->_CommencedDate . "',"
                    . "CompleteDate ='" . $this->_CompleteDate . "', "
                    . "Cancel= " . $this->_Cancel . " "
                    . "WHERE HeadCode = '" . $this->_HeadCode . "' ";
            $cSql->IsUpDel($this->StrConn, $sql);
        }
        return $cSql;
    }

    function delete() {
        $cSql = new SqlSrv();
        $sql = "DELETE FROM  " . $this->_UseTable . " WHERE  HeadCode = '" . $this->_HeadCode . "' ";
        $cSql->IsUpDel($this->StrConn, $sql);
        return $cSql;
    }

}
