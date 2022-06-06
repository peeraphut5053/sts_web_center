<?php
//===================================================//
//===================================================//
//===================================================//
//=============== ItemSyteline.php      =============//
//===================================================//
//===================================================//
//===================================================//

// t-sql for get column name
//
// declare @aa varchar (MAX)
// set @aa = ''
//
// select @aa =
//     case when @aa = ''
//     then COLUMN_NAME
//     else @aa + coalesce(',' + COLUMN_NAME, '')
//     end
//   from Train20_AppDemo.INFORMATION_SCHEMA.COLUMNS
// WHERE TABLE_NAME = N'item_mst'
// print @aa


class ItemSaleOrder {
    var $StrConn = "";
    public $_site_ref = "";

    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetRows($sWhere) {
        $query = "SELECT * FROM SO_Order_detail $sWhere " ;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function GetItemToDropdown($sWhere) {
        $query = "SELECT * FROM item_mst  $sWhere " ;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "" ;
        $tempValue = "" ;
        $returnArray[""] = "" ;
        foreach ($rs0 as $index => $rows){
          $tmpKey = $rows["item"] ;
          $tempValue =  $rows["item"] ;
          $returnArray[$tmpKey] = $tempValue ;
        }
        return $returnArray;
    }

}
