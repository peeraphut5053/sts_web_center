<?php

class UserDefinedTypeValuesSyteline {
    var $StrConn = "";
    public $_item = "";
    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetUFOptionList($UF) {
        $query = "SELECT * FROM UserDefinedTypeValues WHERE TypeName  = 'ITEM_$UF'     ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "" ;
        $tempValue = "" ;
        $returnArray[""] = "" ;
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["Value"];
            $tempValue = $rows["Value"] ;
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

}
