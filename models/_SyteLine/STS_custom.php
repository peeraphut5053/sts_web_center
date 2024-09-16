<?php
class STS_Custom {
    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function Add_STS_Custom_groupB($StartDate, $EndDate, $item, $weight_KG, $qty, $bundle, $pcs, $loc) {
        $query = "INSERT INTO STS_custom_groupB (date_from, date_to, item, weight_KG, qty, bundle, pcs, loc,createdate)
         VALUES ('$StartDate', '$EndDate', '$item', $weight_KG, '$qty', '$bundle', '$pcs', '$loc', GETDATE())";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        return $rs0;
    }

    function InsertSTS_Custom_In($doc_no, $date_in, $date_stock, $supplier, $country, $AD_rate, $weight_KG, $value, $remark) {
        $query = "INSERT INTO STS_custom_IN (doc_no, date_in, date_stock, supplier, country, AD_rate, weight_KG, value, remark, createdate)
         VALUES ('$doc_no', '$date_in', '$date_stock', '$supplier', '$country', $AD_rate, $weight_KG, $value, '$remark', GETDATE())";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function InsertSTS_Custom_Out($doc_no, $boatnote, $date, $item, $boat_name, $boat_no, $inv_no, $bundle, $weight_net, $weight_gross, $weight_zinc, $weight_nonzinc, $cust_po, $value, $pier, $BL_no, $loc_name) {
        $query = "INSERT INTO STS_custom_OUT (doc_no, boatnote, date, item, boat_name, boat_no, inv_no, bundle, weight_net, weight_gross, weight_zinc, weight_nonzinc, cust_po, value, pier, BL_no, loc_name, createdate)
         VALUES ('$doc_no', '$boatnote', '$date', '$item', '$boat_name', '$boat_no', '$inv_no', $bundle, $weight_net, $weight_gross, $weight_zinc, $weight_nonzinc, '$cust_po', $value, '$pier', '$BL_no', '$loc_name', GETDATE())";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function InsertSTS_Custom_Scrap($doc_no, $date, $item, $weight_KG, $value, $stamp_no, $ref_doc_no) {
        $query = "INSERT INTO STS_custom_scrap (doc_no, date, item, weight_KG, value, stamp_no, ref_doc_no, createdate)
        VALUES ('$doc_no', '$date', '$item', $weight_KG, $value, '$stamp_no', '$ref_doc_no', GETDATE())";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetSTS_Custom_In($StartDate, $EndDate) {
        $query = "select *, total = (value * 0.05) + ( (value + (value * 0.05)) * 0.07 )
from STS_custom_IN where date_in between '$StartDate' and '$EndDate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetSTS_Custom_scrap($StartDate, $EndDate) {
        $query = "select *, total = 0 from STS_custom_scrap where date between '$StartDate' and '$EndDate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetSTS_Custom_Out($StartDate, $EndDate) {
        $query = "select * from STS_custom_OUT where date between '$StartDate' and '$EndDate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetSTS_custom_mainrpt_acct($StartDate, $EndDate) {
        $query = "EXEC [dbo].STS_custom_mainrpt_acct
@TransactionDateStarting = '$StartDate',
@TransactionDateEnding = '$EndDate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
?>
