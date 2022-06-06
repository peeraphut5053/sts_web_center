<?php

class STS_Sys_Config {
    var $StrConn = "";    
    public $SO_Approve_Level_OverOrEqual = 0;
    public $IsAdmin_Level_OverOrEqual = 0;
    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetProperties() {
        $query = "SELECT * FROM STS_Sys_Config ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        $this->SO_Approve_Level_OverOrEqual = $rs0[1]["SO_Approve_Level_OverOrEqual"];
        $this->IsAdmin_Level_OverOrEqual = $rs0[1]["IsAdmin_Level_OverOrEqual"];
    }
}
