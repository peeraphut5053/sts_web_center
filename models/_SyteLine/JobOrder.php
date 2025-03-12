<?php

class JOBORDER {

    var $StrConn = "";
    public $_start_Jobdate = ""; //
    public $_end_Jobdate = ""; //
    public $_start_date = ""; //
    public $_end_date = ""; //
    public $_lot = ""; //
    public $_item = ""; //
    public $_location = ""; //
    public $_end_Lot = ""; //
    public $_ref_num = ""; //
    public $_trans_type = ""; //
    public $_job_type = ""; //
    public $_u_m = ""; //
    public $_w_c = ""; //

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetJobOrrderProcessing() {
        $start_Jobdate = $this->_start_Jobdate;
        $end_Jobdate = $this->_end_Jobdate;
        $lot = $this->_lot;
        $ref_num = $this->_ref_num;
        $trans_type = $this->_trans_type;
        $u_m = $this->_u_m;
        $item = $this->_item;
        $location = $this->_location;
        //$lot = "";
        if ($lot) {
            $lot = " AND  lot like '%$lot%'  ";
        }
        if ($ref_num) {
            $ref_num = " AND  ref_num like '%$ref_num%'  ";
        }
        if ($trans_type) {
            $trans_type = " AND  mat.trans_type like '%$trans_type%'  ";
        }
        if ($u_m) {
            $u_m = " AND  ItemUM like '%$u_m%'  ";
        }
        if ($item) {
            $item = " AND  mat.item like '$item%'  ";
        }
        if ($location) {
            $location = " AND   mat.loc like '%$location%'  ";
        }
        $criteria_date = " ";
        if (($start_Jobdate) && ($end_Jobdate)) {
            $criteria_date = " AND ( trans_date BETWEEN '$start_Jobdate 00:00:00.000' AND '$end_Jobdate 23:59:59.000' ) ";
        }
        $query = "select top 50000 mat.trans_num, mat.trans_type,trans_description, mat.loc, lot, trans_date, mat.item, ItemDesc, "
                . " mat.qty, ItemUM, RecordDate,ref_num "
                . "from matltran_mst mat "
                . " left join MaterialTransactionsAllView MTV on MTV.Trans_Num = mat.trans_num "
                . " and MTV.item = mat.item "
                . " left join trans_type_mst on trans_type_mst.trans_type = mat.trans_type "
                . " WHERE   1=1   "
                . " $criteria_date "
                . " $lot "
                . " $ref_num "
                . " $u_m "
                . " $item "
                . " $location "
                . " $trans_type ";

        $query = $query . " ORDER BY RecordDate desc,mat.trans_type asc,lot asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDonutChart() {
        $start_Jobdate = $this->_start_Jobdate;
        $end_Jobdate = $this->_end_Jobdate;
        $lot = $this->_lot;
        $ref_num = $this->_ref_num;
        $trans_type = $this->_trans_type;
        //$lot = "";
        if ($lot) {
            $lot = " AND  lot like '%$lot%'  ";
        }
        if ($ref_num) {
            $ref_num = " AND  ref_num like '%$ref_num%'  ";
        }
        if ($trans_type) {
            $trans_type = " AND  trans_type_mst.trans_type like '%$trans_type%'  ";
        }
        $criteria_date = " ";
        if (($start_Jobdate) && ($end_Jobdate)) {
            $criteria_date = " AND ( trans_date BETWEEN '$start_Jobdate 00:00:00.000' AND '$end_Jobdate 23:59:59.000' ) ";
        }

        $query = " select trans_description as label , count(trans_description) as value "
                . " from matltran_mst mat  left join MaterialTransactionsAllView MTV on MTV.Trans_Num = mat.trans_num "
                . " inner join trans_type_mst on MTV.TransType = trans_type_mst.trans_type and MTV.item = mat.item  "
                . " WHERE   1=1   "
                . " $criteria_date "
                . " $lot "
                . " $ref_num "
                . " $trans_type ";
        $query = $query . " group by trans_description";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetJobOrrderProcessingQty() {
        $query = "Select COUNT(convert(varchar, trans_date, 23)) as countqty from matltran_mst mat left join MaterialTransactionsView MTV on MTV.Trans_Num = mat.trans_num "
                . "WHERE 1 = 1 and trans_date = convert(varchar, GETDATE(), 23)";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetManufacturing() {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $w_c = $this->_w_c;
        $ref_num = $this->_ref_num;
        $_item = $this->_item;
        //$lot = "";

        $query = "EXEC [dbo].[STS_Manufacturing_report]
  @startDate = N'$start_date',
  @endDate = N'$end_date',
  @item = '$_item',
  @ref = '$ref_num',
  @wc = '$w_c'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetJobSlit($txtFromDate, $txtToDate, $txtref_num, $txtItem, $txtw_c) {
        $start_Jobdate = $this->_start_Jobdate;
        $end_Jobdate = $this->_end_Jobdate;
        $w_c = $this->_w_c;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;
        //$lot = "";

        if ($ref_num) {
            $ref_num = " AND  matl1.ref_num like '%$ref_num%'  ";
        }
        if ($job_type) {
            $job_type = " AND  matl1.ref_num like '%$job_type%'  ";
        }

        $criteria_date = " ";
        if (($txtFromDate) && ($txtToDate)) {
            $criteria_date = " AND ( matl1.trans_date BETWEEN '$txtFromDate' AND '$txtToDate' ) ";
        }

        $item = " ";
        if ($this->_item) {
            $item = " AND  jobmatl_mst.item like '%$this->_item%'  ";
        }
        $w_c = " ";
        if ($this->_w_c) {
            $w_c = " AND  ( matl1.wc like '%$this->_w_c%' or  wc_mst.description like '%$this->_w_c%')  ";
        }
        $query = "";
        $query = $query . " select matl1.ref_num,convert(varchar, matl1.trans_date, 23) as trans_date,Mv_Bc_Tag.sts_no, sum(Mv_Bc_Tag .qty1) as KG, item_mst.item, item_mst.description as itemDesc, matl1.wc, wc_mst.description as wc_name ";
        $query = $query . " from matltran_mst as matl1 left join Mv_Bc_Tag on matl1.ref_num = Mv_Bc_Tag.job   and matl1.item=Mv_Bc_Tag.item and matl1.lot = Mv_Bc_Tag.lot and FORMAT(Mv_Bc_Tag.mfg_date, 'yyyy-MM-dd') =  matl1.trans_date left join item_mst on matl1.item = item_mst.item left join wc_mst on matl1.wc = wc_mst.wc ";
        $query = $query . " where 1=1 and trans_type ='F' and (matl1.wc = 'P1SL00' or matl1.wc = 'P1SL01' or matl1.wc = 'P1SL02' or matl1.wc = 'P1SL03' or matl1.wc = 'S1SL01' or  matl1.wc = 'S1SL02' or matl1.wc = 'S1SL03' or matl1.wc = 'W1SL04' or  matl1.wc = 'W1SL05' ) ";
        $query = $query . $ref_num;
        $query = $query . $criteria_date;
        $query = $query . $item;
        $query = $query . $w_c;
        $query = $query . " group by matl1.ref_num ,convert(varchar, matl1.trans_date, 23),Mv_Bc_Tag.sts_no, item_mst.description, matl1.wc, wc_mst.description,item_mst.item ";
        $query = $query . " Having sum(matl1.qty) <> 0 order by ref_num, trans_date ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function wc_mst() {
        $query = "SELECT * FROM wc_mst order by description ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetJobPacking() {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $w_c = $this->_w_c;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;


        $start_date = str_replace("T", " ", $start_date);
        $start_date = str_replace("Z", "", $start_date);

        $end_date = str_replace("T", " ", $end_date);
        $end_date = str_replace("Z", "", $end_date);

        if ($ref_num) {
            $ref_num = " AND  matl1.ref_num like '%$ref_num%'  ";
        }
        if ($job_type) {
            $job_type = " AND  matl1.ref_num like '%$job_type%'  ";
        }

        $criteria_date = " ";
        if (($start_date) && ($end_date)) {
            $criteria_date = " AND ( matl1.Createdate BETWEEN '$start_date' AND '$end_date' ) ";
        }
        $item = " ";
        if ($this->_item) {
            $item = " AND  matl1.item like '%$this->_item%'  ";
        }
        $w_c = " ";
        if ($this->_w_c) {
            $w_c = " AND  ( matl1.wc like '$this->_w_c%' or  wc_mst.description like '$this->_w_c%')  ";
        }

        $query = "";
        $query = $query . " SELECT item_mst.Uf_NPS, item_mst.Uf_spec, isnull(item_mst.Uf_Grade, '') AS Uf_Grade, isnull(item_mst.Uf_Schedule, '') AS Uf_Schedule, item_mst.Uf_length, item_mst.Uf_TypeEnd, isnull(sts_remark_line_report.remark, '') AS remark, matl1.ref_num, matl1.item, item_mst.description AS ItemDesc, format(sum(matl1.qty), 'N0') AS Total_PCS,AllLot = stuff( (SELECT '; ' + right(s1.lot, 4)+'(' +convert(nvarchar,convert(int,s1.qty))  FROM matltran_mst S1 WHERE S1.ref_num = matl1.ref_num AND S1.item = matl1.item AND S1.trans_type = 'F' AND S1.qty <> 0 AND (s1.Createdate BETWEEN '$start_date' AND '$end_date') AND (right(S1.loc, 1) not in ('B', 'C')) GROUP BY S1.lot,S1.qty ORDER BY S1.lot ASC FOR XML PATH ('')) , 1, 1, ''), qty = stuff( (SELECT '; ' + convert(nvarchar,format(sum(S1.qty), 'N0')) FROM matltran_mst S1 WHERE S1.ref_num = matl1.ref_num AND S1.item = matl1.item AND S1.trans_type = 'F' AND S1.qty <> 0 AND (s1.Createdate BETWEEN '$start_date' AND '$end_date') GROUP BY S1.qty FOR XML PATH ('')) , 1, 1, ''), bundle = stuff( (SELECT '; ' + convert(nvarchar,count(S2.qty)) FROM matltran_mst S2 WHERE S2.ref_num = matl1.ref_num AND S2.item = matl1.item AND S2.trans_type = 'F' AND S2.qty <> 0 AND (s2.Createdate BETWEEN '$start_date' AND '$end_date') GROUP BY S2.qty FOR XML PATH ('')), 1, 1, ''), pcs_qty = isnull(stuff( (SELECT '; ' + convert(nvarchar,format(S3.qty, 'N0')) FROM matltran_mst S3 WHERE S3.ref_num = matl1.ref_num AND S3.item = matl1.item AND S3.trans_type = 'F' AND S3.qty <> 0 AND (s3.Createdate BETWEEN '$start_date' AND '$end_date') GROUP BY S3.qty FOR XML PATH ('')), 1, 1, ''), 0), MinBundleNo = (select CAST(CAST(RIGHT(MIN(subMat1.lot), 4) AS INT) AS varchar) from matltran_mst subMat1 where Left(right(subMat1.lot,4),1) <= '9' and matl1.item = subMat1.item and subMat1.trans_type = 'F' and matl1.ref_num = subMat1.ref_num and matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) ,MaxBundleNo = (select CAST(CAST(RIGHT(MAX(subMat1.lot), 4) AS INT) AS varchar) from matltran_mst subMat1 where Left(right(subMat1.lot,4),1) <= '9' and matl1.item = subMat1.item and subMat1.trans_type = 'F'and matl1.ref_num = subMat1.ref_num and matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) ,BMinBundleNo = isnull((select RIGHT(MIN(subMat1.lot), 4) from matltran_mst subMat1 where Left(right(subMat1.lot,4),1) = 'B' and matl1.item = subMat1.item and subMat1.trans_type = 'F' and matl1.ref_num = subMat1.ref_num and matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) ,'-') ,BMaxBundleNo = isnull((select RIGHT(MAX(subMat1.lot), 4) from matltran_mst subMat1 where Left(right(subMat1.lot,4),1) = 'B' and matl1.item = subMat1.item and subMat1.trans_type = 'F'and matl1.ref_num = subMat1.ref_num and matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-') ,sumBBundle = isnull((select sum(CAST(isnull(subMat1.qty,0) AS INT) ) from matltran_mst subMat1 where Left(right(subMat1.lot,4),1) = 'B' and matl1.item = subMat1.item and subMat1.trans_type = 'F'and matl1.ref_num = subMat1.ref_num and matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-') ,RMinBundleNo = isnull((select RIGHT(MIN(subMat1.lot), 4) from matltran_mst subMat1 where Left(right(subMat1.lot,4),1) = 'R' and matl1.item = subMat1.item and subMat1.trans_type = 'F' and matl1.ref_num = subMat1.ref_num and matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) ,'-') ,RMaxBundleNo = isnull((select RIGHT(MAX(subMat1.lot), 4) from matltran_mst subMat1 where Left(right(subMat1.lot,4),1) = 'R' and matl1.item = subMat1.item and subMat1.trans_type = 'F'and matl1.ref_num = subMat1.ref_num and matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-') ,((sum(matl1.qty))%item_mst.uf_pack) AS PCSnoBundle, matl1.wc, wc_mst.description AS WCname, item_mst.description AS item_name, isnull(job_mst.cust_num, '-') AS cust_num, custNAME = (SELECT top 1 (addr.name) FROM custaddr_mst addr WHERE addr.cust_seq = 0 AND addr.cust_num = job_mst.cust_num ), job_mst.Uf_sts_job AS sts, job_mst.Uf_refno AS REF, sum(matl1.qty), isnull(u_m_conv_mst.conv_factor, item_mst.Uf_pack) AS uf_pack, isnull(custaddr_mst.city, '') AS city FROM matltran_mst matl1 LEFT JOIN wc_mst ON matl1.wc = wc_mst.wc LEFT JOIN item_mst ON matl1.item = item_mst.item LEFT JOIN job_mst ON matl1.ref_num = job_mst.job AND matl1.item = job_mst.item LEFT JOIN sts_remark_line_report ON matl1.ref_num = sts_remark_line_report.job LEFT JOIN u_m_conv_mst ON job_mst.cust_num = u_m_conv_mst.vend_num AND u_m_conv_mst.item = matl1.item LEFT JOIN co_mst ON job_mst.ord_num = co_mst.co_num AND co_mst.cust_num = job_mst.cust_num LEFT JOIN custaddr_mst ON co_mst.cust_num = custaddr_mst.cust_num AND co_mst.cust_seq = custaddr_mst.cust_seq WHERE matl1.trans_type = 'F' AND wc_mst.wc  in ('P7PK01', 'P7PK02', 'P7PK03', 'P7PK04', 'P7PKP1', 'P7PKP2', 'W7PK01', 'W7PKP1', 'W7PKP2', 'P7PK00') ";

        $query = $query . $ref_num;
        $query = $query . $criteria_date;
        $query = $query . $item;
        $query = $query . $w_c;
        $query = $query . " GROUP BY item_mst.Uf_NPS, item_mst.Uf_spec, item_mst.Uf_Grade, item_mst.Uf_Schedule, item_mst.Uf_length, item_mst.Uf_TypeEnd, sts_remark_line_report.remark, matl1.ref_num, matl1.wc, matl1.item, item_mst.description, item_mst.Uf_pack, wc_mst.description, job_mst.cust_num, job_mst.Uf_sts_job, job_mst.Uf_refno, u_m_conv_mst.conv_factor, custaddr_mst.city HAVING sum(matl1.qty) <> 0 "
        ;

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetJobPacking2($txtItem, $txtref_num, $txtw_c, $wc_group_query) {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;
        $w_c = $this->_w_c;

        $query = " EXEC STS_JOB_REPORT_DIARY_TEST "
                . " @start_date  = N'$start_date',"
                . " @end_date = N'$end_date',"
                . " @wc_group_query  = '$wc_group_query' ,"
                . " @item = '$txtItem' ,"
                . " @ref_num   = '$txtref_num',"
                . " @wc   ='$txtw_c'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    
    function GetJobPacking_no_job($txtItem, $txtref_num, $txtw_c, $wc_group_query) {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;
        $w_c = $this->_w_c;

        $query = " STS_JOB_REPORT_DIARY "
                . " @start_date  = N'$start_date',"
                . " @end_date = N'$end_date',"
                . " @wc_group_query  = '$wc_group_query' ,"
                . " @item = '$txtItem' ,"
                . " @ref_num   = '$txtref_num',"
                . " @wc   ='$txtw_c'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_JOB_REPORT_DIARY_SUB_QUERY($txtItem, $txtref_num, $txtw_c, $wc_group_query) {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;
        $w_c = $this->_w_c;

        $query = " STS_JOB_REPORT_DIARY_SUB_QUERY "
                . " @start_date  = N'$start_date',"
                . " @end_date = N'$end_date',"
                . " @wc_group_query  = '$wc_group_query' ,"
                . " @item = '$txtItem' ,"
                . " @ref_num   = '$txtref_num',"
                . " @wc   ='$txtw_c'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function API_STS_Stock_mthly($month, $year) {
        $query = " EXEC STS_Stock_mthly @month = N'$month', @year = N'$year' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetJobMatltrans($txtFromDate, $txtToDate, $txtItem, $txtlot, $txttrans_type, $txtref_type, $txtw_c, $txtloc, $txtsts_no) {


        $query = " EXEC STS_JOB_productionMatltrans "
                . " @start_date  = N'$txtFromDate',"
                . " @end_date = N'$txtToDate',"
                . " @item = '$txtItem' ,"
                . " @lot = '$txtlot' ,"
                . " @trans_type = '$txttrans_type' ,"
                . " @ref_type = '$txtref_type' ,"
                . " @wc   ='$txtw_c',"
                . " @loc   ='$txtloc',"
                . " @sts_no   ='$txtsts_no'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        echo $query;
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function StampingReport($txtItem, $txtref_num, $txtw_c, $wc_group_query) {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;
        $w_c = $this->_w_c;

        $query = " EXEC STS_JOB_REPORT_DIARY_STAMPING "
                . " @start_date  = N'$start_date',"
                . " @end_date = N'$end_date',"
                . " @wc_group_query  = '$wc_group_query' ,"
                . " @item = '$txtItem' ,"
                . " @ref_num   = '$txtref_num',"
                . " @wc   ='$txtw_c'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function MovingProductReport($txtItem, $txtref_num, $txtw_c, $wc_group_query) {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;
        $w_c = $this->_w_c;

        $query = " EXEC STS_JOB_REPORT_MOVING "
                . " @start_date  = N'$start_date',"
                . " @end_date = N'$end_date'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function RPT_JOBPACKINGLOT() {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $w_c = $this->_w_c;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;


        $start_date = str_replace("T", " ", $start_date);
        $start_date = str_replace("Z", "", $start_date);

        $end_date = str_replace("T", " ", $end_date);
        $end_date = str_replace("Z", "", $end_date);

        if ($ref_num) {
            $ref_num = " AND  matl1.ref_num like '%$ref_num%'  ";
        }
        if ($job_type) {
            $job_type = " AND  matl1.ref_num like '%$job_type%'  ";
        }

        $criteria_date = " ";
        if (($start_date) && ($end_date)) {
            $criteria_date = " AND ( matl1.Createdate BETWEEN '$start_date' AND '$end_date' ) ";
        }
        $item = " ";
        if ($this->_item) {
            $item = " AND  matl1.item like '%$this->_item%'  ";
        }
        $w_c = " ";
        if ($this->_w_c) {
            $w_c = " AND  ( matl1.wc like '$this->_w_c%' or  wc_mst.description like '$this->_w_c%')  ";
        }

        $query = "";
        $query = $query . " select lot FROM lot_mst where 1=1 ";

//        $query = $query . $ref_num;
//        $query = $query . $criteria_date;
//        $query = $query . $item;
//        $query = $query . $w_c;
//        $query = $query . "  "
        ;

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $query;
    }

    function GetJobPackingDialy() {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $w_c = $this->_w_c;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;

        if ($ref_num) {
            $ref_num = " AND  matl1.ref_num like '%$ref_num%'  ";
//            $ref_num = " AND  matl1.ref_num like '%FERGUS0018%'  ";
        }
        if ($job_type) {
            $job_type = " AND  matl1.ref_num like '%$job_type%'  ";
        }

        $criteria_date = " ";
        if (($start_date) && ($end_date)) {
            $criteria_date = " AND ( matl1.Createdate BETWEEN '$start_date' AND '$end_date' ) ";
        }
        $item = " ";
        if ($this->_item) {
            $item = " AND  matl1.item like '%$this->_item%'  ";
        }
        $w_c = " ";
        if ($this->_w_c) {
            $w_c = " AND  ( matl1.wc like '$this->_w_c%' or  wc_mst.description like '$this->_w_c%')  ";
        }

        $query = "";
        $query = $query . " select isnull(custaddr_mst.city,'-') as city,item_mst.description, mv_bc_tag.Uf_pack as no,job_mst.Uf_refno ,matl1.item,matl1.ref_num, matl1.lot,mv_bc_tag.uf_pack,mv_bc_tag.qty1,item_mst.uf_pack as uf_pack_s,mv_bc_tag.uf_act_weight,mv_bc_tag.sts_no,matl1.Createdate from matltran_mst matl1 Left join wc_mst on matl1.wc = wc_mst.wc left join item_mst on matl1.item = item_mst.item left join job_mst on matl1.ref_num = job_mst.job and matl1.item = job_mst.item left join sts_remark_line_report on matl1.ref_num = sts_remark_line_report.job left join u_m_conv_mst on job_mst.cust_num = u_m_conv_mst.vend_num and u_m_conv_mst.item = matl1.item left join co_mst on job_mst.ord_num = co_mst.co_num and co_mst.cust_num = job_mst.cust_num left join custaddr_mst on co_mst.cust_num = custaddr_mst.cust_num and co_mst.cust_seq = custaddr_mst.cust_seq left join mv_bc_tag on mv_bc_tag.lot = matl1.lot where matl1.trans_type = 'F' and wc_mst.wc in ('P7PK01','P7PK02','P7PK03' ,'P7PK04','P7PKP1','P7PKP2','W7PK01','W7PKP1','W7PKP2','P7PK00') ";
        $query = $query . $ref_num;
        $query = $query . $criteria_date;
        $query = $query . $item;
        $query = $query . $w_c;
        $query = $query . " order by job_mst.Uf_refno , mv_bc_tag.uf_pack "
                . "  ";
//        $query = $query . $ref_num;
//        $query = $query . $criteria_date;
//        $query = $query . $item;
//        $query = $query . $w_c;
//        $query = $query . " AND  (matl1.ref_num like '%FERGUS0018%' or matl1.ref_num like '%PK19010200%' or matl1.ref_num like '%US09140012%' or matl1.ref_num like  'QT09270002'  ) order by  job_mst.Uf_refno , mv_bc_tag.uf_pack"
//                . ", convert(date, matl1.createdate) "
//                . "";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function ReportProductionDaily() {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $w_c = $this->_w_c;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;

        if ($ref_num) {
            $ref_num = " AND  matl1.ref_num like '%$ref_num%'  ";
//            $ref_num = " AND  matl1.ref_num like '%FERGUS0018%'  ";
        }
        if ($job_type) {
            $job_type = " AND  matl1.ref_num like '%$job_type%'  ";
        }

        $criteria_date = " ";
        if (($start_date) && ($end_date)) {
            $criteria_date = " AND ( matl1.Createdate BETWEEN '$start_date' AND '$end_date' ) ";
        }
        $item = " ";
        if ($this->_item) {
            $item = " AND  matl1.item like '%$this->_item%'  ";
        }
        $w_c = " ";
        if ($this->_w_c) {
            $w_c = " AND  ( matl1.wc like '$this->_w_c%' or  wc_mst.description like '$this->_w_c%')  ";
        }

        $query = "";
        $query = $query . " SELECT item_mst.Uf_NPS, item_mst.Uf_spec, isnull(item_mst.Uf_Grade, '') AS Uf_Grade, isnull(item_mst.Uf_Schedule, '') AS Uf_Schedule, item_mst.Uf_length, item_mst.Uf_TypeEnd, isnull(sts_remark_line_report.remark, '') AS remark, matl1.ref_num, matl1.item, item_mst.description AS ItemDesc, format(sum(matl1.qty), 'N0') AS Total_PCS, AllLot = stuff( (SELECT '; ' + right(s1.lot, 4)+'(' +convert(nvarchar,convert(int,s1.qty)) FROM matltran_mst S1 WHERE S1.ref_num = matl1.ref_num AND S1.item = matl1.item AND S1.trans_type = 'F' AND S1.qty <> 0 AND (s1.Createdate BETWEEN '$start_date' AND '$end_date') AND (right(S1.loc, 1) not in ('B', 'C')) GROUP BY S1.lot, S1.qty ORDER BY S1.lot ASC FOR XML PATH ('')) , 1, 1, ''), qty = stuff( (SELECT '; ' + convert(nvarchar,format(sum(S1.qty), 'N0')) FROM matltran_mst S1 WHERE S1.ref_num = matl1.ref_num AND S1.item = matl1.item AND S1.trans_type = 'F' AND S1.qty <> 0 AND (s1.Createdate BETWEEN '$start_date' AND '$end_date') GROUP BY S1.qty FOR XML PATH ('')) , 1, 1, ''), bundle = stuff( (SELECT '; ' + convert(nvarchar,count(S2.qty)) FROM matltran_mst S2 WHERE S2.ref_num = matl1.ref_num AND S2.item = matl1.item AND S2.trans_type = 'F' AND S2.qty <> 0 AND (s2.Createdate BETWEEN '$start_date' AND '$end_date') GROUP BY S2.qty FOR XML PATH ('')), 1, 1, ''), pcs_qty = isnull(stuff( (SELECT '; ' + convert(nvarchar,format(S3.qty, 'N0')) FROM matltran_mst S3 WHERE S3.ref_num = matl1.ref_num AND S3.item = matl1.item AND S3.trans_type = 'F' AND S3.qty <> 0 AND (s3.Createdate BETWEEN '$start_date' AND '$end_date') GROUP BY S3.qty FOR XML PATH ('')), 1, 1, ''), 0), MinBundleNo = (SELECT CAST(CAST(RIGHT(MIN(subMat1.lot), 4) AS INT) AS varchar) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) <= '9' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')), MaxBundleNo = (SELECT CAST(CAST(RIGHT(MAX(subMat1.lot), 4) AS INT) AS varchar) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) <= '9' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')), BMinBundleNo = isnull( (SELECT RIGHT(MIN(subMat1.lot), 4) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'B' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) ,'-'), BMaxBundleNo = isnull( (SELECT RIGHT(MAX(subMat1.lot), 4) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'B' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-'), sumBBundle = isnull( (SELECT sum(CAST(isnull(subMat1.qty, 0) AS INT)) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'B' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-'), RMinBundleNo = isnull( (SELECT RIGHT(MIN(subMat1.lot), 4) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'R' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) ,'-'), RMaxBundleNo = isnull( (SELECT RIGHT(MAX(subMat1.lot), 4) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'R' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-'), ((sum(matl1.qty))%item_mst.uf_pack) AS PCSnoBundle, matl1.wc, wc_mst.description AS WCname, item_mst.description AS item_name, isnull(job_mst.cust_num, '-') AS cust_num, custNAME = (SELECT top 1 (addr.name) FROM custaddr_mst addr WHERE addr.cust_seq = 0 AND addr.cust_num = job_mst.cust_num ), job_mst.Uf_sts_job AS sts, job_mst.Uf_refno AS REF, sum(matl1.qty), isnull(u_m_conv_mst.conv_factor, item_mst.Uf_pack) AS uf_pack, isnull(custaddr_mst.city, '') AS city FROM matltran_mst matl1 LEFT JOIN wc_mst ON matl1.wc = wc_mst.wc LEFT JOIN item_mst ON matl1.item = item_mst.item LEFT JOIN job_mst ON matl1.ref_num = job_mst.job AND matl1.item = job_mst.item LEFT JOIN sts_remark_line_report ON matl1.ref_num = sts_remark_line_report.job LEFT JOIN u_m_conv_mst ON job_mst.cust_num = u_m_conv_mst.vend_num AND u_m_conv_mst.item = matl1.item LEFT JOIN co_mst ON job_mst.ord_num = co_mst.co_num AND co_mst.cust_num = job_mst.cust_num LEFT JOIN custaddr_mst ON co_mst.cust_num = custaddr_mst.cust_num AND co_mst.cust_seq = custaddr_mst.cust_seq WHERE matl1.trans_type = 'F' AND wc_mst.wc not in ('P7PK01', 'P7PK02', 'P7PK03', 'P7PK04', 'P7PKP1', 'P7PKP2', 'W7PK01', 'W7PKP1', 'W7PKP2', 'P7PK00')";
        $query = $query . $ref_num;
        $query = $query . $criteria_date;
        $query = $query . $item;
        $query = $query . $w_c;
        $query = $query . " GROUP BY item_mst.Uf_NPS, item_mst.Uf_spec, item_mst.Uf_Grade, item_mst.Uf_Schedule, item_mst.Uf_length, item_mst.Uf_TypeEnd, sts_remark_line_report.remark, matl1.ref_num, matl1.wc, matl1.item, item_mst.description, item_mst.Uf_pack, wc_mst.description, job_mst.cust_num, job_mst.Uf_sts_job, job_mst.Uf_refno, u_m_conv_mst.conv_factor, custaddr_mst.city HAVING sum(matl1.qty) <> 0 order by matl1.wc "
                . "  ";
//        $query = $query . $ref_num;
//        $query = $query . $criteria_date;
//        $query = $query . $item;
//        $query = $query . $w_c;
//        $query = $query . " AND  (matl1.ref_num like '%FERGUS0018%' or matl1.ref_num like '%PK19010200%' or matl1.ref_num like '%US09140012%' or matl1.ref_num like  'QT09270002'  ) order by  job_mst.Uf_refno , mv_bc_tag.uf_pack"
//                . ", convert(date, matl1.createdate) "
//                . "";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function ReportFormingDaily() {
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;
        $w_c = $this->_w_c;
        $ref_num = $this->_ref_num;
        $job_type = $this->_job_type;

        if ($ref_num) {
            $ref_num = " AND  matl1.ref_num like '%$ref_num%'  ";
//            $ref_num = " AND  matl1.ref_num like '%FERGUS0018%'  ";
        }
        if ($job_type) {
            $job_type = " AND  matl1.ref_num like '%$job_type%'  ";
        }

        $criteria_date = " ";
        if (($start_date) && ($end_date)) {
            $criteria_date = " AND ( matl1.Createdate BETWEEN '$start_date' AND '$end_date' ) ";
        }
        $item = " ";
        if ($this->_item) {
            $item = " AND  matl1.item like '%$this->_item%'  ";
        }
        $w_c = " ";
        if ($this->_w_c) {
            $w_c = " AND  ( matl1.wc like '$this->_w_c%' or  wc_mst.description like '$this->_w_c%')  ";
        }

        $query = "";
        $query = $query . " SELECT item_mst.Uf_NPS, item_mst.Uf_spec, isnull(item_mst.Uf_Grade, '') AS Uf_Grade, isnull(item_mst.Uf_Schedule, '') AS Uf_Schedule, item_mst.Uf_length, item_mst.Uf_TypeEnd, isnull(sts_remark_line_report.remark, '') AS remark, matl1.ref_num, matl1.item, item_mst.description AS ItemDesc, format(sum(matl1.qty), 'N0') AS Total_PCS, qty = stuff( (SELECT '; ' + convert(nvarchar,format(sum(S1.qty), 'N0')) FROM matltran_mst S1 WHERE S1.ref_num = matl1.ref_num AND S1.item = matl1.item AND S1.trans_type = 'F' AND S1.qty <> 0 AND (s1.Createdate BETWEEN '$start_date' AND '$end_date') AND (right(S1.loc, 1) not in ('B','C')) GROUP BY S1.qty FOR XML PATH ('')) , 1, 1, ''), bundle = stuff( (SELECT '; ' + convert(nvarchar,count(S2.qty)) FROM matltran_mst S2 WHERE S2.ref_num = matl1.ref_num AND S2.item = matl1.item AND S2.trans_type = 'F' AND S2.qty <> 0 AND (s2.Createdate BETWEEN '$start_date' AND '$end_date') AND (right(S2.loc, 1) not in ('B','C')) GROUP BY S2.qty FOR XML PATH ('')), 1, 1, ''), pcs_qty = isnull(stuff( (SELECT '; ' + convert(nvarchar,format(S3.qty, 'N0')) FROM matltran_mst S3 WHERE S3.ref_num = matl1.ref_num AND S3.item = matl1.item AND S3.trans_type = 'F' AND S3.qty <> 0 AND (s3.Createdate BETWEEN '$start_date' AND '$end_date') AND (right(S3.loc, 1) not in ('B','C')) GROUP BY S3.qty FOR XML PATH ('')), 1, 1, ''), 0), MinBundleNo = (SELECT CAST(CAST(RIGHT(MIN(subMat1.lot), 4) AS INT) AS varchar) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) <= '9' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')), MaxBundleNo = (SELECT CAST(CAST(RIGHT(MAX(subMat1.lot), 4) AS INT) AS varchar) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) <= '9' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')), BMinBundleNo = isnull( (SELECT RIGHT(MIN(subMat1.lot), 4) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'B' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) ,'-'), BMaxBundleNo = isnull( (SELECT RIGHT(MAX(subMat1.lot), 4) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'B' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-'), sumBBundle = isnull( (SELECT sum(CAST(isnull(subMat1.qty, 0) AS INT)) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'B' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-'), sumRBundle = isnull( (SELECT sum(CAST(isnull(subMat1.qty, 0) AS INT)) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'R' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-'), RMinBundleNo = isnull( (SELECT RIGHT(MIN(subMat1.lot), 4) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'R' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) ,'-'), RMaxBundleNo = isnull( (SELECT RIGHT(MAX(subMat1.lot), 4) FROM matltran_mst subMat1 WHERE Left(right(subMat1.lot, 4), 1) = 'R' AND matl1.item = subMat1.item AND subMat1.trans_type = 'F' AND matl1.ref_num = subMat1.ref_num AND matl1.wc = subMat1.wc AND (subMat1.Createdate BETWEEN '$start_date' AND '$end_date')) , '-'), ((sum(matl1.qty))%item_mst.uf_pack) AS PCSnoBundle, matl1.wc, wc_mst.description AS WCname, item_mst.description AS item_name, isnull(job_mst.cust_num, '-') AS cust_num, custNAME = (SELECT top 1 (addr.name) FROM custaddr_mst addr WHERE addr.cust_seq = 0 AND addr.cust_num = job_mst.cust_num ), job_mst.Uf_sts_job AS sts, job_mst.Uf_refno AS REF, sum(matl1.qty), isnull(u_m_conv_mst.conv_factor, item_mst.Uf_pack) AS uf_pack, isnull(custaddr_mst.city, '') AS city, MatItem = isnull( (SELECT TOP 1 item FROM matltran_mst as subMat1 WHERE (subMat1.Createdate BETWEEN '$start_date' AND '$end_date') and trans_type = 'I' AND matl1.ref_num = subMat1.ref_num group by item ) ,'-') , totalMatUsed = isnull( (SELECT count(item) FROM matltran_mst as subMat1 WHERE subMat1.Createdate BETWEEN '$start_date' AND '$end_date' and subMat1.trans_type = 'I' AND matl1.ref_num = subMat1.ref_num AND matl1.item = subMat1.item group by subMat1.ref_num ) ,'-') /* select count(item),matltran_mst.ref_num,item FROM matltran_mst WHERE Createdate BETWEEN '2020-10-01 08:00:00' AND '2020-10-02 17:00:00' and trans_type = 'I' and ref_num like'%FM%' group by item,ref_num select * FROM matltran_mst WHERE Createdate BETWEEN '2020-10-01 08:00:00' AND '2020-10-02 17:00:00' and trans_type = 'I' and ref_num like'%FM20090071%' */ FROM matltran_mst matl1 LEFT JOIN wc_mst ON matl1.wc = wc_mst.wc LEFT JOIN item_mst ON matl1.item = item_mst.item LEFT JOIN job_mst ON matl1.ref_num = job_mst.job AND matl1.item = job_mst.item LEFT JOIN sts_remark_line_report ON matl1.ref_num = sts_remark_line_report.job LEFT JOIN u_m_conv_mst ON job_mst.cust_num = u_m_conv_mst.vend_num AND u_m_conv_mst.item = matl1.item LEFT JOIN co_mst ON job_mst.ord_num = co_mst.co_num AND co_mst.cust_num = job_mst.cust_num LEFT JOIN custaddr_mst ON co_mst.cust_num = custaddr_mst.cust_num AND co_mst.cust_seq = custaddr_mst.cust_seq WHERE matl1.trans_type = 'F' AND wc_mst.wc not in ('P7PK01', 'P7PK02', 'P7PK03', 'P7PK04', 'P7PKP1', 'P7PKP2', 'W7PK01', 'W7PKP1', 'W7PKP2', 'P7PK00') AND (matl1.Createdate BETWEEN '$start_date' AND '$end_date')  ";
        $query = $query . $ref_num;
        $query = $query . $criteria_date;
        $query = $query . $item;
        $query = $query . $w_c;
        $query = $query . " AND (job_mst.job like '%FM%') GROUP BY item_mst.Uf_NPS, item_mst.Uf_spec, item_mst.Uf_Grade, item_mst.Uf_Schedule, item_mst.Uf_length, item_mst.Uf_TypeEnd, sts_remark_line_report.remark, matl1.ref_num, matl1.wc, matl1.item, item_mst.description, item_mst.Uf_pack, wc_mst.description, job_mst.cust_num, job_mst.Uf_sts_job, job_mst.Uf_refno, u_m_conv_mst.conv_factor, custaddr_mst.city HAVING sum(matl1.qty) <> 0 order by matl1.wc "
                . "  ";
//        $query = $query . $ref_num;
//        $query = $query . $criteria_date;
//        $query = $query . $item;
//        $query = $query . $w_c;
//        $query = $query . " AND  (matl1.ref_num like '%FERGUS0018%' or matl1.ref_num like '%PK19010200%' or matl1.ref_num like '%US09140012%' or matl1.ref_num like  'QT09270002'  ) order by  job_mst.Uf_refno , mv_bc_tag.uf_pack"
//                . ", convert(date, matl1.createdate) "
//                . "";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetUMList() {
        $query = "select * from u_m_mst ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetTranstypeList() {
        $query = "select * from trans_type_mst ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function job_id_list() {
        $query = "select top 5000 job,item FROM job_mst order by job_date desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function loc_list() {
        $query = "select loc,description FROM location_mst order by loc asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function fill_job($job) {
        $query = "select job,item FROM job_mst where job = '$job' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function tag_scan($tag_id) {
        $query = " select  MV_Bc_Tag.*,isnull(MV_Bc_Tag.qty2,1) as qty2,item_mst.description FROM MV_Bc_Tag LEFT JOIN item_mst  on MV_Bc_Tag.item = item_mst.item"
                . " where MV_Bc_Tag.id = '$tag_id' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function process_job_receipt($job, $suffix, $item, $operNum, $qty, $qty2, $lot, $loc, $transDate, $tag_id,$user) {

        $time = date("Y-m-d H:i:s");

        $query = " EXEC RC_STSJobReceiptProcessSp @SJob='$job',"
                . " @SSuffix  = $suffix,"
                . " @SItem ='$item',"
                . " @SOperNum  = $operNum,"
                . " @SQty = $qty,"
                . " @SQty2   = $qty2,"
                . " @SLot   ='$lot',"
                . " @SLoc ='$loc',"
                . " @STransDate = '$time',"
                . " @TagId = '$tag_id',"
                . " @UserName = 'admin',"
                . " @msg = NULL,"
                . " @UpStat =  NULL";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        
        // delay 1s
       sleep(1);
    
        $qs = "update matltran_mst 
  set createdby = '$user', UpdatedBy = '$user'
  where trans_type = 'F' and ref_type = 'J' 
     and uf_qty2 is not null
     and convert(date,createdate) = convert(date,'$time')
     and DATEDIFF(second, createdate, '$time') < 15
     and item = '$item' 
     and ref_num = '$job' and ref_release = '$operNum'
     and qty = $qty
     and loc = '$loc'
     and lot = '$lot'";

        $cSql->SqlQuery($this->StrConn, $qs);

        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function check_process_job_receipt($lot) {
        $query = " select * FROM lot_loc_mst inner join lot_mst ON lot_loc_mst.lot = lot_mst.lot where lot_loc_mst.lot = '$lot'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDataJob($job) {


        //$sql2 = "select CONVERT(DECIMAL(10,0), SUM( mat.qty)) as qty_mat from matltran_mst mat WHERE   1=1 AND  ref_num like '$jobm[0]'  AND  trans_type like '%I%'  ";

        $query = "  select jobmatl_mst.item,job_mst.job,job_mst.suffix,jobroute_mst.oper_num,job_mst.item,"
                . " job_mst.description,job_mst.qty_released,job_mst.qty_complete,Uf_sts_job,"
                . " stat,jobroute_mst.wc,wc_mst.description as WcDesc,"
                . " sum(isnull(case when units='U' then (job_mst.qty_released * jobmatl_mst.matl_qty) else jobmatl_mst.matl_qty end,0)) as QtyRem,"
                . " sum(isnull(qty_issued,0)) as QtyIss "
                . " FROM job_mst "
                . " LEFT JOIN jobroute_mst on job_mst.job = jobroute_mst.job "
                . " LEFT JOIN wc_mst on jobroute_mst.wc = wc_mst.wc "
                . " LEFT JOIN jobmatl_mst on jobmatl_mst.job = job_mst.job and job_mst.suffix=jobmatl_mst.suffix and job_mst.type='J'"
                . " where job_mst.job = '$job' "
                . " and ("
                . " job_mst.item like '%WC%' or  job_mst.item like '%WR%' or job_mst.item like '%FR%' or job_mst.item like '%FC%' or "
                . " jobmatl_mst.item like '%WC%' or  jobmatl_mst.item like '%WR%' or jobmatl_mst.item like '%FR%' or jobmatl_mst.item like '%FC%' ) ";
        $query = $query . " group by jobmatl_mst.item,job_mst.job,job_mst.suffix,jobroute_mst.oper_num,job_mst.item, job_mst.description,job_mst.qty_released,job_mst.qty_complete,Uf_sts_job, stat,jobroute_mst.wc,wc_mst.description";
//        $query =  " DECLARE @return_value int, @item nvarchar(40), @Desc nvarchar(80), @QtyOrd decimal(25, 3), @QtyRcvd decimal(25, 3), @DocRef nvarchar(50), @st nvarchar(1), @wc nvarchar(20), @Res nvarchar(30), @wcDesc nvarchar(80), @code nvarchar(50) "
//                . " EXEC @return_value = [dbo].[STS_MVmGetJobSp] @job = N'A531912001', @Suffix = N'0', @Op = 10, @item = @item OUTPUT, @Desc = @Desc OUTPUT, @QtyOrd = @QtyOrd OUTPUT, @QtyRcvd = @QtyRcvd OUTPUT, @DocRef = @DocRef OUTPUT, @st = @st OUTPUT, @wc = @wc OUTPUT, @Res = @Res OUTPUT, @wcDesc = @wcDesc OUTPUT, @code = @code OUTPUT "
//                . " SELECT @item as N'@item', @Desc as N'@Desc', @QtyOrd as N'@QtyOrd', @QtyRcvd as N'@QtyRcvd', @DocRef as N'@DocRef', @st as N'@st', @wc as N'@wc', @Res as N'@Res', @wcDesc as N'@wcDesc', @code as N'@code' ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);

//        echo '<pre>';
//        print_r($rs0);
//        echo '</pre>';

        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetJob($job) {
        $query = "select job.item, jobname=job.description, QtyOrd=job.qty_released, QtyRcvd=isnull(job.qty_complete,0),DocRef=isnull(job.Uf_sts_job,'')
  ,jobr.wc, wcname=wc.description--, wcg.rgid
  , rgid = STUFF((SELECT ',' + w.rgid
            FROM wcresourcegroup_mst w
   where jobr.wc = w.wc
            FOR XML PATH('')
            ), 1, 1, '')
from job_mst job 
  inner join jobroute_mst jobr on job.job = jobr.job and oper_num = convert(int,right('$job',2))
  inner join wc_mst wc on jobr.wc = wc.wc
  inner join wcresourcegroup_mst wcg on wcg.wc = wc.wc
where job.stat = 'R' and job.type ='J'
  and job.job = left('$job',10)
group by job.item, job.description, job.qty_released, isnull(job.qty_complete,0),isnull(job.Uf_sts_job,'')
  ,jobr.wc, wc.description";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetTag($job, $tag) {
        $query = "select tag_status, mv.item, itemName = item.description, lt.loc, mv.lot, mv.qty1, UM = isnull(mv.um1,item.u_m)
  , qty2 = isnull(mv.qty2,0), UM2 = isnull(mv.um2,''), DocRef=isnull(mv.uf_sts_job,'')
  , QtyRem=sum(isnull(case when units='U' then (job.qty_released * jm.matl_qty) else jm.matl_qty end,0))
  , QtyIss=sum(isnull(qty_issued,0))
from Mv_Bc_Tag mv
  inner join item_mst as item on item.item=mv.item
  inner join lot_loc_mst as lt on lt.item=mv.item
         and lt.lot=mv.lot
  inner join jobmatl_mst jm on mv.item = jm.item and jm.ref_type in ('J', 'I')
  inner join job_mst as job on job.job=jm.job
           and job.suffix=jm.suffix and job.type='J'
where mv.id = '$tag' and mv.active = 1 and mv.ship_stat = 0
   and jm.job = left('$job',10)
   and jm.oper_num = convert(int,right('$job',2))
   and jm.suffix = convert(smallint,substring('$job',12,1))
group by tag_status, mv.item, item.description, lt.loc, mv.lot, mv.qty1,  isnull(mv.um1,item.u_m)
  ,isnull(mv.qty2,0), isnull(mv.um2,''), isnull(mv.uf_sts_job,'')";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function MaterialProcess($job, $suffix, $item, $operNum, $qty1, $qty2, $lot, $loc, $doc, $user) {

        $date = date("Y-m-d H:i:s");

        $query = " EXEC [dbo].[RC_STSJobMatlTransactionSp] @Job = '$job',"
                . " @Suffix = $suffix,"
                . " @OperNum = $operNum,"
                . " @Item = '$item',"
                . " @Loc = '$loc',"
                . " @Lot = '$lot',"
                . " @TransDate = '$date',"
                . " @Qty = $qty1,"
                . " @DocumentNum = '$doc',"
                . " @Qty2 = $qty2,"
                . " @msg = NULL";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);

        sleep(1);

        $qs = "update matltran_mst 
  set createdby = '$user', updatedby = '$user'
  where trans_type = 'I' and ref_type = 'J' 
     and uf_qty2 is not null
     and convert(date,createdate) = convert(date,'$date')
     and DATEDIFF(second, createdate, '$date') < 15
     and item = '$item' 
     and ref_num = '$job' and ref_release = $operNum
     and qty = $qty1
     and loc = '$loc'
     and lot = '$lot'";

        $cSql->SqlQuery($this->StrConn, $qs);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function Getmatltran_mst($job) {
        $query = "select CONVERT(DECIMAL(10,0), SUM( mat.qty)) as qty_mat from matltran_mst mat "
                . "WHERE   1=1 AND  ref_num like '$job'  AND  trans_type like '%I%'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDataTag($id, $job) {

        $query = " select jobmatl_mst.item  ,Mv_Bc_Tag.id,MV_BC_TAG.suffix,MV_BC_TAG.item,MV_BC_TAG.job,MV_BC_TAG.lot,wc,um1,um2,qty1,qty2,loc,flag_used_mat "
                . " FROM jobmatl_mst RIGHT JOIN MV_BC_TAG ON jobmatl_mst.item= Mv_Bc_Tag.item left join  lot_loc_mst ON MV_BC_TAG.lot = lot_loc_mst.lot where 1=1  "
                . " and (select item FROM MV_BC_TAG where id = '$id' ) = jobmatl_mst.item "
                . " and Mv_Bc_Tag.id = '$id'  and jobmatl_mst.job = '$job'   and flag_used_mat is null and loc is not null ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function IssueProcess($IdArr, $ItemArr, $LotArr, $LocArr, $Wc, $Job) {

        $loop_count = count($ItemArr);

        for ($i = 0; $i < $loop_count; $i++) {
            $col_id = $IdArr[$i];
            $col_item = $ItemArr[$i];
            $col_lot = $LotArr[$i];
            $col_loc = $LocArr[$i];

            $query_insert = "INSERT INTO STS_JobMat_temp (MatTagId,Lot ,Loc ,WC ,Job ,flag_process) VALUES ('$col_id','$col_lot','$col_loc','$Wc','$Job','0')";
            $cSql_insert = new SqlSrv();
            $cSql_insert->SqlQuery($this->StrConn, $query_insert);

//            $query = "EXEC [dbo].[Test_SP_Jobmat2] @pMatTagId = N'$col_id' ";
//                    . "@wLot = N'$col_lot',"
//                    . "@wLoc = N'$col_loc', "
//                    . "@wWC ='$Wc',"
//                    . "@wJob ='$Job' ";
//            $cSql = new SqlSrv();
//            $cSql->SqlQuery($this->StrConn, $query);
//            
        }

        $query = "EXEC [dbo].[STS_JobMat_processing]   ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return 1;
    }

    function GetBarType($barcode) {
        $query = "  DECLARE @Type nvarchar(1) "
                . " EXEC [dbo].[STS_MVmGetTypeSp] @vText = N'$barcode',"
                . " @Type = @Type OUTPUT SELECT	@Type as N'@Type'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0[0][""];
    }

    function QtyItemLocation($item) {
        $query = "Select item,loc,qty_on_hand FROM  itemloc_mst where item = '$item' and qty_on_hand <> 0 ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function QtyItemWarehouse($item) {
        $query = "select  qty_on_hand from itemwhse_mst where item = '$item' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function QtyLotLoc($item, $loc) {
        $query = "select * FROM lot_loc_mst where item = '$item'  and loc = '$loc' and qty_on_hand <> 0 ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function QtySumMattrans($item) {
        $query = "select sum(qty) as qty_on_hand FROM matltran_mst  where item = '$item'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function QtySumMattransByLoc($item, $loc) {
        $query = "  select lot,trans_type_mst.trans_description as trans_type,trans_date,qty "
                . "FROM matltran_mst "
                . " LEFT JOIN trans_type_mst ON trans_type_mst.trans_type = matltran_mst.trans_type   "
                . " where item = '$item' and loc = '$loc' and qty <> 0 order by trans_date desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function QtyOnHand($item) {
        $query = " select itemwhse_mst.item, "
                . "(select sum(qty) FROM matltran_mst where matltran_mst.item = itemwhse_mst.item and loc is not null) as sum_matlrans,"
                . " itemwhse_mst.qty_on_hand as itemloc_mst_qty_on_hand,"
                . " (select sum(qty_on_hand) FROM itemloc_mst where itemloc_mst.item = itemwhse_mst.item) as sum_itemloc,"
                . " (select sum(qty_on_hand) FROM lot_loc_mst where lot_loc_mst.item = itemwhse_mst.item) as sum_lot_loc "
                . " FROM itemwhse_mst "
                . " INNER JOIN itemloc_mst ON itemloc_mst.item = itemwhse_mst.item "
                . " where 1=1 "
                . " and itemwhse_mst.item like '%$item%'"
                . " and ((select sum(qty) FROM matltran_mst where matltran_mst.item = itemwhse_mst.item and loc is not null) <> 0) "
                . " group by itemwhse_mst.item,itemwhse_mst.qty_on_hand ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function InsertSts_po_qc($po_QC) {



        for ($countpo_QC = 0; $countpo_QC < count($po_QC); $countpo_QC++) {
            $array_col = array_keys($po_QC[$countpo_QC]);
            $insert_col_query = "";
            for ($i = 0; $i < count($array_col); $i++) {
                $column_name = $array_col[$i];
                ($column_name == "user") ? $column_name = "users" : $column_name = $column_name;
                $insert_col_query = $insert_col_query . $column_name;
                if ($i < count($array_col) - 1) {
                    $insert_col_query = $insert_col_query . ",";
                }
            }
            $array_val = array_values($po_QC[$countpo_QC]);
            $insert_val_query = "";
            for ($i = 0; $i < count($array_val); $i++) {
                $column_val = $array_val[$i];
                $insert_val_query = $insert_val_query . "'" . $column_val . "'";
                if ($i < count($array_val) - 1) {
                    $insert_val_query = $insert_val_query . ",";
                }
            }

            $sno = $po_QC[$countpo_QC]["sno"];
            $querychk = "  select * FROM STS_po_qc where sno = '$sno' ";
            $cSqlchk = new SqlSrv();
            $rs0 = $cSqlchk->SqlQuery($this->StrConn, $querychk);
            array_splice($rs0, count($rs0) - 1, 1);
//            echo $sno;
//            echo $rs0[0]["sno"];
            $sno2 = "";
            (isset($rs0[0]["sno"])) ? $sno2 = $rs0[0]["sno"] : $sno2 = "";
            if ($sno != $sno2) {
                $insert_query = "INSERT INTO STS_po_qc(" . $insert_col_query . ") VALUES (" . $insert_val_query . ") ";
                $cSql = new SqlSrv();
                $cSql->SqlQuery($this->StrConn, $insert_query);
            }
            //echo $insert_query;
        }
    }

    function BOMJobItem($item) {

        $query = " select job_mst.item as item1,jobmatl_mst.item as item2 FROM job_mst "
                . " left join jobmatl_mst ON jobmatl_mst.job = job_mst.job "
                . " where job_mst.item = '$item' "
                . " and jobmatl_mst.item is not null and job_mst.item <> jobmatl_mst.item "
                . " group by job_mst.item,jobmatl_mst.item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function BOMJobItem2($item) {

        $query = " select job_mst.item as item3,jobmatl_mst.item as item4 FROM job_mst "
                . " left join jobmatl_mst ON jobmatl_mst.job = job_mst.job "
                . " where job_mst.item = '$item' "
                . " and jobmatl_mst.item is not null and job_mst.item <> jobmatl_mst.item "
                . " group by job_mst.item,jobmatl_mst.item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function BOMJobItem3($item) {

        $query = " select job_mst.item as item5,jobmatl_mst.item as item6 FROM job_mst "
                . " left join jobmatl_mst ON jobmatl_mst.job = job_mst.job "
                . " where job_mst.item = '$item' "
                . " and jobmatl_mst.item is not null and job_mst.item <> jobmatl_mst.item "
                . " group by job_mst.item,jobmatl_mst.item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function BOMJobItem4($item) {

        $query = " select job_mst.item as item7,jobmatl_mst.item as item8 FROM job_mst "
                . " left join jobmatl_mst ON jobmatl_mst.job = job_mst.job "
                . " where job_mst.item = '$item' "
                . " and jobmatl_mst.item is not null and job_mst.item <> jobmatl_mst.item "
                . " group by job_mst.item,jobmatl_mst.item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function updateRemark($job, $remark) {

        $query_check = " select job FROM sts_remark_line_report where job = '$job' ";
        $cSql = new SqlSrv();
        $rs_check = $cSql->SqlQuery($this->StrConn, $query_check);
        if ($rs_check[0][0] == 0) {
            $query = "INSERT INTO sts_remark_line_report(report_name,job,remark) VALUES ('RPT_JOBPACKING','$job','$remark')  ";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $query);
            return "insert Success";
        } else {
            $query = "UPDATE STS_remark_line_report SET job ='$job',remark ='$remark' where job ='$job' and report_name = 'RPT_JOBPACKING' ";
            $cSql = new SqlSrv();
            $cSql->SqlQuery($this->StrConn, $query);
            return "update Success";
        }



//        
//        $query = " select * FROM ";
//        $cSql = new SqlSrv();
//        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        array_splice($rs0, count($rs0) - 1, 1);
//        return $rs0;
    }

    function workcenter() {
        $query = " select wc ,description FROM wc_mst    where description not like  '%ลบ%' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function workcenter_selection() {
        $query = " select wc AS dataSelection ,description FROM wc_mst    where description not like  '%ลบ%' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    
    function location() {
        $query = " select loc ,description FROM location_mst    ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function locationCL() {
        $query = " select loc ,description FROM location_mst where loc like '%CL%'    ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function LocationByDo($do) {
        $query = "select distinct location_mst.loc ,location_mst.description  
        from mv_bc_tag tag 
          inner join job_mst on tag.job = job_mst.job 
          inner join AIT_Preship_Do_Seq preship on job_mst.ord_num = preship.co_num and job_mst.ord_line = preship.co_line 
          inner JOIN STS_qty_move_line on STS_qty_move_line.lot = tag.lot and STS_qty_move_line.toloc like 'CL%' 
          inner JOIN STS_qty_move_hrd on STS_qty_move_hrd.doc_num = STS_qty_move_line.doc_num and STS_qty_move_hrd.loc like 'CL%' and STS_qty_move_hrd.doc_type ='Ship'
          inner join STS_list_of_do_group gr on gr.do_group_list like '%'+preship.do_num+'%' and preship.do_num <> '1'
          inner join location_mst on location_mst.loc like 'CL%' and location_mst.loc = STS_qty_move_hrd.loc
    inner join co_ship_mst cosh on cosh.do_num = preship.do_num and cosh.co_num = preship.co_num and cosh.co_line = preship.co_line
        where gr.do_group_name = '$do' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    
    function locationPTR() {
        $query = " select loc ,description FROM location_mst where loc like '%PTR%'     ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    

    function dataMatl($txtFromDate, $txtToDate, $txtItem, $txtref_num, $txtw_c) {
        $query = " select * FROM matltran_mst "
                . " Left JOIN trans_type_mst ON matltran_mst.trans_type = trans_type_mst.trans_type "
                . " where createDate between  '$txtFromDate' and '$txtToDate' "
                . " and item like '%$txtItem%' "
                . " and ref_num like '%$txtref_num%' "
                . " and wc like '%$txtw_c%'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    //-------------------------------- Start CRUD STS_forming_reason -----------------------------
    function SelectForming($startdate, $enddate, $item, $refnum, $w_c) {

        $searchDate = "";
        $searchItem = "";
        $searchRefnum = "";
        $searchw_c = "";


        if ($startdate != "" || $enddate != "") {
            $searchDate = "and (time_stopped between '$startdate' and '$enddate' ) ";
        }

        if ($item != "") {
            $searchItem = "and item = '' ";
        }

        if ($refnum != "") {
            $searchRefnum = "and ref_num = '$refnum' ";
        }

        if ($w_c != "") {
            $searchw_c = "and w_c = '$w_c' ";
        }
        $query = " select id,STS_forming_reason_description.reason_description as reason_id,times_count, STS_forming_reason_description_detail.description as reason_detail_id,convert(varchar, time_stopped, 20) as time_stopped,time_used, w_c,ref_num,convert(varchar, create_date, 20) as create_date,remark FROM STS_forming_reason LEFT JOIN STS_forming_reason_description ON STS_forming_reason.reason_id = STS_forming_reason_description.reason_id LEFT JOIN STS_forming_reason_description_detail ON STS_forming_reason.reason_detail_id = STS_forming_reason_description_detail.reason_detail_id  where 1=1  ";
        $query = $query . $searchDate;
        $query = $query . $searchItem;
        $query = $query . $searchRefnum;
        $query = $query . $searchw_c;

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function CreateForming($reason_id, $reason_detail_id, $time_stopped, $w_c, $remark, $times_count) {

        $date = date("Y-m-d");

        $select = "select top 1 w_c ,time_stopped, time_end
from STS_forming_reason
where w_c = '$w_c'and CONVERT(DATE, time_stopped) = '$date' 
order by time_stopped desc";
$cSql = new SqlSrv();
$rs = $cSql->SqlQuery($this->StrConn, $select);

      array_splice($rs, count($rs) - 1, 1);

      if ($rs[0] && $rs[0]["time_end"] == null) { 
        return http_response_code(400); 
      }

        $query = " insert into STS_forming_reason (reason_id,reason_detail_id,time_stopped,create_date,w_c,remark,times_count) "
                . "VALUES ('$reason_id','$reason_detail_id',GETDATE(),GETDATE(),'$w_c','$remark','$times_count')";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function UpdateForming($id, $reason_id, $time_stopped, $time_used, $w_c) {
        $query = "UPDATE STS_forming_reason 
SET time_end = GETDATE(), 
    time_used = DATEDIFF(MINUTE, '$time_stopped', GETDATE()) 
WHERE id = '$id' ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function DeleteForming($id, $reason_id, $time_stopped, $time_used, $create_date, $w_c) {
        $query = " Delete STS_forming_reason "
                . " where id = '$id' ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    //-------------------------------- End CRUD STS_forming_reason -----------------------------
    //-------------------------------- Start CRUD STS_forming_reason -----------------------------
    function SelectForming_reason_meter($meters_start, $meters_end, $w_c) {

        $searchDate = "";
        $searchw_c = "";


        if ($meters_start != "" || $meters_end != "") {
            $searchDate = "and (time_save between '$meters_start' and '$meters_end' ) ";
        }

        if ($w_c != "") {
            $searchw_c = "and w_c = '$w_c' ";
        }

        $query = " select id,meters_start,meters_end,w_c,meters_minute,convert(varchar, createdate, 20) as createdate,convert(varchar, time_save, 20) as time_save FROM STS_forming_reason_meter where 1=1  ";
        $query = $query . $searchDate;
        $query = $query . $searchw_c;

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function CreateForming_reason_meter($meters_start, $meters_end, $time_save, $w_c) {
        $query = " insert into STS_forming_reason_meter (meters_start,meters_end,time_save,w_c,createdate) "
                . "VALUES ('$meters_start','$meters_end','$time_save','$w_c',GETDATE())";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function UpdateForming_reason_meter($id, $meters_minute, $meters_start, $meters_end, $time_save, $w_c) {
        $query = " update STS_forming_reason_meter set "
                . " meters_minute = '$meters_minute',"
                . " meters_start = '$meters_start',"
                . " meters_end = '$meters_end',"
                . " time_save = '$time_save' "
                . " where id = '$id' ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function DeleteForming_reason_meter($id, $meters_minute, $meters_start, $meters_end, $time_save, $w_c) {
        $query = " Delete STS_forming_reason_meter "
                . " where id = '$id'  ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function getForming_last_meter($w_c) {
        $query = " select top 1 meters_end FROM STS_forming_reason_meter "
                . " where w_c = '$w_c' order by id desc ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    //-------------------------------- End CRUD STS_forming_reason -----------------------------

    function SelectForming_reason_description() {
        $query = " select * FROM STS_forming_reason_description where 1=1  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function CreateForming_reason_description($reason_description) {
        $query = " insert into STS_forming_reason_description (reason_description) "
                . "VALUES ('$reason_description')";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function UpdateForming_reason_description($reason_id, $reason_description) {
        $query = " update STS_forming_reason_description set "
                . " reason_description = '$reason_description' "
                . " where reason_id = '$reason_id' ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function DeleteForming_reason_description($reason_id, $reason_description) {
        $query = " Delete STS_forming_reason_description "
                . " where reason_id = '$reason_id' ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function SelectForming_reason_description_detail($reason_id) {
        $query = " select * FROM STS_forming_reason_description_detail where 1=1 and reason_id like '%$reason_id%' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function CreateForming_reason_description_detail($reason_description) {
        $query = " insert into STS_forming_reason_description_detail (reason_description) "
                . "VALUES ('$reason_description')";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function UpdateForming_reason_description_detail($reason_id, $reason_description) {
        $query = " update STS_forming_reason_description_detail set "
                . " reason_description = '$reason_description' "
                . " where reason_id = '$reason_id' ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function DeleteForming_reason_description_detail($reason_id, $reason_description) {
        $query = " Delete STS_forming_reason_description_detail "
                . " where reason_id = '$reason_id' ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    // BreakTimeForming

    function CreateBreakTimeForming($w_c, $startdate, $enddate, $rate) {

        $query0 = " DELETE FROM STS_forming_reason_BreakTimeForming "
                . "WHERE w_c = '$w_c' and startdate BETWEEN '$startdate' and '$enddate' ";
        $cSql0 = new SqlSrv();
        $cSql0->SqlQuery($this->StrConn, $query0);


        $query = " insert into STS_forming_reason_BreakTimeForming (w_c,startdate,enddate,rate) "
                . "VALUES ('$w_c','$startdate','$enddate','$rate')";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query0;
    }

    function SelectBreakTimeForming($meters_start, $meters_end, $w_c) {

        $searchDate = "";
        $searchw_c = "";


        if ($meters_start != "" || $meters_end != "") {
            $searchDate = "and ( STS_forming_reason_BreakTimeForming.startdate BETWEEN  '$meters_start' and '$meters_end' ) ";
        }

        if ($w_c != "") {
            $searchw_c = "and w_c = '$w_c' ";
        }

        $query = " SELECT top 1 rate FROM STS_forming_reason_BreakTimeForming where 1=1  ";
        $query = $query . $searchw_c;
        $query = $query . $searchDate;
        $query = $query . "order by id desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    
    function saveOperation($txtFromDate, $txtToDate, $operationWeight, $operationSpeed, $operationTime, $w_c){
        
        $query = " insert into STS_forming_operation (fromDate,toDate,operationWeight,operationSpeed,operationTime,w_c) "
                . "VALUES ('$txtFromDate','$txtToDate','$operationWeight','$operationSpeed','$operationTime','$w_c')";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
    }

    function Select_Operation($txtFromDate, $txtToDate, $w_c){
        
        $query = " select * from STS_forming_operation "
                . "where w_c= '$w_c' and fromDate >= '$txtFromDate' and toDate <= '$txtToDate' )";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    
    function STS_QTY_MOVE_REPORT($doc_num) {
        
        $query = " EXEC STS_QTY_MOVE_REPORT @doc_num = N'$doc_num' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_QTY_MOVE_REPORT_header($doc_num) {
        
        $query = " select STS_qty_move_hrd.*,isnull(location_mst.Uf_car_no,'') as Uf_car_no 
            FROM STS_qty_move_hrd Left join location_mst 
            on STS_qty_move_hrd.loc = location_mst.loc 
            where doc_num = '$doc_num' order by id desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function BoatNoteSelectByDoGroup($do_group_name,$loc,$boatPosition) {
        $query = "EXEC sts_boatnote_report @do_group_name = N'$do_group_name', @loc = N'$loc', @Boat_position = N'$boatPosition' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
	
	function BoatNoteSummaryByDoGroup($do_group_name,$loc) {
        $query = "select uf_by,loc,loc_description, cust_name,city,sts_PO,sum(countsts_PO) as sumSTS_PO, sum([weight]) as sumWeight, cust_po, loc_no, startDate, endDate
                  from V_STS_BoatNote_SUM
                  where do_group_name = '$do_group_name'
                  and case when '$loc' is null or '$loc' = '' then '1' else loc end = case when '$loc' is null or '$loc' = '' then '1' else '$loc' end
                  group by uf_by,loc,loc_description, cust_name,city,sts_PO,do_group_name, cust_po, loc_no, startDate, endDate
                  order by sts_PO ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
	
	function ReportTagBoatNote() {
        $query = "select distinct tag.id, preship.do_num,tag.lot,lot.loc, lot.item, lot.qty_on_hand, tag.qty1 as tagQTY
                 from lot_loc_mst lot 
                 inner join mv_bc_tag tag on lot.lot=tag.lot
                 inner join job_mst on LEFT(lot.lot,10) = job_mst.job 
                 inner join AIT_Preship_Do_Seq preship on job_mst.ord_num = preship.co_num and job_mst.ord_line = preship.co_line 
     inner join STS_list_of_do_group gr on gr.do_group_list like '%'+preship.do_num+'%'  
where lot.loc like 'cl%'  and tag.active=1 and qty1 <> 0
 and gr.id = 35
                 order by id";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function getFormingOperation($txtFromDate,$txtToDate,$txtw_c) {
        $query =  "select * from STS_forming_operation"
        . " where w_c= '$txtw_c' and fromDate >= '$txtFromDate' and toDate <= '$txtToDate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function BY_Item($Item) {
        $query =  "select convert(varchar,due_date) as Due_Date, * from
        V_STS_orderAvaiStock_item
        where item like '%$Item%'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function BY_CO($CO) {
        $query =  "select V.* , subitem.FC_cumulative ,convert(varchar,V.due_date) as Due_Date
        from V_STS_orderAvaiStock v
        inner join
        (select * from V_STS_orderAvaiStock_item_sub) subitem
        on subitem.item = v.item and subitem.co_num = v.co_num and subitem.co_line = v.co_line and convert(date,subitem.due_date) = convert(date,v.due_date)
        where   V.co_num ='$CO'
        order by  convert(date,V.due_date), V.co_num, V.co_line";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function BY_DO($DO) {
        if ($DO == ""){
            $query =  "select * ,convert(varchar, due_date) as Due_Date from V_STS_orderAvaiStock_DO
            order by co_num, co_line, do_num";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            return $rs0;
        } else {
        $query =  "select * ,convert(varchar, due_date) as Due_Date from V_STS_orderAvaiStock_DO
        where do_num ='$DO'
        order by co_num, co_line, do_num";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
        }
    }
    
    function ALL() {
        $query =  "select V.* , subitem.FC_cumulative ,convert(varchar,V.due_date) as Due_Date
        from V_STS_orderAvaiStock v
        inner join
        (select * from V_STS_orderAvaiStock_item_sub) subitem
        on subitem.item = v.item and subitem.co_num = v.co_num and subitem.co_line = v.co_line and convert(date,subitem.due_date) = convert(date,v.due_date)
        order by V.item, convert(date,V.due_date), V.co_num, V.co_line";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SelectFinishingReason() {
        $query = "select * FROM STS_finishing_reason_description where 1=1  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function CreateNewReasonFinishing($reason_id, $time_stopped, $w_c,$remark) {

        if ($w_c == "" || $w_c == null) {
            return http_response_code(401);
        }

        $date = date("Y-m-d");

        $select = "select top 1 w_c ,time_stopped, time_end
from STS_finishing_reason
where w_c = '$w_c' and CONVERT(DATE, time_stopped) = '$date'
order by time_stopped desc";
$cSql = new SqlSrv();
$rs = $cSql->SqlQuery($this->StrConn, $select);

      array_splice($rs, count($rs) - 1, 1);

      if ($rs[0] && $rs[0]["time_end"] == null) { 
        return http_response_code(400); 
      }

        $query = " insert into STS_finishing_reason (reason_id,time_stopped,w_c,remark,create_date) "
                . "VALUES ('$reason_id',GETDATE(),'$w_c','$remark',GETDATE()) ";
        $cSql = new SqlSrv();
        $result = $cSql->SqlQuery($this->StrConn, $query);
        return $result;
    }

    function UpdateReasonFinishing($id, $time_stopped) {
        $query = "UPDATE STS_finishing_reason 
SET time_end = GETDATE(), 
    down_time = DATEDIFF(MINUTE, '$time_stopped', GETDATE()) 
WHERE id = '$id' ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
        return $query;
    }

    function SelectFinishing($startdate, $enddate, $w_c, $type) {

        $searchDate = "";
        $searchw_c = "";


        if ($startdate != "" || $enddate != "") {
            $searchDate = "and (create_date between '$startdate' and '$enddate' ) ";
        }

        if ($w_c != "") {
            $searchw_c = "and w_c = '$w_c' ";
        }
        $query = "select * from STS_finishing_reason LEFT JOIN STS_finishing_reason_description ON STS_finishing_reason.reason_id = STS_finishing_reason_description.reason_id where 1=1  ";
        $query = $query . $searchDate;
        
        if ($type == 1) {
            $query = $query . $searchw_c;
        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function PlatedOrderReport($item, $wc) {
        $query = " EXEC MV_PLATED_ORDER_REPORT_web @TransactionDateStarting= NULL,"
                . " @TransactionDateEnding  = NULL,"
                . " @JobStarting = NULL,"
                . " @JobEnding = NULL,"
                . " @SuffixStarting = 0000,"
                . " @SuffixEnding = 9999,"
                . " @ItemStarting = " . ($item == null ? "NULL" : "'$item'") . ","
                . " @ItemEnding = " . ($item == null ? "NULL" : "'$item'") . ","
                . " @JobStatus = 'R',"
                . " @JobStsStart = NULL,"
                . " @JobStsEnd = NULL,"
                . " @OperStart = NULL,"
                . " @OperEnd = NULL,"
                . " @wcStarting = " . ($wc == null ? "NULL" : "'$wc'") . ","
                . " @wcEnding = " . ($wc == null ? "NULL" : "'$wc'") . "";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function PackingOrderReport($item, $wc) {
        $query = " EXEC MV_PACKING_ORDER_web @TransactionDateStarting= NULL,"
                . " @TransactionDateEnding  = NULL,"
                . " @JobStarting = NULL,"
                . " @JobEnding = NULL,"
                . " @SuffixStarting = 0000,"
                . " @SuffixEnding = 9999,"
                . " @ItemStarting = " . ($item == null ? "NULL" : "'$item'") . ","
                . " @ItemEnding = " . ($item == null ? "NULL" : "'$item'") . ","
                . " @wcStarting = " . ($wc == null ? "NULL" : "'$wc'") . ","
                . " @wcEnding = " . ($wc == null ? "NULL" : "'$wc'") . "";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function ProductionOrderReport($item,$wc) {
        $query = " EXEC MV_PRODUCTION_ORDER_REPORT_web @TransactionDateStarting= NULL,"
                . " @TransactionDateEnding  = NULL,"
                . " @JobStarting = NULL,"
                . " @JobEnding = NULL,"
                . " @SuffixStarting = 0000,"
                . " @SuffixEnding = 9999,"
                . " @ItemStarting = " . ($item == null ? "NULL" : "'$item'") . ","
                . " @ItemEnding = " . ($item == null ? "NULL" : "'$item'") . ","
                . " @JobStatus = 'R',"
                . " @wcStarting = " . ($wc == null ? "NULL" : "'$wc'") . ","
                . " @wcEnding = " . ($wc == null ? "NULL" : "'$wc'") . "";
                $query2 = " EXEC MV_PRODUCTION_ORDER_REPORT_sub @TransactionDateStarting= NULL,"
                . " @TransactionDateEnding  = NULL,"
                . " @JobStarting = NULL,"
                . " @JobEnding = NULL,"
                . " @SuffixStarting = 0000,"
                . " @SuffixEnding = 9999,"
                . " @ItemStarting = 'FC074N0060000-M2AS040F02100H',"
                . " @ItemEnding = 'FC074N0060000-M2AS040F02100H',"
                . " @JobStatus = 'FCRHS'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function HotRollReport($item,$wc) {
        $query = " EXEC MV_HOT_ROLL_SLIT_REPORT @TransactionDateStarting= NULL,"
                . " @TransactionDateEnding  = NULL,"
                . " @JobStarting = NULL,"
                . " @JobEnding = NULL,"
                . " @ItemStarting = " . ($item == null ? "NULL" : "'$item'") . ","
                . " @ItemEnding = " . ($item == null ? "NULL" : "'$item'") . ","
                . " @wcStarting = " . ($wc == null ? "NULL" : "'$wc'") . ","
                . " @wcEnding = " . ($wc == null ? "NULL" : "'$wc'") . "";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetWorkCenter() {
        $query = "select wc,[description] 
from wc_mst
where [description] not like '%กลุ่ม%' and [description] <> 'ลบ'
  and [description] not like '%ยกเลิก%'
  and [description] not like 'สถานี Forming (%)'";

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function SearchJobOrderReport($Item , $wc, $whereClause) {

        $wh = '';

        if ($Item != '') {
            $wh .= " and item = '" . $Item . "'";
        }
        if ($wc != '') {
            $wh .= " and wc = '" . $wc . "'";
        }

        if ($whereClause != '') {
            $wh .=  "". $whereClause;
        }

        $query = "select distinct job_mst.job, job_mst.stat, job_mst.item, jr.wc,jo.no,jo.Createdate
FROM            job_mst 
    inner JOIN jobroute_mst jr ON job_mst.job = jr.job 
    left JOIN STS_curr_job_order jo ON job_mst.job = jo.job
where len(rtrim(ltrim(job_mst.job))) = 10
  and job_mst.stat <> 'H' $wh 
order by job_mst.job";

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function SaveJobOrder($job, $job_order) {

        $query = "INSERT INTO STS_curr_job_order (job,no) VALUES ('$job','$job_order')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
        
    }

    function CloseJobOrder($job) {

        $query = "DELETE FROM STS_curr_job_order WHERE job = '$job'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
        
    }

    
    
}
