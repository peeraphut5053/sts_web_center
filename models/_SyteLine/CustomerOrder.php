<?php

//===================================================//
//===================================================//
//===================================================//
//=============== ItemSyteline.php      =============//
//===================================================//
//===================================================//
//===================================================//

class CustomerOrder {

    var $StrConn = "";
    public $_site_ref = "";
    public $_atYear = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function CheckQty($item) {
        $query = "EXEC [dbo].[STS_WebApp_salesIN_itemRpt] @item = '$item'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsBetweenSale($item) {
        $atYear = $this->_atYear;
        $crYear = "";
        if ($atYear != "") {
            $crYear = " AND ( year(CreateDate) = $atYear ) ";
        }

        $query = "SELECT CONVERT(varchar,order_date,103) as order_date, co_num , cust_name = name, co_line, qty_pending
FROM V_WebApp_InvItem_OutStanding 
WHERE LEFT(cust_num,2) = 'TT' AND item ='$item'";
      
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsShipping($item) {
        $atYear = $this->_atYear;
        $crYear = "";
        if ($atYear != "") {
            $crYear = " AND (year(pickup_date) = $atYear ) ";
        }
        $query = "select pickup_date,cust_name,co_num,co_line,do_num,do_line ,sum(qty_shipped) as qty_shipped  "
                . "from V_WebApp_Item_ship_route   "
                . "where qty_shipped > 0   $crYear   "
                . "AND item_code = '$item'  "
                . "AND (do_seq is null or do_seq ='' ) "
                . "AND (do_line <> '' )  "
                . "group by pickup_date,cust_name,co_num,co_line,do_num,do_line  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsShipped($item) {
        $query = "SELECT   * FROM V_WebApp_Item_ship_route  WHERE  ( do_seq <> '' )   and (loc <> '')  and    item_code = '$item'    ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetShipRoute($do_num, $co_num, $co_line) {
        $query = "SELECT   * FROM V_WebApp_Item_ship_route  WHERE do_num = '$do_num' AND co_num  ='$co_num' AND co_line = $co_line ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SP_WebApp_OrderProcessing_Overall($StartDate, $EndDate, $StartOrdNum, $EndOrdNum) {
        if ($StartDate == "") {
            $StartDate = '2010-01-01';
            $EndDate = date("Y-m-d");
        }
        $query = "SP_WebApp_OrderProcessing_Overall "
                . " @StartDate  ='$StartDate 00:00:00' ,  @EndDate  ='$EndDate 00:00:00' ";
        if ($StartOrdNum != "") {
            ($EndOrdNum == "") ? $EndOrdNum = $StartOrdNum : $EndOrdNum = $EndOrdNum;
            $query = $query . " ,@StartOrdNum = '$StartOrdNum', @EndOrdNum = '$EndOrdNum' ";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function OrderProcessing_Line($ord_num) {
        //$query = 'select top 10 * FROM MV_BC_TAG';
        $query = "select * from V_WebApp_OrderProcessing where ord_num = '$ord_num' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function OrderProcessing_Detail($ord_num, $ord_line) {
        $query = "exec SP_WebApp_OrderProcessing_Detail  @OrdNum ='EB19060001' , @OrdLine = 1";
        $query = 'select top 10 * FROM MV_BC_TAG';
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function V_WebApp_OrderProcessing($cust_po, $name, $Port) {
        $query = "select cust_name , cust_po , Port , Size , Spec , Grade , Schedule , Length , TypeEnd , PcsPerBndl , CEILING( QTY_ORDERED / PcsPerBndl ) as BndlOrdered , CEILING( qty_released / PcsPerBndl ) as BndlProduce , CEILING( qty_released / PcsPerBndl ) - CEILING( qty_complete / PcsPerBndl ) as BalanceBndl , due_date , end_cust_name , Process from V_WebApp_OrderProcessing where 1=1 "
                . " and cust_po like '%$cust_po%' and cust_name like '%$name%' and Port like '%$Port%' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_EX_order($job,$co,$stsPO,$custpo,$orderdate,$item) {
        $query = "EXEC [dbo].[STS_EX_order] 
	 @job  = '$job' ,
	 @co  = '$co',
	 @stsPO = '$stsPO',
	 @custpo = '$custpo',
     @item = '$item',
	 @orderdate = '$orderdate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_EX_order_EndUserPort($endUser,$transport,$port,$custpo,$orderdate,$item) {
        $query = "EXEC [dbo].[STS_EX_order_EndUserPort] 
	 @endUser  = '$endUser' ,
	 @transport  = '$transport',
	 @port = '$port',
	 @custpo = '$custpo',
     @item = '$item',
	 @orderdate = '$orderdate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function Customer_report_order($txtStartDate,$txtToDate) {
        $query = "EXEC	STS_CO_report @TransactionDateStarting = N'$txtStartDate',@TransactionDateEnding = N'$txtToDate',@CUSTnumStarting = N'',@CUSTnumEnding = N'' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SaveExJob($job, $status, $date) {
        $cSql = new SqlSrv();
        $query = "SELECT job FROM STS_EX_job WHERE job = '$job'";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        
        if (is_array($rs0) && count($rs0) > 1) {
            $update = "UPDATE STS_EX_job SET status = '$status', [Date] = '$date' WHERE job = '$job'";
            $cSql->SqlQuery($this->StrConn, $update);
        } else {
            $insert = "INSERT INTO STS_EX_job (job, status, [Date], CreateDate) VALUES ('$job', '$status', '$date', GETDATE())";
            $cSql->SqlQuery($this->StrConn, $insert);
        }
        return ['status' => 'success'];
    }

    function GetExBookCont() {
        $query = "SELECT endUser, port, no_cont40, no_cont45, [BULK], po_bulk, po_40, po_45 FROM dbo.STS_EX_book_cont";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SaveExBookCont($endUser, $port, $no_cont40, $no_cont45, $bulk, $po_bulk, $po_40, $po_45) {
        $no_cont40 = ($no_cont40 === '') ? null : intval($no_cont40);
        $no_cont45 = ($no_cont45 === '') ? null : intval($no_cont45);
        $bulk = ($bulk === '') ? null : intval($bulk);
        $po_bulk = ($po_bulk === '') ? null : $po_bulk;
        $po_40 = ($po_40 === '') ? null : $po_40;
        $po_45 = ($po_45 === '') ? null : $po_45;

        $query = "MERGE dbo.STS_EX_book_cont AS target
USING (SELECT ? AS endUser, ? AS port) AS source
ON target.endUser = source.endUser AND target.port = source.port
WHEN MATCHED THEN
    UPDATE SET no_cont40 = ?, no_cont45 = ?, [BULK] = ?, po_bulk = ?, po_40 = ?, po_45 = ?, updatedate = GETDATE()
WHEN NOT MATCHED THEN
    INSERT (endUser, port, no_cont40, no_cont45, [BULK], po_bulk, po_40, po_45, updatedate)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, GETDATE());";
        $params = array($endUser, $port, $no_cont40, $no_cont45, $bulk, $po_bulk, $po_40, $po_45, $endUser, $port, $no_cont40, $no_cont45, $bulk, $po_bulk, $po_40, $po_45);
        $cSql = new SqlSrv();
        $cSql->SqlQuery2($this->StrConn, $query, $params);
        return array('status' => 'success');
    }

    function GetEndUserPortCustPOBatch($keys) {
        $rows = json_decode($keys, true);
        if (!is_array($rows) || count($rows) == 0) {
            return array();
        }

        $values = array();
        $params = array();
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $endUser = isset($row['endUser']) ? trim($row['endUser']) : '';
            $port = isset($row['port']) ? trim($row['port']) : '';
            if ($endUser == '' || $port == '') {
                continue;
            }
            $values[] = "(?, ?)";
            $params[] = $endUser;
            $params[] = $port;
        }

        if (count($values) == 0) {
            return array();
        }

        $query = "SELECT DISTINCT
    K.endUser,
    K.port,
    co.co_num,
    cust_po = LTRIM(RTRIM(LEFT(co.cust_po, CHARINDEX('/', co.cust_po + '/') - 1)))
FROM (VALUES " . implode(",", $values) . ") AS K(endUser, port)
INNER JOIN custaddr_mst addr ON addr.city = K.port AND ISNULL(addr.addr##2, addr.name) LIKE K.endUser
INNER JOIN co_mst co ON co.cust_num = addr.cust_num AND co.cust_seq = addr.cust_seq
INNER JOIN job_mst job ON job.ord_num = co.co_num AND job.stat <> 'C'
WHERE co.stat = 'O'
  AND co.co_num LIKE 'EX%'
ORDER BY K.endUser, K.port, co.co_num";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery2($this->StrConn, $query, $params);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
