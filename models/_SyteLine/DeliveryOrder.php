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

    function GetDoInventoryDetail($do_num, $sts_no, $cust_po, $cust_num) {
        $Searchdo_num = "";
        $Searchsts_no = "";
        $Searchcust_po = "";
        // if ($do_num != "") {
        //     $Searchdo_num = "and do_seq_mst.do_num  = '$do_num' ";
        // }
        // if ($sts_no != "") {
        //     $Searchsts_no = "and sts_no like '%$sts_no%' ";
        // }
        // if ($cust_po != "") {
        //     $Searchcust_po = "and co_mst.cust_po like '%$cust_po%'";
        // }
        $query = " EXEC [dbo].[STS_WebApp_QC_DO_detail]
                    @do_num = N'$do_num',
                    @sts_no = '$sts_no',
                    @cust_po = '$cust_po',
                    @cust_num = '$cust_num' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
//         "select
//         top 20000 
//         isnull(sts_remark_line_report.remark,'') as remark
//         ,do_seq_mst.do_num 
//         , do_seq_mst.do_line ,mltk.loc, do_seq_mst.ref_num as co_num 
//         ,ref_line as co_line , co_mst.cust_po ,co_mst.Uf_StsPO_refNo as STS_PO 
//         ,coitem_mst.item, item_mst.Uf_typeEnd ,item_mst.Uf_NPS , item_mst.Uf_Grade 
//         ,item_mst.Uf_Schedule, item_mst.Uf_length ,item_mst.Uf_spec ,mltk.lot 
//         ,lot_mst.uf_lot_sts_no as sts_no
//         ,lot_mst.uf_qty_sts_no as qty_sts_no 
//         ,cosh.qty_shipped
//         , lot_mst.uf_sts_no2 as sts_no2 
//   , lot_mst.uf_qty_sts_no2 as qty_sts_no2
//   ,lot_mst.uf_sts_no3 as sts_no3 
//   ,lot_mst.uf_qty_sts_no3 as qty_sts_no3 

//         from do_seq_mst LEFT JOIN co_mst ON co_mst.co_num = do_seq_mst.ref_num 
//         LEFT JOIN coitem_mst on do_seq_mst.ref_num = coitem_mst.co_num 
//          and do_seq_mst.ref_line = coitem_mst.co_line and do_seq_mst.ref_release = coitem_mst.co_release 
//         LEFT JOIN item_mst on item_mst.item = coitem_mst.item 
//         left join matltrack_mst mltk on mltk.ref_num = do_seq_mst.ref_num AND mltk.ref_line_suf = do_seq_mst.ref_line 
//          AND mltk.ref_release =do_seq_mst.ref_release AND mltk.trans_date = do_seq_mst.ship_date 
//          AND mltk.date_seq = do_seq_mst.date_seq and mltk.qty<0 and mltk.ref_type = 'O' 
        
//         left join co_ship_mst cosh on cosh.do_num = do_seq_mst.do_num and cosh.do_line = do_seq_mst.do_line and cosh.do_seq = do_seq_mst.do_seq
//          and cosh.co_num = do_seq_mst.ref_num and cosh.co_line = do_seq_mst.ref_line and cosh.date_seq = do_seq_mst.date_seq
//          and cosh.ship_date = do_seq_mst.ship_date
        
//         left join lot_mst on mltk.lot = lot_mst.lot 

//         LEFT JOIN sts_remark_line_report on sts_remark_line_report.lot = lot_mst.lot 
//          where 1=1
//      and cosh.qty_shipped <> 0 ";
//          select
//          top 20000 
//          isnull(sts_remark_line_report.remark,'') as remark
//          ,do_seq_mst.do_num 
//          , do_seq_mst.do_line ,mltk.loc, do_seq_mst.ref_num as co_num 
//          ,ref_line as co_line , co_mst.cust_po ,co_mst.Uf_StsPO_refNo as STS_PO 
//          ,coitem_mst.item, item_mst.Uf_typeEnd ,item_mst.Uf_NPS , item_mst.Uf_Grade 
//          ,item_mst.Uf_Schedule, item_mst.Uf_length ,item_mst.Uf_spec ,mltk.lot 
//          ,sts_no
//          ,qty_sts_no 
//          ,cosh.qty_shipped
//          , sts_no2 ,qty_sts_no2,sts_no3 ,qty_sts_no3 
 
//    ,mv_bc_tag.id
 
//          from do_seq_mst LEFT JOIN co_mst ON co_mst.co_num = do_seq_mst.ref_num 
//          LEFT JOIN coitem_mst on do_seq_mst.ref_num = coitem_mst.co_num 
//           and do_seq_mst.ref_line = coitem_mst.co_line and do_seq_mst.ref_release = coitem_mst.co_release 
//          LEFT JOIN item_mst on item_mst.item = coitem_mst.item 
//          left join matltrack_mst mltk on mltk.ref_num = do_seq_mst.ref_num AND mltk.ref_line_suf = do_seq_mst.ref_line 
//           AND mltk.ref_release =do_seq_mst.ref_release AND mltk.trans_date = do_seq_mst.ship_date 
//           AND mltk.date_seq = do_seq_mst.date_seq and mltk.qty<0 and mltk.ref_type = 'O' 
         
//          left join co_ship_mst cosh on cosh.do_num = do_seq_mst.do_num and cosh.do_line = do_seq_mst.do_line and cosh.do_seq = do_seq_mst.do_seq
//           and cosh.co_num = do_seq_mst.ref_num and cosh.co_line = do_seq_mst.ref_line and cosh.date_seq = do_seq_mst.date_seq
//           and cosh.ship_date = do_seq_mst.ship_date
         
//          --left join ast_ship ast on ast.do_num = cosh.do_num and ast.do_line = cosh.do_line 
//          -- and ast.co_num = cosh.co_num and ast.co_line = cosh.co_line 
//          -- and ast.lot = mltk.lot
         
//          --left join mv_bc_tag on mv_bc_tag.id = ast.tag_id  
//    left join mv_bc_tag on mltk.lot = mv_bc_tag.lot and mltk.item = mv_bc_tag.item and abs(mltk.qty) = abs(mv_bc_tag.qty1)  and mv_bc_tag.active = 1 and mv_bc_tag.ship_stat = 1
//      and cosh.qty_shipped = mv_bc_tag.qty1
 
//          LEFT JOIN sts_remark_line_report on sts_remark_line_report.lot = mv_bc_tag.lot
//           where 1=1 --and cosh.qty_shipped = mv_bc_tag.qty1 and mv_bc_tag.ship_stat = 10

        // $query = $query . $Searchdo_num . $Searchsts_no . $Searchcust_po;
        // $query = $query . "  order by lot ";
        //and  mv_bc_tag.receipt = 1
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
                . " do_num,do_hdr_date,cust_num,do_value,pickup_date,invoicee_name FROM do_hdr_mst where 1=1 order by do_hdr_date desc";
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

    function GetDataDeliveryOptions() {
        $query = "select distinct do_num
from do_line_mst
where do_num like 'dx%'
order by do_num desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDataDeliveryDx($do_num) {
        $query = "select do_num, do_line, Uf_Container_num, Uf_Container_zeal, Uf_Container_size
  , Uf_Container_blank_weight, Uf_Container_car_num, uf_act_weight
from do_line_mst
where do_num = '$do_num'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function GetDeliveryReport($do_num_start, $do_num_end, $first_line, $last_line, $customer_start, $customer_end, $inovice_start, $inovice_end) {

        $in_start = $inovice_start == '' ? 'NULL' : "'$inovice_start'";
        $in_end = $inovice_end == '' ? 'NULL' : "'$inovice_end'";
        $cus_start = $customer_start == '' ? 'NULL' : "'$customer_start'";
        $cus_end = $customer_end == '' ? 'NULL' : "'$customer_end'";
 

        $query = "EXEC [dbo].[STS_DeliveryReport]
  @InvNumStarting = $in_start,
  @InvNumEnding = $in_end,
  @CustomerStarting = $cus_start,
  @CustomerEnding = $cus_end,
  @DONumStarting = N'$do_num_start',
  @DONumEnding = N'$do_num_end',
  @DOlineStarting = $first_line,
  @DOlineEnding = $last_line";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    
    function SaveDelivery($do_delivery, $line_delivery, $Uf_Container_num, $Uf_Container_zeal, $Uf_Container_size, $Uf_Container_blank_weight, $Uf_Container_car_num, $uf_act_weight) {
        $con_size = $Uf_Container_size == '' ? 'NULL' : $Uf_Container_size;
        $act_weight = $uf_act_weight == '' ? 'NULL' : $uf_act_weight;
        $query = "UPDATE do_line_mst set Uf_Container_num = '$Uf_Container_num', Uf_Container_zeal = '$Uf_Container_zeal', Uf_Container_size = $con_size, Uf_Container_blank_weight = $Uf_Container_blank_weight, Uf_Container_car_num = '$Uf_Container_car_num', Uf_act_weight = $act_weight where do_num = '$do_delivery' and do_line = '$line_delivery' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDataDeliveryCriteria($do_num_s, $do_num_e, $customer_c_s, $customer_c_e, $do_date_s, $do_date_e, $pick_date_s, $pick_date_e, $co_no_s, $co_no_e, $item_delivery_s, $item_delivery_e) {
        $do_num_s = $do_num_s == '' ? 'NULL' : "'$do_num_s'";
        $do_num_e = $do_num_e == '' ? 'NULL' : "'$do_num_e'";
        $customer_c_s = $customer_c_s == '' ? 'NULL' : "'$customer_c_s'";
        $customer_c_e = $customer_c_e == '' ? 'NULL' : "'$customer_c_e'";
        $do_date_s = $do_date_s == '' ? 'NULL' : "'$do_date_s'";
        $do_date_e = $do_date_e == '' ? 'NULL' : "'$do_date_e'";
        $pick_date_s = $pick_date_s == '' ? 'NULL' : "'$pick_date_s'";
        $pick_date_e = $pick_date_e == '' ? 'NULL' : "'$pick_date_e'";
        $co_no_s = $co_no_s == '' ? 'NULL' : "'$co_no_s'";
        $co_no_e = $co_no_e == '' ? 'NULL' : "'$co_no_e'";
        $item_delivery_s = $item_delivery_s == '' ? 'NULL' : "'$item_delivery_s'";
        $item_delivery_e = $item_delivery_e == '' ? 'NULL' : "'$item_delivery_e'";

            $query = "EXEC [dbo].[MV_DELIVERY_ORDER_REPORT]
  @DOnumStarting = $do_num_s,
  @DOnumEnding = $do_num_e,
  @CUSTnumStarting = $customer_c_s,
  @CUSTnumEnding = $customer_c_e,
  @TransactionDateStarting = $do_date_s,
  @TransactionDateEnding = $do_date_e,
  @COnumStarting = $co_no_s,
  @COnumEnding = $co_no_e,
  @ItemStarting = $item_delivery_s,
  @ItemEnding = $item_delivery_e,
  @TransactionDateStarting1 = $pick_date_e,
  @TransactionDateEnding1 = $pick_date_e";
    
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDoNumReturn() {
        $query = "select distinct pre.do_num 
from ait_preship_do_seq pre
  inner join coitem_mst coi on pre.co_num = coi.co_num and pre.co_line = coi.co_line and pre.co_release = coi.co_release
   and coi.qty_shipped <> 0 and pre.qty <> 0 and pre.do_num like 'do%'
   and coi.item not like 'ZR-%' 
order by do_num desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDoBySearch($search) {
        $query = "select distinct pre.do_num 
from ait_preship_do_seq pre
  inner join coitem_mst coi on pre.co_num = coi.co_num and pre.co_line = coi.co_line and pre.co_release = coi.co_release
   and coi.qty_shipped <> 0 and pre.qty <> 0 and pre.do_num like 'do%'
   and coi.item not like 'ZR-%' 
where pre.do_num like '%$search%'
order by do_num desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function GetCoByDo($do_num) {
        $query = "select distinct pre.co_num 
from ait_preship_do_seq pre
  inner join coitem_mst coi on pre.co_num = coi.co_num and pre.co_line = coi.co_line and pre.co_release = coi.co_release
   and coi.qty_shipped <> 0 and pre.qty <> 0 and pre.do_num like 'do%'
   and coi.item not like 'ZR-%' 
where pre.do_num = '$do_num'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    function GetItemByCo($do_num, $co_num) {
        $query = "select distinct coi.item, item.[description], item.u_m
from ait_preship_do_seq pre
  inner join coitem_mst coi on pre.co_num = coi.co_num and pre.co_line = coi.co_line and pre.co_release = coi.co_release
   and coi.qty_shipped <> 0 and pre.qty <> 0 and pre.do_num like 'do%'
   and coi.item not like 'ZR-%' 
     inner join item_mst item on coi.item = item.item
where pre.do_num = '$do_num' and pre.co_num = '$co_num'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function CreateItemReturn($user,$remark_h, $arr_do_num, $arr_co_num, $arr_item, $arr_qty, $arr_issue, $arr_remark, $stat, $return_type) {
        if (sqlsrv_begin_transaction($this->StrConn) === false) {
            die("Transaction failed: " . print_r(sqlsrv_errors(), true));
        }

        try {
            $year = date('y');
            $month = date('m');
            $prefix = "C{$year}{$month}";

            // วิธีที่ 1: ใช้ Table Lock (แนะนำ)
            $lockSql = "SELECT TOP 1 1 FROM STS_return_hdr WITH (TABLOCKX)";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $lockSql);

            // หรือวิธีที่ 2: Lock ด้วย Application Lock
            // $lockSql = "EXEC sp_getapplock @Resource='withdraw_doc_no', @LockMode='Exclusive', @LockTimeout=5000";
            // $cSql->SqlQuery($this->StrConn, $lockSql);

            // Query เลขล่าสุดหลัง lock แล้ว
            $sql = "SELECT TOP 1 doc_no 
                FROM STS_return_hdr 
                WHERE doc_no LIKE '{$prefix}%' 
                ORDER BY doc_no DESC";

            $result = $cSql->SqlQuery($this->StrConn, $sql);
            array_splice($result, count($result) - 1, 1);

            if (count($result) > 0) {
                $lastDoc = $result[0]['doc_no'];
                $lastNumber = intval(substr($lastDoc, -3));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $docNumber = sprintf("C%s%02d%03d", $year, $month, $newNumber);

            // ใส่ Unique Constraint ที่ table
            // ALTER TABLE STS_store_withdraw_hdr ADD CONSTRAINT UQ_doc_no UNIQUE (doc_no)

            // Insert header
            $query = "INSERT INTO STS_return_hdr (doc_no, remark, createdby, stat, return_method) 
                  VALUES ('$docNumber', '$remark_h', '$user', '$stat', '$return_type')";
            $cSql->SqlQuery($this->StrConn, $query);

            // Insert lines
            foreach ($arr_item as $key => $item) {
                $seq = $key + 1;
                $do_num = str_replace("'", "''", $arr_do_num[$key]);
                $co_num = str_replace("'", "''", $arr_co_num[$key]);
                $item_escaped = str_replace("'", "''", $arr_item[$key]);
                $qty = floatval($arr_qty[$key]);
                $issue = str_replace("'", "''", $arr_issue[$key]);
                $remark_l = str_replace("'", "''", $arr_remark[$key]);

                $query2 = "INSERT INTO STS_return_line 
                       (doc_no, do_num, co_num, item, qty, issue, remark, [user]) 
                       VALUES ('$docNumber', '$do_num', '$co_num', '$item_escaped', $qty, '$issue', '$remark_l', '$user')";
                $cSql->SqlQuery($this->StrConn, $query2);
            }

            sqlsrv_commit($this->StrConn);
            return $docNumber;
        } catch (Exception $e) {
            sqlsrv_rollback($this->StrConn);
            throw $e;
        }
    
    }

    function GetDocNoReturn() {
        $query = "SELECT doc_no FROM STS_return_hdr ORDER BY doc_no DESC";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReasonCause() {
        $query = "SELECT * FROM STS_return_cause";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetIssueReturn() {
        $query = "SELECT * FROM STS_return_issue";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemReturnByDocNo($doc_no) {
        $query = "SELECT h.*, l.*, h.remark as remark_h, h.stat as stat_h
   ,item.[description], customer = case when co.cust_num like 'EX%' then isnull(cust.addr##1,cust.name) else cust.name end
FROM STS_return_hdr h 
 LEFT JOIN STS_return_line l ON h.doc_no = l.doc_no
 left join co_mst co on co.co_num = l.co_num
 left join custaddr_mst cust on co.cust_num = cust.cust_num and co.cust_seq = cust.cust_seq
 left join item_mst item on item.item = l.item 
        WHERE h.doc_no = '$doc_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function CreateReturnPic($doc_no, $file_type) {
        $s = "SELECT doc_no FROM STS_return_pic WHERE doc_no = '$doc_no' ORDER BY doc_no DESC";
        $cSql = new SqlSrv();
        $doc = $cSql->SqlQuery($this->StrConn, $s);
        $doc_count = count($doc);
        if ($doc_count > 1) {
            $doc_count = $doc_count + 0;
        } else {
            $doc_count = 1;
        }
        $pathName = $doc_no . "_" . $doc_count . "." . $file_type;
        $query = "INSERT INTO STS_return_pic (doc_no,path) OUTPUT INSERTED.* VALUES('$doc_no','$pathName')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function AddItemReturn($doc_no, $do_num, $co_num, $item, $qty, $issue, $remark, $user) {
        $cSql = new SqlSrv();
        $query = "INSERT INTO STS_return_line (doc_no,do_num,co_num,item,qty,issue,remark,[user]) OUTPUT INSERTED.* VALUES('$doc_no','$do_num','$co_num','$item',$qty,'$issue','$remark','$user')";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UpdateItemReturn($doc_no, $do_num, $do_num_old, $co_num, $co_num_old, $item, $item_old, $qty, $remark, $issue) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_return_line  
        SET do_num = '$do_num', co_num = '$co_num', item = '$item', qty = $qty, issue = '$issue', remark = '$remark'
        WHERE doc_no = '$doc_no' AND do_num = '$do_num_old' AND co_num = '$co_num_old' AND item = '$item_old'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
    function DeleteItemReturn($doc_no, $do_num, $co_num, $item) {
        $cSql = new SqlSrv();
        $query = "DELETE FROM STS_return_line WHERE doc_no = '$doc_no' AND do_num = '$do_num' AND co_num = '$co_num' AND item = '$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UpdateReturnHeader($doc_no, $remark, $stat, $return_type) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_return_hdr SET remark = '$remark', stat = '$stat', return_method = '$return_type', updatedate = getdate() WHERE doc_no = '$doc_no'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function ReceiveItemReturn($doc_no, $returned) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_return_hdr SET returned = '$returned', updatedate = getdate() WHERE doc_no = '$doc_no'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function ApproveReturnByQc($doc_no, $qc) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_return_hdr SET qc = '$qc', updatedate = getdate() WHERE doc_no = '$doc_no'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    function GetReturnPicByDocNo($doc_no) {
        $cSql = new SqlSrv();
        $query = "SELECT * FROM STS_return_pic WHERE doc_no = '$doc_no'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UpdateCauseReturn($doc_no, $do_num, $co_num, $item, $cause) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_return_line SET cause = '$cause' WHERE doc_no = '$doc_no' AND do_num = '$do_num' AND co_num = '$co_num' AND item = '$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UpdateStatusReturn($doc_no, $do_num, $co_num, $item, $status) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_return_line SET stat = '$status' WHERE doc_no = '$doc_no' AND do_num = '$do_num' AND co_num = '$co_num' AND item = '$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
    function UpdateRemarkQcReturn($doc_no, $do_num, $co_num, $item, $remark) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_return_line SET remark = '$remark' WHERE doc_no = '$doc_no' AND do_num = '$do_num' AND co_num = '$co_num' AND item = '$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    function GetReportReturn($StartDate, $EndDate, $doc_no) {
        $wh = "";
        if ($StartDate != '' && $EndDate != '') {
            $wh .= " AND h.createdate BETWEEN '$StartDate' AND '$EndDate' ";
        }
        if ($doc_no != '') {
            $wh .= " AND h.doc_no = '$doc_no' ";
        }
        $cSql = new SqlSrv();
        $query = "SELECT h.*, l.*, h.remark as remark_h, h.stat as stat_h, rc.cause as cause_name, ri.issue as issue_name
   ,item.[description], customer = case when co.cust_num like 'EX%' then isnull(cust.addr##1,cust.name) else cust.name end
FROM STS_return_hdr h 
 LEFT JOIN STS_return_line l ON h.doc_no = l.doc_no
 left join co_mst co on co.co_num = l.co_num
 left join custaddr_mst cust on co.cust_num = cust.cust_num and co.cust_seq = cust.cust_seq
 left join item_mst item on item.item = l.item 
 left join STS_return_cause rc on l.cause = rc.id
 left join STS_return_issue ri on l.issue = ri.id
        WHERE 1=1 $wh";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function SalesApproveReturn($doc_no, $sales) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_return_hdr SET sales = '$sales', updatedate = getdate() WHERE doc_no = '$doc_no'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
}
