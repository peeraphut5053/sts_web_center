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

    function InsertSTS_Custom_In($doc_no, $date_in, $date_stock, $supplier, $country, $AD_rate, $weight_KG, $value, $remark, $item,  $type) {

        $sql = "SELECT * FROM STS_custom_IN WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);

        if ($doc_no == $rs0[1]["doc_no"]) {
            $query = "UPDATE STS_custom_IN set date_stock = '$date_stock', supplier = '$supplier', country = '$country', AD_rate = $AD_rate, weight_KG = $weight_KG, value = $value, remark = '$remark', item = '$item', type = $type where doc_no = '$doc_no'";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0;
        } else {
            $query = "INSERT INTO STS_custom_IN (doc_no, date_in, date_stock, supplier, country, AD_rate, weight_KG, value, remark, item, type, createdate)
            VALUES ('$doc_no', '$date_in', '$date_stock', '$supplier', '$country', $AD_rate, $weight_KG, $value, '$remark', '$item', $type, GETDATE())";
           $cSql = new SqlSrv();
           $rs0 = $cSql->SqlQuery($this->StrConn, $query);
           array_splice($rs0, count($rs0) - 1, 1);
           return $rs0;
        }
    }

    function InsertSTS_Custom_Out($doc_no, $boatnote, $date, $item, $boat_name, $boat_no, $inv_no, $bundle, $weight_net, $weight_gross,$weight_zinc,$weight_nonzinc, $cust_po, $value, $pier, $BL_no, $loc_name,$loc_name2,$loc_name3,$loc_name4) {

        $sql = "SELECT * FROM STS_custom_OUT WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);

        if ($doc_no == $rs0[1]["doc_no"]) {
            $query = "UPDATE STS_custom_OUT set 
    boatnote = '$boatnote', 
    date = '$date', 
    item = '$item', 
    boat_name = '$boat_name', 
    boat_no = '$boat_no', 
    inv_no = '$inv_no',
    bundle = $bundle, 
    weight_net = $weight_net, 
    weight_gross = $weight_gross, 
    weight_zinc = " . ($weight_zinc !== '' ? $weight_zinc : 0.00) . ", 
    weight_non_zinc = " . ($weight_nonzinc !== '' ? $weight_nonzinc : 0.00) . ", 
    cust_po = '$cust_po', 
    value = $value, 
    pier = '$pier', 
    BL_no = '$BL_no', 
    loc_name = '$loc_name',
    loc_name2 = '$loc_name2',
    loc_name3 = '$loc_name3',
    loc_name4 = '$loc_name4' 
    where doc_no = '$doc_no'";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0;
        } else {
            $query = "INSERT INTO STS_custom_OUT (doc_no, boatnote, date, item, boat_name, boat_no, inv_no, bundle, weight_net, weight_gross, weight_zinc, weight_non_zinc, cust_po, value, pier, BL_no, loc_name, loc_name2, loc_name3, loc_name4, createdate)
                    VALUES ('$doc_no', '$boatnote', '$date', '$item', '$boat_name', '$boat_no', '$inv_no', $bundle, $weight_net, $weight_gross,  " . ($weight_zinc !== '' ? $weight_zinc : 0.00) . ", " . ($weight_nonzinc !== '' ? $weight_nonzinc : 0.00) . ", '$cust_po', $value, '$pier', '$BL_no', '$loc_name', '$loc_name2', '$loc_name3', '$loc_name4', GETDATE())";
           $cSql = new SqlSrv();
           $rs0 = $cSql->SqlQuery($this->StrConn, $query);
           array_splice($rs0, count($rs0) - 1, 1);
           return $rs0;
        } 
    }

    function InsertSTS_Custom_Scrap($doc_no, $date, $item, $weight_KG, $value, $stamp_no, $ref_doc_no) {

        $sql = "SELECT * FROM STS_custom_scrap WHERE doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        if ($doc_no == $rs0[1]["doc_no"]) {
            $query = "UPDATE STS_custom_scrap set date = '$date', item = '$item', weight_KG = $weight_KG, value = $value, stamp_no = '$stamp_no', ref_doc_no = '$ref_doc_no' where doc_no = '$doc_no'";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0;
        } else {
            $query = "INSERT INTO STS_custom_scrap (doc_no, date, item, weight_KG, value, stamp_no, ref_doc_no, createdate)
            VALUES ('$doc_no', '$date', '$item', $weight_KG, $value, '$stamp_no', '$ref_doc_no', GETDATE())";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0;
        }
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

    function GetDataReportRemainPDF($StartDate, $EndDate) {
        $query = "EXEC [dbo].[STS_custom_mainrpt_acct_remain]
  @TransactionDateStarting = '$StartDate',
  @TransactionDateEnding = '$EndDate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDataReportMoving($StartDate, $EndDate, $StartDate2, $EndDate2) {
        $query = "EXEC [dbo].[STS_custom_mainrpt_acct_remain]
        @TransactionDateStarting = '$StartDate2',
        @TransactionDateEnding = '$EndDate2'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        $query2 = "select *, total = (value * 0.05) + ( (value + (value * 0.05)) * 0.07 )
        from STS_custom_IN where date_in between '$StartDate' and '$EndDate'";

        $cSql = new SqlSrv();
        $rs1 = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs1, count($rs1) - 1, 1);

        $query3 = "EXEC [dbo].STS_custom_mainrpt_acct
@TransactionDateStarting = '$StartDate',
@TransactionDateEnding = '$EndDate'";

        $cSql = new SqlSrv();
        $rs2 = $cSql->SqlQuery($this->StrConn, $query3);
        array_splice($rs2, count($rs2) - 1, 1);

        $query4 = "EXEC [dbo].[STS_custom_mainrpt_acct_remain]
        @TransactionDateStarting = '$StartDate',
        @TransactionDateEnding = '$EndDate'";

        $cSql = new SqlSrv();
        $rs3 = $cSql->SqlQuery($this->StrConn, $query4);
        array_splice($rs3, count($rs3) - 1, 1);

        return [$rs0, $rs1, $rs2, $rs3];

    }

    function GetDataReportSummary($StartDate, $EndDate, $StartDate2, $EndDate2) {
        $query = "EXEC [dbo].[STS_custom_mainrpt_acct_remain]
        @TransactionDateStarting = '$StartDate2',
        @TransactionDateEnding = '$EndDate2'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        $query2 = "select *, total = (value * 0.05) + ( (value + (value * 0.05)) * 0.07 )
        from STS_custom_IN where date_in between '$StartDate' and '$EndDate'";

        $cSql = new SqlSrv();
        $rs1 = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs1, count($rs1) - 1, 1);

        $query3 = "select value from STS_custom_OUT where date between '$StartDate' and '$EndDate'";

        $cSql = new SqlSrv();
        $rs2 = $cSql->SqlQuery($this->StrConn, $query3);
        array_splice($rs2, count($rs2) - 1, 1);

        $query4 = "select value from STS_custom_scrap where item = 'เศษเหล็ก' and date between '$StartDate' and '$EndDate'";

        $cSql = new SqlSrv();
        $rs3 = $cSql->SqlQuery($this->StrConn, $query4);
        array_splice($rs3, count($rs3) - 1, 1);

        
        $query5 = "EXEC [dbo].[STS_custom_mainrpt_acct_remain]
        @TransactionDateStarting = '$StartDate',
        @TransactionDateEnding = '$EndDate'";

        $cSql = new SqlSrv();
        $rs4 = $cSql->SqlQuery($this->StrConn, $query5);
        array_splice($rs4, count($rs4) - 1, 1);

        return [$rs0, $rs1, $rs2, $rs3 , $rs4];

    }

    function GetDataReport($StartDate, $EndDate) {
      
        $query2 = "select *, total = (value * 0.05) + ( (value + (value * 0.05)) * 0.07 )
        from STS_custom_IN where date_in between '$StartDate' and '$EndDate'";

        $cSql = new SqlSrv();
        $rs1 = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs1, count($rs1) - 1, 1);

        $query3 = "select value, weight_net from STS_custom_OUT where date between '$StartDate' and '$EndDate'";

        $cSql = new SqlSrv();
        $rs2 = $cSql->SqlQuery($this->StrConn, $query3);
        array_splice($rs2, count($rs2) - 1, 1);

        $query4 = "select value, weight_KG from STS_custom_scrap where item = 'เศษเหล็ก' and date between '$StartDate' and '$EndDate'";

        $cSql = new SqlSrv();
        $rs3 = $cSql->SqlQuery($this->StrConn, $query4);
        array_splice($rs3, count($rs3) - 1, 1);

        return [$rs1, $rs2, $rs3];

    }

}
?>
