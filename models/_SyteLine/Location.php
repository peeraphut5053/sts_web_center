<?php

class SlLocation {

    var $StrConn = "";
    public $_site_ref = "";
    public $_search = "";
    public $_loc = "";
    public $_item = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetRowsAll() {
        $loc = $this->_loc;
        $item = $this->_item;

        $query = " select itemloc_mst.item as item_code,item_mst.description as item_desc, loc,sum(qty_on_hand) qty_on_hand FROM itemloc_mst LEFT JOIN item_mst ON itemloc_mst.item = item_mst.item where 1=1 and qty_on_hand > 0 "
                . "and itemloc_mst.item like '%$item%' and itemloc_mst.loc like '%$loc%' "
                . " group by itemloc_mst.item,loc,item_mst.description order by itemloc_mst.item  ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemListInLocation() {
        
        $loc = $this->_loc;
//        $query = "select item_code,loc,"
//                . " V_WebApp_ItemLotLoc_CountItem.description,"
//                . " V_WebApp_ItemLotLoc_CountItem.loc_description,"
//                . " sum(qty_on_hand) as sum_pcs ,"
//                . " Uf_pack from  V_WebApp_ItemLotLoc_CountItem "
//                . " LEFT JOIN item_mst ON V_WebApp_ItemLotLoc_CountItem.item_code = item_mst.item "
//                . " where 1=1 "
//                . " and loc = '$loc' "
//                . " group by item_code,loc,V_WebApp_ItemLotLoc_CountItem.description,"
//                . " V_WebApp_ItemLotLoc_CountItem.loc_description,Uf_pack";
//        
        $query = "select itemloc_mst.item as item_code,loc,item_mst.description, sum(qty_on_hand) sum_pcs ,isnull(Uf_pack,0) Uf_pack FROM itemloc_mst LEFT JOIN item_mst ON itemloc_mst.item = item_mst.item where 1=1 and qty_on_hand > 0 and loc = '$loc' group by itemloc_mst.item,loc,item_mst.description,Uf_pack order by item_code ";
        

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    

    function GetItemDetail($loc, $item) {
        $query = "select item_code,loc,"
                . " V_WebApp_ItemLotLoc_CountItem.description,"
                . " V_WebApp_ItemLotLoc_CountItem.loc_description,"
                . " sum(qty_on_hand) as sum_pcs ,"
                . " Uf_pack from  V_WebApp_ItemLotLoc_CountItem "
                . " LEFT JOIN item_mst ON V_WebApp_ItemLotLoc_CountItem.item_code = item_mst.item "
                . " where 1=1 "
                . " and loc = '$loc' "
                . " and item = '$item' "
                . " group by item_code,loc,V_WebApp_ItemLotLoc_CountItem.description,"
                . " V_WebApp_ItemLotLoc_CountItem.loc_description,Uf_pack";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemDetailByStsTag($tag) {
        $query = "select * FROM V_WebApp_WH_Check_ItemLoc   "
                . "WHERE id = '$tag'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $this->GetItemDetail($rs0[0]['loc'], $rs0[0]['item']);
    }

    function GetLocationAll() {
        $loc = $this->_loc;

        $query = "select loc, V_WebApp_ItemLotLoc_CountItem.loc_description from  V_WebApp_ItemLotLoc_CountItem LEFT JOIN item_mst ON V_WebApp_ItemLotLoc_CountItem.item_code = item_mst.item where 1=1 group by loc,V_WebApp_ItemLotLoc_CountItem.loc_description  order by loc_description";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetWhAll() {
        $query = "SELECT remark_wh FROM location_mst WHERE remark_wh <> null OR remark_wh <> ''  group by remark_wh ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}

?>
