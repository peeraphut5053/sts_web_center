<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DeliveryOrder
 *
 * @author TonSuchard
 */
class DeliveryOrder {

    var $StrConn = "";
    public $_txtDoNum = "";
    public $_txtDoNumFrom = "";
    public $_txtDoNumTo = "";
    public $_txtPickFrom = "";
    public $_txtPickTo = "";
    public $_txtCoFrom = "";
    public $_txtCoTo = "";
    public $_txtItemFrom = "";
    public $_txtItemTo = "";
    public $_txtCustFrom = "";
    public $_txtCustTo = "";
    public $_HdrOnly = "";
    public $_txtDoDateFrom = "";
    public $_txtDoDateTo = "";
    public $_FromDate = "";
    public $_ToDate = "";
    public $_CerDate = "";
    public $_CoNum = "";
    public $_CoLine = "";
    public $_MultiLotDateStart = "";
    public $_MultiLotDateEnd = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetCER_DO($and ) {
        $cerDate = $this->_CerDate;
        $query = "Select  CONVERT(varchar,ship_date,120) as ship_date_conv , * from V_WebApp_DO   ";
        $query .= " WHERE "
                . "( co_num like 'TT%' ) "
//                . "AND (stat = 'F' ) "
                . " $and "
                ." order by ship_date, do_num, do_seq ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetMultipleLotDoSeq() {
        $CoNum = $this->_CoNum;
        $CoLine = $this->_CoLine;
        $CerDate = $this->_CerDate;


        $query = "SELECT lot, NULLIF(b.description,b.loc) as loc_desc , CONVERT (varchar, CAST(qty  AS money), 1) as qty_conv  "
                . "FROM  matltran_mst a LEFT JOIN location_mst b ON (a.loc= b.loc ) "
                . "WHERE ref_num = '$CoNum' AND ref_line_suf = $CoLine  "
                . "AND trans_date BETWEEN '$CerDate 00:00:00' AND '$CerDate 23:59:59'   ";
        $cSql = new SqlSrv();
//        return $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDoInventoryDetail($do_num, $sts_no, $cust_po) {
        $Searchdo_num = "";
        $Searchsts_no = "";
        $Searchcust_po = "";
        if ($do_num != "") {
            $Searchdo_num = "and do_seq_mst.do_num = '$do_num' ";
        }
        if ($sts_no != "") {
            $Searchsts_no = "and sts_no like '%$sts_no%' ";
        }
        if ($cust_po != "") {
            $Searchcust_po = "and co_mst.cust_po like '%$cust_po%'";
        }
        $query = " select distinct top 20000 isnull(sts_remark_line_report.remark,'') as remark,mv_bc_tag.id,do_num , do_seq_mst.do_line ,loc, do_seq_mst.ref_num as co_num ,ref_line as co_line , co_mst.cust_po ,co_mst.Uf_StsPO_refNo as STS_PO ,coitem_mst.item, item_mst.Uf_typeEnd ,item_mst.Uf_NPS , item_mst.Uf_Grade ,item_mst.Uf_Schedule, item_mst.Uf_length ,item_mst.Uf_spec ,mv_bc_tag.lot ,sts_no,qty_sts_no , sts_no2 ,qty_sts_no2,sts_no3 ,qty_sts_no3 from do_seq_mst LEFT JOIN co_mst ON co_mst.co_num = do_seq_mst.ref_num LEFT JOIN coitem_mst on do_seq_mst.ref_num = coitem_mst.co_num and do_seq_mst.ref_line = coitem_mst.co_line and do_seq_mst.ref_release = coitem_mst.co_release LEFT JOIN item_mst on item_mst.item = coitem_mst.item left join matltrack_mst mltk on mltk.ref_num = do_seq_mst.ref_num AND mltk.ref_line_suf = do_seq_mst.ref_line AND mltk.ref_release =do_seq_mst.ref_release AND mltk.trans_date = do_seq_mst.ship_date AND mltk.date_seq = do_seq_mst.date_seq and mltk.qty<0 and mltk.ref_type = 'O' LEFT JOIN mv_bc_tag on mv_bc_tag.lot = mltk.lot and mv_bc_tag.item = mltk.item LEFT JOIN sts_remark_line_report on sts_remark_line_report.lot = mv_bc_tag.lot "
                . " where 1=1 ";

        $query = $query . $Searchdo_num . $Searchsts_no . $Searchcust_po;
        $query = $query . " and  mv_bc_tag.receipt = 1 order by lot ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetCarSelect() {
        $query = "Select * FROM STS_delivery_note_vehicle where vehicle_type = 'car'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetShipSelect() {
        $query = "Select * FROM STS_delivery_note_vehicle where vehicle_type = 'ship' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDOSelect() {
        $query = "select top(20) do_num as id,* FROM do_hdr_mst where do_num like '%DB1912000%' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GET_do_hdr_mst() {
        $FromDate = $this->_FromDate;
        $ToDate = $this->_ToDate;
        $query = "select top 2000 "
                . "CONVERT(varchar,do_hdr_date,103) as do_hdr_date_conv,"
                . "CONVERT(varchar,pickup_date,103) as pickup_date_conv,"
                . " do_num,do_hdr_date,cust_num,do_value,pickup_date,invoicee_name FROM do_hdr_mst where 1=1 ";
        if ($FromDate != "") {
            $query = $query . "AND do_hdr_date BETWEEN '$FromDate 00:00:00' AND '$ToDate 23:59:59'  ";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GET_do_line_mst() {
        $txtDoNum = $this->_txtDoNum;
        $query = "select top 500 do_num,do_line,weight,"
                . "CONVERT(varchar,RecordDate,103) as RecordDate_conv,"
                . "UpdatedBy "
                . "FROM do_line_mst where do_num = '$txtDoNum' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function ConnectionInformation() {
        $cSql = new SqlSrv();
        $q = "select UserName,"
                . " CONVERT(varchar,RecordDate,114) as RecordDate,"
                . " CONVERT(varchar,CreateDate,114) as CreateDate,"
                . " DATEDIFF( n , RecordDate , GETDATE() ) as minutediff "
                . " FROM ConnectionInformation "
                . " where SessionType <> 4 ";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function ConnectionInformation2() {
        $cSql = new SqlSrv();
        $q = "select DATEDIFF( n , RecordDate , GETDATE() ) as minutediff ,UserName,"
                . " CONVERT(varchar,RecordDate,114) as RecordDate,"
                . " CONVERT(varchar,CreateDate,114) as CreateDate "
                . " FROM ConnectionInformation where SessionType <> 4 "
                . " and DATEDIFF ( n , RecordDate , GETDATE() ) > 15";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function STS_DELETE_SESSION_USER($minkick) {
        $cSql = new SqlSrv();
        $q = "EXEC STS_DELETE_SESSION_USER '$minkick' ";
        $cSql->SqlQuery($this->StrConn, $q);
    }

    Function Log_Request_Session($username,$department) {
        $cSql = new SqlSrv();
        $sqlUser = "select * from usernames where Username is not null and Username = '$username'";
        $rs_sqlUser = $cSql->SqlQuery($this->StrConn, $sqlUser);
        
        if ($username ==  isset($rs_sqlUser[1]["Username"]) && $username != ""){
            echo '<script>setTimeout(function(){ window.close(); }, 1000);</script>';
            echo ''
            . '<div style="font-size:3em;text-align:center;padding-top:10%;">'
            . ' <div style="border-style: outset;padding:50px;border-radius: 12px;margin: 2em;border: 2px solid green;">"ยืนยันการขอเข้าระบบสำเร็จ"</div>'
        
            . '<div><img src="sts_logo.jpg" alt="Cinque Terre" width="300" ></div>'
            . '</div>';
            
            $sql = "INSERT INTO log_syteline (username,department,datetime) VALUES ('$username','$department', Getdate())";
            $rs = $cSql->SqlQuery($this->StrConn, $sql);
            
            $q = "EXEC STS_DELETE_SESSION_USER '15' ";
            $cSql->SqlQuery($this->StrConn, $q);
            return $rs;
        }
        else
        {
            echo '<script>alert("ไม่พบผู้ใช้นี้");</script>';
            echo " <script language='JavaScript'>history.go(-1);</script>"; 
        }
    }
    Function ConnectionInformation3() {
        $cSql = new SqlSrv();
        $q = "select GroupName,count(UserGroupMap_mst.UserId) as useronline "
                . "FROM UserGroupMap_mst "
                . " INNER JOIN GroupNames ON GroupNames.GroupId = UserGroupMap_mst.GroupId "
                . " INNER JOIN UserNames ON UserNames.UserId = UserGroupMap_mst.UserId "
                . " INNER JOIN ConnectionInformation ON ConnectionInformation.UserName = UserNames.UserName "
                . " Group by GroupName,UserGroupMap_mst.GroupId order by GroupName";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetDoHdr() {
        $txtDoNumFrom = $this->_txtDoNumFrom;
        $txtDoNumTo = $this->_txtDoNumTo;
        $txtCustFrom = $this->_txtCustFrom;
        $txtCustTo = $this->_txtCustTo;
        $txtDoDateFrom = $this->_txtDoDateFrom;
        $txtDoDateTo = $this->_txtDoDateTo;
        $txtCoFrom = $this->_txtCoFrom;
        $txtCoTo = $this->_txtCoTo;
        $txtItemFrom = $this->_txtItemFrom;
        $txtItemTo = $this->_txtItemTo;
        $txtPickFrom = $this->_txtPickFrom;
        $txtPickTo = $this->_txtPickTo;

        $sql = "{call MV_DELIVERY_ORDER_REPORT (?,?,?,?,?,?,?,?,?,?,?,?)}";
        $params = array(
            array($txtDoNumFrom, SQLSRV_PARAM_IN),
            array($txtDoNumTo, SQLSRV_PARAM_IN),
            array($txtCustFrom, SQLSRV_PARAM_IN),
            array($txtCustTo, SQLSRV_PARAM_IN),
            array($txtDoDateFrom, SQLSRV_PARAM_IN),
            array($txtDoDateTo, SQLSRV_PARAM_IN),
            array($txtCoFrom, SQLSRV_PARAM_IN),
            array($txtCoTo, SQLSRV_PARAM_IN),
            array($txtItemFrom, SQLSRV_PARAM_IN),
            array($txtItemTo, SQLSRV_PARAM_IN),
            array($txtPickFrom, SQLSRV_PARAM_IN),
            array($txtPickTo, SQLSRV_PARAM_IN),
        );

        $query = sqlsrv_query($this->StrConn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

        return $result;
//        $query = "SELECT DISTINCT * FROM V_WebApp_Report_DeliveryOrder "
//                . "WHERE 1=1  ";
//
//        if (($txtDoNumFrom <> "") && ($txtDoNumTo <> "")) {
//            $query = $query . " AND ( do_num >= '$txtDoNumFrom'  AND do_num <= '$txtDoNumTo'  ) ";
//        }
//        if (($txtPickFrom <> "") && ($txtPickTo <> "")) {
//            $query = $query . " AND ( pickup_date   BETWEEN  '$txtPickFrom'  AND  '$txtPickTo'  ) ";
//        }
//        if (($txtCoFrom <> "") && ($txtCoTo <> "")) {
//            $query = $query . " AND ( co_num >= '$txtCoFrom'  AND co_num <= '$txtCoTo'  ) ";
//        }
//        if (($txtItemFrom <> "") && ($txtItemTo <> "")) {
//            $query = $query . " AND ( item >= '$txtItemFrom'  AND item <= '$txtItemTo'  ) ";
//        }
//        if (($txtCustFrom <> "") && ($txtCustTo <> "")) {
//            $query = $query . " AND ( cust_num >= '$txtCustFrom'  AND cust_num <= '$txtCustTo'  ) ";
//        }
//        if ($this->_HdrOnly == "1") {
//            $query = $query . " AND ( do_seq =1  ) ";
//        }
////        return $query;
//        $cSql = new SqlSrv();
//        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        array_splice($rs0, count($rs0) - 1, 1);
//        return $rs0;
    }

    function GetDoSeq($do_num) {
        $query = "SELECT * FROM V_WebApp_Report_DeliveryOrder "
                . "WHERE do_num ='$do_num'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDoPending() {
        $txtDoDateFrom = $this->_txtDoDateFrom;
        $txtDoDateTo = $this->_txtDoDateTo;
        $FromDate = $this->_FromDate;

        $query = "SELECT * FROM V_WebApp_Report_DO_Pending_To_INV  "
                . "WHERE (ref_num like 'TT%') "
                . "AND ( qty_invoiced = 0 AND qty_shipped > 0 )  ";
        if ($FromDate == "INV_Date") {
            $query .= " AND ship_date BETWEEN '$txtDoDateFrom' AND '$txtDoDateTo'  ";
        } else if ($FromDate == "DO_Date") {
            $query .= " AND do_hdr_date BETWEEN '$txtDoDateFrom' AND '$txtDoDateTo'  ";
        }

        $query = "select v.* , isnull(sts.remark,'') as remark from V_STS_DOnoINV v left join STS_DOnoINV_remark sts on v.do_num = sts.do_num and v.do_line = sts.do_line and v.do_seq = sts.do_seq and v.co_num = sts.co_num and v.co_line = sts.co_line and isnull(v.lot,'') = isnull(sts.lot,'') "
                . "where (v.do_num not like '%X%' or v.do_num is null)   and (convert(date,ship_date) between '$txtDoDateFrom'and '$txtDoDateTo') order by do_num,do_seq";

 


        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function DO_PENDING_REMARK($co_num, $co_line, $do_num, $do_line, $do_seq, $lot, $remark) {

        $query0 = " select * FROM STS_DOnoINV_remark "
                . " where co_num = '$co_num' and co_line = '$co_line' and do_num = '$do_num' and do_line = '$do_line' and do_seq = '$do_seq' and lot = '$lot' ";

        $cSql0 = new SqlSrv();
        $rs0 = $cSql0->SqlQuery($this->StrConn, $query0);
        array_splice($rs0, count($rs0) - 1, 1);

        if (!$rs0[0]) {
            $query = "INSERT INTO STS_DOnoINV_remark (co_num, co_line, do_num, do_line, do_seq, lot,remark) VALUES ('$co_num', '$co_line', '$do_num', '$do_line', '$do_seq', '$lot','$remark')";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $query);
        } else {
            $query = "Update STS_DOnoINV_remark set remark = '$remark' where co_num = '$co_num' and co_line = '$co_line' and do_num = '$do_num' and do_line = '$do_line' and do_seq = '$do_seq' and lot = '$lot' ";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $query);
        }



        return "Insert";
    }

    function GetDataWith_SP_DO_Line() {

        $txtDoNum = $this->_txtDoNum;
        $sql = "{call MV_DELIVERY_ORDER_REPORT_DETAIL (?)}";
        $params = array(
            array($txtDoNum, SQLSRV_PARAM_IN)
        );
        $query = sqlsrv_query($this->StrConn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
//        print_r($result);
        return $result;


//        $str = "select DISTINCT  ";
//        $str = $str . "  do_hdr_mst.do_hdr_date ";
//        $str = $str . "   , do_hdr_mst.pickup_date ";
//        $str = $str . "   , do_hdr_mst.do_num ";
//        $str = $str . "   , do_hdr_mst.cust_num  ";
//        $str = $str . "   , custaddr_mst.name ";
//        $str = $str . "   , AIT_Preship_Do_Seq.do_line ";
//        $str = $str . "   , AIT_Preship_Do_Seq.do_seq  ";
//        $str = $str . "   , AIT_Preship_Do_Seq.Co_num AS DoCoNum ";
//        $str = $str . "   , AIT_Preship_Do_Seq.Co_line ";
//        $str = $str . "   , AIT_Preship_Do_Seq.Co_Release ";
//        $str = $str . "   , AIT_Preship_Do_Seq.loc ";
//        $str = $str . "   , coitem_mst.item ";
//        $str = $str . "   , isnull(item_mst.description,coitem_mst.description) as description  ";
//        $str = $str . "   , co_mst.Co_num ";
//        $str = $str . "   , co_mst.cust_po ";
//        $str = $str . "   , item_mst.Uf_Thickness ";
//        $str = $str . "   , item_mst.Uf_length ";
//        $str = $str . "  , isnull(item_mst.Uf_Pack,0) as Uf_Pack ";
//        $str = $str . "   , coitem_mst.qty_ordered ";
//        $str = $str . "   , AIT_Preship_Do_Seq.Qty ";
//        $str = $str . "   , case when item_mst.Uf_Pack <> 0 then FLOOR((AIT_Preship_Do_Seq.Qty / item_mst.Uf_Pack))";
//        $str = $str . "      else FLOOR((AIT_Preship_Do_Seq.Qty / 999999)) END AS QtyBundle ";
//        $str = $str . " , case when item_mst.Uf_Pack <> 0 then CEILING(AIT_Preship_Do_Seq.Qty % item_mst.Uf_Pack) ";
//        $str = $str . "		  else CEILING(AIT_Preship_Do_Seq.Qty % 999999) END AS QtyRemainder ";
//        $str = $str . "  , isnull(coitem_mst.uf_um2,coitem_mst.u_m) as u_m ";
//        $str = $str . "  , item_mst.u_m as S_u_m";
//        $str = $str . "  , item_mst.unit_weight";
//        $str = $str . "  , item_mst.unit_weight*AIT_Preship_Do_Seq.Qty AS SumWeight";
//        $str = $str . "  , CAST(SpecificNotes.NoteContent AS VARCHAR ) as NoteContent  ";
//        $str = $str . " from  do_hdr_mst";
//        $str = $str . " LEFT OUTER JOIN ObjectNotes ON do_hdr_mst.RowPointer = ObjectNotes.RefRowPointer";
//        $str = $str . " LEFT OUTER JOIN SpecificNotes ON ObjectNotes.SpecificNoteToken = SpecificNotes.SpecificNoteToken";
//        $str = $str . " LEFT OUTER JOIN custaddr_mst ON do_hdr_mst.site_ref = custaddr_mst.site_ref ";
//        $str = $str . "                              and do_hdr_mst.cust_num = custaddr_mst.cust_num ";
//        $str = $str . "							  and do_hdr_mst.cust_seq = custaddr_mst.cust_seq";
//        $str = $str . "  LEFT OUTER JOIN do_line_mst ON do_hdr_mst.site_ref = do_line_mst.site_ref";
//        $str = $str . "                             and do_hdr_mst.do_num = do_line_mst.do_num";
//        $str = $str . "  LEFT OUTER JOIN AIT_Preship_Do_Seq ON do_line_mst.site_ref = AIT_Preship_Do_Seq.Site ";
//        $str = $str . "                                    and do_line_mst.do_num = AIT_Preship_Do_Seq.do_num ";
//        $str = $str . "									and do_line_mst.do_line = AIT_Preship_Do_Seq.do_line";
//        $str = $str . "  LEFT OUTER JOIN coitem_mst ON AIT_Preship_Do_Seq.Site = coitem_mst.site_ref ";
//        $str = $str . "                           and AIT_Preship_Do_Seq.Co_num = coitem_mst.co_num";
//        $str = $str . "							and AIT_Preship_Do_Seq.Co_line = coitem_mst.co_line";
//        $str = $str . "							and AIT_Preship_Do_Seq.Co_Release = coitem_mst.co_release";
//        $str = $str . "  LEFT OUTER JOIN co_mst ON coitem_mst.site_ref = co_mst.site_ref and coitem_mst.Co_num = co_mst.Co_num";
//        $str = $str . "  LEFT OUTER JOIN item_mst ON coitem_mst.site_ref = item_mst.site_ref and coitem_mst.item = item_mst.item";
//        $str = $str . " WHERE do_hdr_mst.do_num  ='$do_num'  ORDER BY do_seq ASC";
//
//
//        $cSql = new SqlSrv();
//        $rs0 = $cSql->SqlQuery($this->StrConn, $str);
//        array_splice($rs0, count($rs0) - 1, 1);
//        return $rs0;
    }

    function GetDataWith_SP_DO_Hdr() {
        $txtDoNum = "";


        $this->_txtDoNumFrom == "" ? $txtDoNumFrom = "" : $txtDoNumFrom = $this->_txtDoNumFrom;
        $this->_txtDoNumTo == "" ? $txtDoNumTo = "zzzzzzzzzzz" : $txtDoNumTo = $this->_txtDoNumTo;

        $this->_txtCustFrom == "" ? $txtCustFrom = "" : $txtCustFrom = $this->_txtCustFrom;
        $this->_txtCustTo == "" ? $txtCustTo = "zzzzzzzzzzz" : $txtCustTo = $this->_txtCustTo;


        $this->_txtCoFrom == "" ? $txtCoFrom = "" : $txtCoFrom = $this->_txtCoFrom;
        $this->_txtCoTo == "" ? $txtCoTo = "zzzzzzzzzzz" : $txtCoTo = $this->_txtCoTo;

        $this->_txtItemFrom == "" ? $txtItemFrom = "" : $txtItemFrom = $this->_txtItemFrom;
        $this->_txtItemTo == "" ? $txtItemTo = "zzzzzzzzzzz" : $txtItemTo = $this->_txtItemTo;


        $this->_txtPickFrom == "" ? $txtPickFrom = "2001-01-01" : $txtPickFrom = $this->_txtPickFrom;
        $this->_txtPickTo == "" ? $txtPickTo = "2999-12-31" : $txtPickTo = $this->_txtPickTo;

        $this->_txtDoDateFrom == "" ? $txtDoDateFrom = "2001-01-01" : $txtDoDateFrom = $this->_txtDoDateFrom;
        $this->_txtDoDateTo == "" ? $txtDoDateTo = "2999-12-31" : $txtDoDateTo = $this->_txtDoDateTo;
        $partHdr = "";
        $str = "select   do_hdr_mst.do_num,   do_hdr_mst.do_hdr_date    , do_hdr_mst.pickup_date  ,do_hdr_mst.cust_num ,custaddr_mst.name       from  do_hdr_mst";
        $str = $str . " LEFT OUTER JOIN ObjectNotes ON do_hdr_mst.RowPointer = ObjectNotes.RefRowPointer";
        $str = $str . " LEFT OUTER JOIN SpecificNotes ON ObjectNotes.SpecificNoteToken = SpecificNotes.SpecificNoteToken";
        $str = $str . " LEFT OUTER JOIN custaddr_mst ON do_hdr_mst.site_ref = custaddr_mst.site_ref ";
        $str = $str . "                              and do_hdr_mst.cust_num = custaddr_mst.cust_num ";
        $str = $str . "							  and do_hdr_mst.cust_seq = custaddr_mst.cust_seq";
        $str = $str . "  LEFT OUTER JOIN do_line_mst ON do_hdr_mst.site_ref = do_line_mst.site_ref";
        $str = $str . "                             and do_hdr_mst.do_num = do_line_mst.do_num";
        $str = $str . "  LEFT OUTER JOIN AIT_Preship_Do_Seq ON do_line_mst.site_ref = AIT_Preship_Do_Seq.Site ";
        $str = $str . "                                    and do_line_mst.do_num = AIT_Preship_Do_Seq.do_num ";
        $str = $str . "									and do_line_mst.do_line = AIT_Preship_Do_Seq.do_line";
        $str = $str . "  LEFT OUTER JOIN coitem_mst ON AIT_Preship_Do_Seq.Site = coitem_mst.site_ref ";
        $str = $str . "                           and AIT_Preship_Do_Seq.Co_num = coitem_mst.co_num";
        $str = $str . "							and AIT_Preship_Do_Seq.Co_line = coitem_mst.co_line";
        $str = $str . "							and AIT_Preship_Do_Seq.Co_Release = coitem_mst.co_release";
        $str = $str . "  LEFT OUTER JOIN co_mst ON coitem_mst.site_ref = co_mst.site_ref and coitem_mst.Co_num = co_mst.Co_num";
        $str = $str . "  LEFT OUTER JOIN item_mst ON coitem_mst.site_ref = item_mst.site_ref and coitem_mst.item = item_mst.item";
        $str = $str . "  WHERE  (do_hdr_mst.do_num >= '$txtDoNumFrom' and do_hdr_mst.do_num  <= '$txtDoNumTo') ";
        $str = $str . "  and (do_hdr_mst.cust_num between '$txtCustFrom' and '$txtCustTo') ";
        $str = $str . "  and (do_hdr_mst.do_hdr_date between '$txtDoDateFrom' and '$txtDoDateTo') ";
        $str = $str . "  and (co_mst.co_num between '$txtCoFrom' and '$txtCoTo') ";
        $str = $str . "  and (coitem_mst.item between '$txtItemFrom' and '$txtItemTo') ";
        $str = $str . "  and (do_hdr_mst.pickup_date between '$txtPickFrom' and '$txtPickTo')  "
                . "GROUP BY   do_hdr_mst.do_num, do_hdr_mst.do_hdr_date    , do_hdr_mst.pickup_date  ,do_hdr_mst.cust_num ,custaddr_mst.name ";


        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $str);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function updateRemark($lot, $remark) {

        $query_check = " select job FROM sts_remark_line_report where lot = '$lot' ";
        $cSql = new SqlSrv();
        $rs_check = $cSql->SqlQuery($this->StrConn, $query_check);
        if ($rs_check[0][0] == 0) {
            $query = "INSERT INTO sts_remark_line_report(report_name,lot,remark) VALUES ('RPT_DO_INVENTORY_DETAIL','$lot','$remark')  ";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $query);
            return "insert Success";
        } else {
            $query = "UPDATE STS_remark_line_report SET lot ='$lot',remark ='$remark' where lot ='$lot' and report_name = 'RPT_DO_INVENTORY_DETAIL' ";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $query);
            return "update Success";
        }
    }

}
