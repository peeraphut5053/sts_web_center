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
        $query = "SELECT distinct lot.loc, loc.description, lot.lot, lot.qty_on_hand, tag.uf_act_weight ,tag=tag.id
FROM lot_loc_mst lot
inner join location_mst loc on loc.loc = lot.loc
left join mv_bc_tag tag on lot.item=tag.item and lot.lot=tag.lot and tag.qty1 = lot.qty_on_hand
   and tag.active = 1 and tag.ship_stat <> 1
Where lot.qty_on_hand > 0 
and lot.item = '$item'";
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

    function Customer_report_order($txtStartDate,$txtToDate) {
        $query = "EXEC	STS_CO_report @TransactionDateStarting = N'$txtStartDate',@TransactionDateEnding = N'$txtToDate',@CUSTnumStarting = N'',@CUSTnumEnding = N'' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
