<?php

class CountStock {

    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetCountStockList($loc,$id_hdr) {
        $query = "Select Convert(nvarchar,create_date,120) as conv_create_date,"
                . " Convert(nvarchar,approve_date,120) as conv_approve_date,"
                . " * FROM MV_countstock_hdr where 1=1 ";
        
        $query = $query . " and location like '%$loc%' ";
        $query = $query . " and id_hdr like '%$id_hdr%' ";
        $query = $query . " order by approve_status asc,approve_date desc ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemCodeByStsNo($item_code) {
        $query = "select item FROM V_WebApp_WH_Check_ItemLoc   "
                . "WHERE id = '$item_code'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0[0]["item"];
    }

    function GetCountStockDetail($loc, $item_code, $id_hdr) {
        $query_item_code = "";
        $query_loc = "";
        if ($item_code != "") {
            if (strlen($item_code) == 13) {
                $item_code = $this->GetItemCodeByStsNo($item_code);
            }
            //if item_code length = 10 then query in location by sts tag no and get item_code to them
            $query_item_code = "and item_code = '$item_code' ";
        }
        if ($loc != "") {
            $query_loc = "and location = '$loc' ";
        }
        $query = "Select Convert(nvarchar,create_date,120) as conv_create_date,MV_countstock_detail.*,item_mst.description FROM MV_countstock_detail LEFT JOIN item_mst ON MV_countstock_detail.item_code = item_mst.item where 1=1  and id_hdr = '$id_hdr' ";

        $query = $query . $query_item_code;
        $query = $query . $query_loc;

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function CreateCountStockList($loc, $Uf_pack, $sum_pcs, $item_code, $qty_bundle, $qty_pcs) {
        $result = "";
        $result2 = "";
        $id_hdr = $this->genLastIdhdr($loc);

        $query = "INSERT INTO MV_countstock_hdr("
                . "id_hdr, create_date, approve_date, approve_status,"
                . "create_by, location"
                . ")VALUES(?,?,?,?,?,?) ";
        $params = array(
            $id_hdr, date('Y-m-d H:i:s'), "", "0", "user", $loc
        );

        $stmt = sqlsrv_prepare($this->StrConn, $query, $params);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
        } else {
            $result = "1";
        }

        for ($i = 0; $i < count($item_code); $i++) {
            $query2 = "INSERT INTO MV_countstock_detail("
                    . "id_detail, id_hdr, item_code,qty_bundle,qty_count_bundle, sum_qty_pcs,sum_qty_count_pcs,qty_pcs,qty_count_pcs,"
                    . "uf_pack, remark,create_date,approve_status,location"
                    . ")VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
            $params2 = array(
                "", $id_hdr, $item_code[$i], $qty_bundle[$i], 0, $sum_pcs[$i], 0, $qty_pcs[$i], 0, $Uf_pack[$i], "", date('Y-m-d H:i:s'), 0, $loc
            );
            $stmt2 = sqlsrv_prepare($this->StrConn, $query2, $params2);
            if (sqlsrv_execute($stmt2) == false) {
                $result = sqlsrv_errors();
            } else {
                $result = $id_hdr;
            }
        }
        

        return $result;
    }

    function UpdateCountStockList($id_hdr, $item_code, $sum_qty_count_pcs, $qty_count_bundle, $qty_count_pcs, $remark) {
        $query = "update MV_countstock_detail set sum_qty_count_pcs='$sum_qty_count_pcs',qty_count_bundle = '$qty_count_bundle',qty_count_pcs='$qty_count_pcs',remark='$remark',create_date=GETDATE() where item_code= '$item_code' and id_hdr = '$id_hdr' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $query);
        return $query;
    }

    function chkStockApprove($loc) {
        $query = "Select * FROM MV_countstock_hdr where location = '$loc' and approve_status = '0' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        if ($rs0) {
            $rs0 = $rs0[0]["id_hdr"];
        } else {
            $rs0 = 0;
        }
        return $rs0;
    }

    function genLastIdhdr($loc) {
        $query = "Select * FROM MV_countstock_hdr where id_hdr like '%$loc%' order by id_hdr desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        if ($rs0) {
            $rs0 = explode('_', $rs0[0]["id_hdr"]);
            $number = $rs0[1] + 1;
            $rs0 = $rs0[0] . "_" . $number;
        } else {
            $rs0 = date("Ymd") . $loc . "_1";
        }
        return $rs0;
    }

    function confirmCheckStock($id_hdr) {
        $query2 = "update MV_countstock_hdr set approve_status='1', approve_date = GETDATE() where id_hdr = '$id_hdr' ";
        $cSql2 = new SqlSrv();
        $rs2 = $cSql2->IsUpDel($this->StrConn, $query2);

        $query = "update MV_countstock_detail set approve_status='1' where id_hdr = '$id_hdr' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $query);
        return $query;
    }

    function CheckStock($location, $item, $qty, $remark, $user) {
        // check item exist or not if exist return true else return false
        $query = "select itm.item, itm.[description], itm.u_m 
 , loc = isnull(lot.loc, loc.loc), lot = isnull(lot.lot,'0'), qty_on_hand = coalesce(lot.qty_on_hand, loc.qty_on_hand, whse.qty_on_hand)
from item_mst itm 
left join lot_loc_mst lot on lot.item = itm.item and lot.loc = '$location'
left join itemloc_mst loc on loc.item = itm.item and loc.loc = '$location'
left join itemwhse_mst whse on whse.item = itm.item 
where itm.item = '$item'";
        $cSql2 = new SqlSrv();
        $rs = $cSql2->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);

   
       
        if ((float) $rs[0]["lot"] != 0) {
            return 2;
        }

        $i = false;
        if ($rs) {
            // $rs[0]["qty_on_hand"]; covert to number 
            $qty_on_hand = (float) $rs[0]["qty_on_hand"];
            $tag = $rs[0]["lot"];
        
            $query2 = "INSERT INTO STS_countstock (tag,location, item, qty, remark, [user], qty_stock) OUTPUT inserted.* VALUES ('$tag','$location', '$item', '$qty', '$remark', '$user' , '$qty_on_hand')";
            $cSql2 = new SqlSrv();
            $rs2 = $cSql2->SqlQuery($this->StrConn, $query2);
            array_splice($rs2, count($rs2) - 1, 1);
            $i = $rs2;
        } else {
            $i = false;
        }
            
        return $i;
    }

    function CheckStockLot($location, $tag, $qty, $remark, $user) {
        // check item exist or not if exist return true else return false
        $query = "select itm.item, itm.[description], itm.u_m 
 , loc = isnull(lot.loc, loc.loc), lot = isnull(lot.lot,'0'), qty_on_hand = coalesce(lot.qty_on_hand, loc.qty_on_hand, whse.qty_on_hand)
from item_mst itm 
left join lot_loc_mst lot on lot.item = itm.item and lot.loc = '$location'
left join itemloc_mst loc on loc.item = itm.item and loc.loc = '$location'
left join itemwhse_mst whse on whse.item = itm.item 
where lot.lot = '$tag'";
        $cSql2 = new SqlSrv();
        $rs = $cSql2->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        $date = date("Y-m-d");
        $q = "select * from STS_countstock where tag = '$tag' and location = '$location' and create_date = '$date' and item = '{$rs[0]["item"]}'";
        $cSql = new SqlSrv();
        $c = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($c, count($c) - 1, 1);
        // $c count == 1 return false

        if (count($c) == 1) {
            return 2;
        }
        
        
        $i = false;
        if ($rs) {
            // $rs[0]["qty_on_hand"]; covert to number 
            $qty_on_hand = (float) $rs[0]["qty_on_hand"];
            $item = $rs[0]["item"];
        
            $query2 = "INSERT INTO STS_countstock (tag,location, item, qty, remark, [user], qty_stock) OUTPUT inserted.* VALUES ('$tag','$location', '$item', '$qty', '$remark', '$user' , '$qty_on_hand')";
            $cSql2 = new SqlSrv();
            $rs2 = $cSql2->SqlQuery($this->StrConn, $query2);
            array_splice($rs2, count($rs2) - 1, 1);
            $i = $rs2;
        } else {
            $i = false;
        }
            
        return $i;
    }

      function GetLocationItem() {
        $query = "select distinct loc from itemloc_mst";
        $cSql2 = new SqlSrv();
        $rs = $cSql2->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
      function GetListItem() {
        $query = "select itm.item, itm.[description], itm.u_m 
 , loc.loc, lot = isnull(lot.lot,'0'), qty_on_hand = coalesce(lot.qty_on_hand,loc.qty_on_hand,whse.qty_on_hand)
from item_mst itm 
left join itemloc_mst loc on loc.item = itm.item 
left join lot_loc_mst lot on lot.item = itm.item 
left join itemwhse_mst whse on whse.item = itm.item 
where (itm.item like 'st%' or itm.item like 'rzi%' or itm.item like 'rca%' or itm.item like 'rcb%' or itm.item like 'p%')
and whse.qty_on_hand <> 0 and loc.qty_on_hand <> 0";
        $cSql2 = new SqlSrv();
        $rs = $cSql2->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
      function GetCountStock($user, $date) {
        $query = "select * from STS_countstock where create_date = '$date' and [user] = '$user'";
        $cSql2 = new SqlSrv();
        $rs = $cSql2->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function GetItemByTag($tag,$loc) {
        $query = "select distinct tag.id, itm.item, itm.[description], itm.u_m 
 , loc = isnull(lot.loc, loc.loc), lot = isnull(lot.lot,'0')
 , qty_on_hand = coalesce(lot.qty_on_hand, loc.qty_on_hand, whse.qty_on_hand)
from mv_bc_tag tag  
inner join item_mst itm on tag.item = itm.item 
inner join lot_loc_mst lot on lot.item = tag.item and lot.lot = tag.lot and lot.loc = '$loc'
left join itemloc_mst loc on loc.item = tag.item and lot.lot = tag.lot and loc.loc = '$loc'
inner join itemwhse_mst whse on whse.item = itm.item 
where tag.id = '$tag'
";
        $cSql2 = new SqlSrv();
        $rs = $cSql2->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }


    

}
