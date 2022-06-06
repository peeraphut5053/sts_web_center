<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ItemCategory
 *
 * @author webcenter
 */
class ItemCategory {

    var $StrConn = "";
    public $_item = "";

    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetItemToDropdown($sWhere) {
        $query = "SELECT * FROM item_category_mst  $sWhere ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "" ;
        $tempValue = "" ;
        $returnArray[""] = "" ;
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["item_category"];
            $tempValue = $rows["item_category"] . " - " . $rows["description"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }
}
