<?php

class Section {

    var $StrConn = "";
    public $_sec_id = "";
    public $sec_id = "";
    public $sec_name = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetRows($sWhere) {
        $query = "SELECT * FROM STS_Section $sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemToDropdown($sWhere) {
        $tmpKey = "";
        $tempValue = "";
        $returnArray = array();
        $query = "SELECT * FROM STS_Section $sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        foreach ($rs0 as $index => $rows) {
            
            $tmpKey = $rows["sec_id"];
            $tempValue = $rows["sec_name"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function GetProperties($sWhere) {
        $query = "SELECT * FROM STS_Section $sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            $this->sec_id = $rs0[1]["sec_id"];
            $this->sec_name = $rs0[1]["sec_name"];
        }
    }

}
