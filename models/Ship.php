<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ship
 *
 * @author webcenter
 */
class Ship {

    var $StrConn = "";
    public $_site_ref = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetRows($sWhere) {
        $query = "SELECT *  FROM STS_MT_Ship $sWhere ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0 ;
    }

    function GetItemToDropdown($sWhere) {
        $query = "SELECT * FROM STS_MT_Ship  $sWhere ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "" ;
        $tempValue = "" ;
        $returnArray[""] = "" ;
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["IdRun"];
            $tempValue = $rows["Ship_MV"] . " - " . $rows["Ship_LighterNo"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

}
