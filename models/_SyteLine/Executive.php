<?php

class Executive {

    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function ReportProductForming() {
        $searchDepartmentName = "";
        $query = "";
        $query = $query . " select * from V_STS_execRpt_W_byDEPT_Live where [แผนก] <> 'others'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_execRpt_W_byType_Live($daystart, $dayend) {

        $query = " EXEC STS_execRpt_W_byType_Live "
                . " @daystart  = N'$daystart',"
                . " @dayend = N'$dayend' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        echo $query;
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_execRpt_W_bySize_Live($daystart, $dayend) {

        $query = " EXEC STS_execRpt_W_bySize_Live "
                . " @daystart  = N'$daystart',"
                . " @dayend = N'$dayend' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        echo $query;
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_execRpt_W_bySizeType_Live($daystart, $dayend) {

        $query = " EXEC STS_execRpt_W_bySizeType_Live "
                . " @daystart  = N'$daystart',"
                . " @dayend = N'$dayend' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        echo $query;
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function ReportProductFinish() {
        $searchDepartmentName = "";
        $query = "";
        $query = $query . "   select * from V_STS_execRpt_F_byMarket_Live order by [no.1], [no.2] ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_execRpt_F_byType_Live($daystart, $dayend) {

        $query = " EXEC STS_execRpt_F_byType_Live "
                . " @daystart  = N'$daystart',"
                . " @dayend = N'$dayend' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        echo $query;
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function V_STS_execRpt_F_byMKTType_Live($daystart, $dayend) {

        $query = " select * from V_STS_execRpt_F_byMKTType_Live order by [no.1], TypeDesc, Type ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        echo $query;
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_execRpt_F_bySize_Live($daystart, $dayend) {

        $query = " EXEC STS_execRpt_F_bySize_Live "
                . " @daystart  = N'$daystart',"
                . " @dayend = N'$dayend' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        echo $query;
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_execRpt_F_bySizeType_Live($daystart, $dayend) {

        $query = " EXEC STS_execRpt_F_bySizeType_Live "
                . " @daystart  = N'$daystart',"
                . " @dayend = N'$dayend' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        echo $query;
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function setExecutiveSummaryData1() {
        $searchDepartmentName = "";
        $query = "";
        $query = $query . " select case when Data_Group = 'Received' then 1 when Data_Group = 'Released' then 2 when Data_Group = 'Released (w/o Invoice)' then 3 else 4 end as [no.] , case when Data_Group = 'Released' then 'Released (Total)' else Data_group end as Data_Group , isnull(format(Coil,'N0'),'-') as Coil , isnull(format(Strip,'N0'),'-') as Strip , isnull(format([Processing Pipe],'N0'),'-') as [ProcessingPipe] , isnull(format([Finished Pipe],'N0'),'-') as [FinishedPipe] , isnull(format([Scrap Pipe],'N0'),'-') as [ScrapPipe] from (select data_group, item_group ,case when item_group in ('Processing Pipe') then NULL else sumqty end as sumqty from V_STS_execSUM_All_PrevMth_mtran sub1 union all select data_group, item_group,sumqty from V_STS_execSUM_All_PrevMth_mStock sub2 union all select data_group, item_group,sumqty from V_STS_execSUM_All_PrevMth_mtran_ShipNoINV sub3 ) as main PIVOT (sum(main.sumQTY) FOR main.Item_Group in ([Coil], [Strip], [Processing Pipe] ,[Finished Pipe],[Scrap Pipe]) ) as piv order by [no.] ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function setExecutiveSummaryData2() {
        $searchDepartmentName = "";
        $query = "";
        $query = $query . " select case when Data_Group = 'Received' then 1 when Data_Group = 'Released' then 2 when Data_Group = 'Released (w/o Invoice)' then 3 else 4 end as [no.] , case when Data_Group = 'Released' then 'Released (Total)' else Data_group end as Data_Group , isnull(format(Coil,'N0'),'-') as Coil , isnull(format(Strip,'N0'),'-') as Strip , isnull(format([Processing Pipe],'N0'),'-') as [ProcessingPipe] , isnull(format([Finished Pipe],'N0'),'-') as [FinishedPipe] , isnull(format([Scrap Pipe],'N0'),'-') as [ScrapPipe] from (select data_group, item_group ,case when item_group in ('Processing Pipe') then NULL else sumqty end as sumqty from V_STS_execSUM_All_Cur_mtran sub1 union all select * from V_STS_execSUM_All_Cur_mStock sub2 union all select * from V_STS_execSUM_All_Cur_mtran_ShipNOINV sub3 ) as main PIVOT (sum(main.sumQTY) FOR main.Item_Group in ([Coil], [Strip], [Processing Pipe] ,[Finished Pipe],[Scrap Pipe]) ) as piv order by [no.] ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_execRpt_SUM_mthly($daystart, $dayend, $codeItem) {

//        $query = " EXEC STS_execRpt_SUM_mthly "
//                . " @startDate  = N'$daystart',"
//                . " @endDate = N'$dayend' ,"
//                . " @codeItem = N'$codeItem'";
        $query = " EXEC STS_execRpt_SUM_mthly "
                . " @codeItem = N'$codeItem'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_execRpt_SUM_mthly_all() {

        $query = " EXEC STS_execRpt_SUM_mthly_all ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        return $rs0;
    }

    function STS_execSUM_Coil_Rec($daystart, $dayend) {

        $query = " EXEC STS_execSUM_Coil_Rec "
                . " @startDate  = N'$daystart',"
                . " @endDate = N'$dayend' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_execSUM_Pipe_Sale($daystart, $dayend) {

        $query = " EXEC STS_execSUM_Pipe_Sale "
                . " @startDate  = N'$daystart',"
                . " @endDate = N'$dayend' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function V_STS_execSUM_Outs_Coil() {
        $query = " Select * FROM V_STS_execSUM_Outs_Coil order by sort";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function V_STS_execSUM_Outs_Strip() {
        $query = " Select * FROM V_STS_execSUM_Outs_Strip where aged is not null  order by sort ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function V_STS_execSUM_Outs_ProcessingPipe() {
        $query = " Select * FROM V_STS_execSUM_Outs_ProcessingPipe order by sort";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function V_STS_execSUM_Outs_FinishedPipe() {
        $query = " select * from V_STS_execSUM_Outs_FinishedPipe union all select * from V_STS_execSUM_Outs_Finished_BarePipe union all select * from V_STS_execSUM_Outs_Finished_BundledPipe order by sort ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function V_STS_execSUM_Outs_Finished_BarePipe() {
        $query = " Select * FROM V_STS_execSUM_Outs_Finished_BarePipe ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function V_STS_execSUM_Outs_Finished_BundledPipe() {
        $query = " Select * FROM V_STS_execSUM_Outs_Finished_BundledPipe ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function matltran_mst_count_intreval_1($startDate, $endDate) {
        $query = " EXEC STS_ProductionDashboard_1  @startDate = '$startDate',  @endDate = '$endDate' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function matltran_mst_count_intreval($startDate, $endDate) {
        $query = " EXEC STS_ProductionDashboard  @startDate = '$startDate',  @endDate = '$endDate' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function matltran_mst_intreval() {
        $query = " select top 20 * FROM matltran_mst where trans_type ='F' order by trans_num desc ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    ///ฝ่ายขาย

    function STS_rpt_COitem_sum($startDate, $endDate, $start_co_num, $end_co_num, $cust_num) {
//        $query = "EXEC STS_rpt_COitem_sum @DateStarting = N'2020-03-29', @DateEnding = N'2020-04-02'";
        $cust_num_sql = '';
        if ($cust_num !== '') {
            $cust_num_sql = " and cust_num like '%$cust_num%'";
        }

        $query = "select co_num , order_date , datediff(day, order_date,getdate()) as COage_Days, [Type] , createdby , cust_num , cust_name , format(sum(qty_ordered),'N2') as qty_ordered , format(sum(co_price),'N2') as co_price , format(sum(totalWeight),'N2') as totalWeight , format(sum(qty_shipped),'N2') as qty_shipped , format(sum(do_price),'N2') as do_price , format(sum(qty_invoiced),'N2') as qty_invoiced , format(sum(inv_price),'N2') as inv_price , format(sum(inv_weight),'N2') as inv_weight  , format(sum(qty_shipped) - sum(qty_ordered),'N2') as Diff_CO_DO from V_STS_rpt_COitem_sum "
                . "where order_date between '$startDate' and '$endDate'  ";
        $query = $query . $cust_num_sql;
        $query = $query . " group by co_num , order_date , [Type] , createdby , cust_num , cust_name having sum(qty_shipped) <> sum(qty_ordered) order by co_num ";
        
        
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        print_r($rs0);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_rpt_COitem($co_num) {
        $query = "select co_num , order_date , [Type] , createdby , cust_num , cust_name , co_line , item , format(qty_ordered,'N2') as qty_ordered , u_m , format(co_price,'N2') as co_price , format(totalWeight,'N2') as totalWeight , format(sum(qty_shipped),'N2') as qty_shipped , format(sum(do_price),'N2') as do_price , format(sum(qty_invoiced),'N2') as qty_invoiced , format(sum(inv_price),'N2') as inv_price , format(sum(inv_weight),'N2') as inv_weight, format(sum(qty_shipped) - sum(qty_ordered),'N2') as Diff_CO_DO  from V_STS_rpt_COitem "
                . " where co_num = '$co_num' group by co_num , order_date , [Type] , createdby , cust_num , cust_name , co_line , item , qty_ordered , u_m , co_price , totalWeight order by co_num, co_line ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function MenuDoItem($co_num, $co_line) {
        $query = "select distinct do_num from co_ship_mst  where co_num = '$co_num' and co_line = $co_line";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function MenuInvItem($co_num, $co_line) {
        $query = "select distinct inv_num  from inv_item_mst where co_num = '$co_num' and co_line = $co_line";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function auditlog($startDate, $endDate) {

        $query = "select username, CreateDate, keyvalue as COnumLine ,oldvalue, newvalue  from auditlog where messagetype =10005 "
                . " and oldvalue is not null and oldvalue <> '0.00000000' "
                . " and (convert(date,createdate) between '$startDate' and '$endDate') ";


        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function ProductionDashboardP() {
        $query = "select description as Station, qty as [Qty Today (PCS)],qtyTon as [Qty Today (Ton)] , MTDqty as [Qty MTD (PCS)] , MTDqtyTon as [Qty MTD (Ton)]
                  from V_STS_execProdDash where wc like 'P%' order by description";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function ProductionDashboardW() {
        $query = "select description as Station, qty as [Qty Today (PCS)], qtyTon as [Qty Today (Ton)], MTDqty as [Qty MTD (PCS)] , MTDqtyTon as [Qty MTD (Ton)]
				  from V_STS_execProdDash where wc like 'W%' order by description";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
	
	  function STS_execRpt_F_byType_Live_Detail($daystart, $dayend, $country) {
        $query = "STS_execRpt_F_byType_Live_Detail @daystart = '$daystart', @dayend = '$dayend', @country = '$country'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
	
	  function STS_execRpt_F_bySize_Live_Detail($daystart, $dayend, $country) {
        $query = "STS_execRpt_F_bySize_Live_Detail @daystart = '$daystart', @dayend = '$dayend', @country = '$country'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
	
	  function STS_execRpt_F_bySizeType_Live_Detail($daystart, $dayend, $country) {
        $query = "STS_execRpt_F_bySizeType_Live_Detail @daystart = '$daystart', @dayend = '$dayend', @country = '$country'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
	
	  function STS_execRpt_W_byType_Live_Detail($daystart, $dayend, $country) {
        $query = "STS_execRpt_W_byType_Live_Detail @daystart = '$daystart', @dayend = '$dayend', @country = '$country'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
	
	  function STS_execRpt_W_bySize_Live_Detail($daystart, $dayend, $country) {
        $query = "STS_execRpt_W_bySize_Live_Detail @daystart = '$daystart', @dayend = '$dayend', @country = '$country'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
	
	  function STS_execRpt_W_bySizeType_Live_Detail($daystart, $dayend, $country) {
        $query = "STS_execRpt_W_bySizeType_Live_Detail @daystart = '$daystart', @dayend = '$dayend', @country = '$country'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

	  function GetLocationStock() {
        $query = "select loc_group, qty = sum(qtyPCS) , [weight_ton]= sum(qtyWeight)
from V_STS_stock_loc
group by loc_group";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

}
