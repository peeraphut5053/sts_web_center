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

}
