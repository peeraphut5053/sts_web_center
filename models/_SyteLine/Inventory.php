<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Inventory
 *
 * @author Administrator
 */
class Inventory {

    var $StrConn = "";
    public $_txtStartDate = "";
    public $_txtEndDate = "";
    public $_txtItem = array();
    public $_txtItemStart = "";
    public $_txtItemEnd = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function GetRows_SP() {
        $txtStartDate = $this->_txtStartDate;
        $txtEndDate = $this->_txtEndDate;
        $txtItem = $this->_txtItem;
        $callSP = "Exec Rpt_InventoryBalanceSp_dev "
                . "@ItemStarting='?' "
                . ",@ItemEnding='?' "
                . ",@TransDateStarting = '?' "
                . ",@TransDateEnding= '?' "
                . ",@SummaryDtl=1 "
                . ",@IncludeMoveTrn = 1 "
                . ",@IncludeNonNetStk = 1   ";
        $params = array($txtItem, $txtItem, $txtStartDate, $txtEndDate);
        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            return "Error in executing statement 3.\n";
            die(print_r(sqlsrv_errors(), true));
        }
        $ArrData = array();
        $ArrData2 = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ArrData2["Seq"] = $row['Seq'];
            $ArrData2["trans_date"] = $row['trans_date']->format('Y-m-d H:i:s');
            $ArrData2["Item"] = $row['Item'];
            $ArrData2["ItemDesc"] = $row['ItemDesc'];
            $ArrData2["ItemUOM"] = $row['ItemUOM'];
            $ArrData2["RefType"] = $row['RefType'];
            $ArrData2["DisplayTransType"] = $row['DisplayTransType'];
            $ArrData2["UnitCost"] = $row['UnitCost'];
            $ArrData2["tranQty"] = $row['tranQty'];
            $ArrData2["tranCost"] = $row['tranCost'];
            $ArrData2["TranCostTotal"] = $row['TranCostTotal'];
            $ArrData2["QtyBal"] = $row['QtyBal'];
            $ArrData2["AccuAmount"] = $row['AccuAmount'];
            array_push($ArrData, $ArrData2);
        }
        sqlsrv_free_stmt($stmt);
        //echo $callSP ;
        return $ArrData;
    }

    Function GetRows_NewInventoryBalanceReport_SP() {
        $txtItemStart = $this->_txtItemStart;
        $txtItemEnd = $this->_txtItemEnd;
        $txtStartDate = $this->_txtStartDate;
        $txtEndDate = $this->_txtEndDate;
        $searchItem = "";

        if ($txtItemStart) {
            if (substr($txtItemStart, -1) == "*") {
                $txtItemStart = str_replace('*', '', $txtItemStart);
                $searchItem = " AND ( itm.item like '$txtItemStart%') ";
            } else {
                $searchItem = " AND ( itm.item like '%$txtItemStart%') ";
            }
        }
        $query = "SELECT main.item  
,ItemDescription = itm.[description]
,UnitWeight = itm.unit_weight 
,BalQty, BalAmt, BalUnit 
FROM  (select * from
   (select  item, BalQty, BalAmt, BalUnit 
     ,rowno = ROW_NUMBER() OVER (PARTITION BY item ORDER BY CONVERT (date , TransDate ,103) DESC 
           , CASE WHEN left(TransDescription ,1) ='R' OR left(TransDescription ,1) ='F' THEN 1 ELSE 0 END 
           , CAST(TransNum as int) DESC) 
   from matltran2_detail_mst b 
   where  (CONVERT(date , TransDate,103) BETWEEN '$txtStartDate' AND '$txtEndDate' or TransDate is null) 
   ) sub
   where rowno = 1) main
  LEFT JOIN item_mst itm ON main.item = itm.item 
where 1=1 $searchItem
order by main.item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function GetRows_NewInventoryBalanceReport_Detail($item, $txtStartDate, $txtEndDate) {

        $searchItem = "";

        if ($item) {
            if (substr($item, -1) == "*") {
                $item = str_replace('*', '', $item);
                $searchItem = " AND ( a.item like '$item%' ) ";
            } else {
                $searchItem = " AND ( a.item like '$item' ) ";
            }
        }



        $search_date = "AND  ((convert(date,a.transdate, 103) BETWEEN '$txtStartDate' AND '$txtEndDate') or a.transdate is null) ";

        $query = "select convert(date,a.transdate, 103) as TransdateCon ,a.* , b.lot , b.loc , b.reason_code, reason.description as reason_description, b.wc , wc.description as wc_description from matltran2_detail_mst a left join matltran_mst b on cast(a.transnum as int) = cast(b.trans_num as int) 		 left join (select distinct reason_code, description from reason_mst) reason on reason.reason_code = b.reason_code left join wc_mst wc on wc.wc = b.wc  where 1=1 ";
        $query = $query . $searchItem;
        $query = $query . $search_date;
        $query = $query . "ORDER BY CONVERT (date , TransDate ,103) ASC , CASE WHEN left(TransDescription ,1) ='R' OR left(TransDescription ,1) ='F' THEN 0 ELSE 1 END , CAST(TransNum as int) ASC ";


        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
	
	 Function RPT_NEW_INVENTORY_BALANCE_InvoiceAD_IN($txtStartDate, $txtEndDate) {
        $query = " EXEC Rpt_STS_InvoiceAD_IN "
        . " @TransactionDateStarting  = N'$txtStartDate',"
        . " @TransactionDateEnding = N'$txtEndDate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function Select_itemGradeSch ($itemGradeSch) {
       
        if ($itemGradeSch == ""){
            $query = "select * from STS_AD_itemGradeSch order by item";
        } else {
            $query = "select * from STS_AD_itemGradeSch where item = '$itemGradeSch'";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function Select_itemSIZEH ($itemSIZEH) {
       
        if ($itemSIZEH == ""){
            $query = "select * from STS_AD_itemSIZEH order by item";
        } else {
            $query = "select * from STS_AD_itemSIZEH where item = '$itemSIZEH'";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function GetItemGradeData ($itemGrade) {
        $query = "select * from STS_AD_itemGradeSch where item = '$itemGrade'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function GetItemSizehData ($itemSizeh) {
        $query = "select * from STS_AD_itemSIZEH where item = '$itemSizeh'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function InsertItemGrade ($itemGrade,$spec,$GRADEH,$SCHH,$saveStat) {
        if ($saveStat == 'Insert'){
            $query = "INSERT INTO STS_AD_itemGradeSch(item,spec,GRADEH,SCHH) VALUES('$itemGrade','$spec','$GRADEH','$SCHH')";
        }
        elseif ($saveStat == 'Update'){
            $query = "UPDATE STS_AD_itemGradeSch SET "
            . "item ='$itemGrade', "
            . "spec ='$spec', "
            . "GRADEH ='$GRADEH', "
            . "SCHH ='$SCHH'"
            . "WHERE item ='$itemGrade'";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function InsertItemSizeh ($itemSizeh,$inch,$MM,$SIZEH,$saveStat) {
        if ($saveStat == 'Insert'){
            $query = "INSERT INTO STS_AD_itemSIZEH(item,inch,MM,SIZEH) VALUES('$itemSizeh','$inch','$MM','$SIZEH')";
        }
        elseif ($saveStat == 'Update'){
            $query = "UPDATE STS_AD_itemSIZEH  SET "
            . "item ='$itemSizeh', "
            . "inch ='$inch', "
            . "MM ='$MM', "
            . "SIZEH ='$SIZEH'"
            . "WHERE item ='$itemSizeh'";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function DeleteItemGrade ($itemGrade) {
        $query = "delete from STS_AD_itemGradeSch where item = '$itemGrade'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function DeleteItemSizeh ($itemSizeh) {
        $query = "delete from STS_AD_itemSIZEH where item = '$itemSizeh'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function RPT_NEW_INVENTORY_BALANCE_Stock_Card_by_Invoice_No($item, $txtStartDate, $txtEndDate, $ThVendInvNum) {

        $searchItem = "";

        if ($item) {
            if (substr($item, -1) == "*") {
                $item = str_replace('*', '', $item);
                $searchItem = " AND ( a.item like '$item%' ) ";
            } else {
                $searchItem = " AND ( a.item like '%$item%' ) ";
            }
        }

//        $txtStartDate= '2018-12-31';
//        $txtEndDate='2020-08-21';
//        $ThVendInvNum = 'IN19060762';
        //     $search_date = "AND  convert(date,a.transdate, 103)  BETWEEN '$txtStartDate' AND '$txtEndDate'";
        $search_date = "AND  convert(date,date, 103)  BETWEEN '$txtStartDate' AND '$txtEndDate'";

        $search_ThVendInvNum = "and doc_no like '%$ThVendInvNum%'";
        //      $search_ThVendInvNum = "and a.ThVendInvNum like '%$ThVendInvNum%'";
        /*
          $query = " select convert(date,a.transdate, 103) as TransdateCon ,a.* , b.lot , b.loc from matltran2_detail_mst a left join matltran_mst b on cast(a.transnum as int) = cast(b.trans_num as int)  where 1=1  ";
          $query = $query . $searchItem;
          $query = $query . $search_date;
          $query = $query . $search_ThVendInvNum;
          $query = $query . "ORDER BY CONVERT (date , TransDate ,103) ASC , CASE WHEN left(TransDescription ,1) ='R' OR left(TransDescription ,1) ='F' THEN 0 ELSE 1 END , CAST(TransNum as int) ASC ";
         */
        $query = " select * FROM STS_report_pee_pen  where 1=1  ";
//        $query = " select convert(date,trans_date) as date , matltran_mst.item , item_mst.description , item_mst.unit_weight , concat(matltran_mst.ref_type, ' ',ref_type_mst.ref_description) , concat(matltran_mst.trans_type, ' ',trans_type_mst.trans_description) , TH_vend_inv_num , matltran_mst.qty from matltran_mst inner join item_mst on matltran_mst.item = item_mst.item inner join ref_type_mst on matltran_mst.ref_type = ref_type_mst.ref_type inner join trans_type_mst on matltran_mst.trans_type = trans_type_mst.trans_type where matltran_mst.ref_type = 'O' and convert(date, trans_date) > '2018-12-31' and TH_vend_inv_num is not null ";

        $query = $query . $searchItem;
        $query = $query . $search_date;
        $query = $query . $search_ThVendInvNum;

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function GetRows_NewInventoryBalanceReport_Detail_edit($FirstSearch, $item, $txtStartDate, $txtEndDate, $TrandesctionSelect) {

        $search_FirstSearch = "";
        if ($FirstSearch) {
            $search_FirstSearch = " AND 1=2 ";
        }
        $search_item = "";

        if ($item) {


            if ($item[0] == "*" && substr($item, -1) == "*") {
                $item = str_replace('*', '', $item);
                $search_item = " AND ( a.item like '%$item%' ) ";
            } else {
                if ($item[0] == "*" && substr($item, -1) != "*") {
                    $item = str_replace('*', '', $item);
                    $search_item = " AND ( a.item like '%$item' ) ";
                } elseif (substr($item, -1) == "*" and $item[0] != "*") {
                    $item = str_replace('*', '', $item);
                    $search_item = " AND ( a.item like '$item%' ) ";
                } elseif (substr($item, -1) != "*" and $item[0] != "*") {
                    $item = str_replace('*', '', $item);
                    $search_item = " AND ( a.item = '$item' ) ";
                }
            }
        }
        $search_TrandesctionSelect = "";
        if ($TrandesctionSelect) {
            $search_TrandesctionSelect = " AND ( a.TransDescription like '%$TrandesctionSelect%' ) ";
        }
        $search_date = "AND ((convert(date,a.transdate, 103) BETWEEN '$txtStartDate' AND '$txtEndDate') or a.transdate is null) ";

        $query = "select top 50000 convert(date,a.transdate, 103) as TransdateCon ,a.* ,"
                . " b.lot , b.loc  from matltran2_detail_mst a left join matltran_mst b on a.transnum = b.trans_num "
                . "where 1=1 ";
        $query = $query . $search_item;
        $query = $query . $search_date;
        $query = $query . $search_TrandesctionSelect;
        $query = $query . " ORDER BY a.item ASC, CONVERT (date ,  TransDate ,103)   ASC ,"
                . " CASE WHEN  left(TransDescription ,1)  ='R' OR  left(TransDescription ,1)  ='F' THEN 0 ELSE 1 END ,"
                . " CAST(TransNum as int) ASC ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function TrandesctionSelect() {
        $query = "select isnull(TransDescription,'-') as TransDescription FROM matltran2_detail_mst "
                . "where TransDescription is not null "
                . "group by TransDescription";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function GetRows_NewInventoryBalanceReport_JobFinsh($FirstSearch, $item, $txtStartDate, $txtEndDate, $TrandesctionSelect) {

        $search_FirstSearch = "";
        if ($FirstSearch) {
            $search_FirstSearch = " AND 1=2 ";
        }
        $search_item = "";
        if ($item) {
            $search_item = " AND ( mt.item like '%$item%' ) ";
        }
        $search_TrandesctionSelect = "";
        if ($TrandesctionSelect) {
            $search_TrandesctionSelect = "  AND ( a.TransDescription like '%$TrandesctionSelect%' ) ";
        }
        $search_date = "and trans_date between '$txtStartDate' and '$txtEndDate'  ";
        $query = "select recal.RowNumber, mt.trans_num , trans_date,"
                . " convert(varchar,trans_date,103) as trans_date_conv ,"
                . " mt.item , recal.itemDescription, recal.UnitWeight, recal.RefDescription,"
                . " recal.TransDescription, recal.RefNum, mt.lot, mt.loc, mt.qty , recal.NewAmt,"
                . " recal.NewUnit, mta.trans_seq, ref_num , DocumentNum, ThVendInvNum, ref_line_suf ,"
                . " amt , matl_amt , lbr_amt , fovhd_amt , fovhd_amt ,vovhd_amt, out_amt "
                . " from matltran_mst mt LEFT JOIN matltran2_detail_mst recal ON recal.transnum = mt.trans_num "
                . " inner join matltran_amt_mst mta on mt.trans_num = mta.trans_num where trans_type= 'F' "
                . " and ref_type ='J' and mta.include_in_inventory_bal_calc=1 and isnull(itemDescription,'')<>'' ";
//        $query = "select recal.RowNumber, mt.trans_num , trans_date,"
//                . " convert(varchar,trans_date,103) as trans_date_conv ,"
//                . " mt.item , recal.itemDescription, recal.UnitWeight, recal.RefDescription,"
//                . " recal.TransDescription, recal.RefNum, mt.lot, mt.loc, mt.qty , recal.NewAmt,"
//                . " recal.BalUnit, mta.trans_seq, ref_num , DocumentNum, ThVendInvNum, ref_line_suf ,"
//                . " amt , matl_amt , lbr_amt , fovhd_amt , fovhd_amt , out_amt "
//                . " from matltran_mst mt LEFT JOIN matltran2_detail_mst recal ON recal.transnum = mt.trans_num "
//                . " inner join matltran_amt_mst mta on mt.trans_num = mta.trans_num where trans_type= 'F' "
//                . " and ref_type ='J'  and trans_seq = 0 ";
        $query = $query . $search_item;
        $query = $query . $search_date;
        $query = $query . " ORDER BY mt.item ASC, CONVERT (date ,  TransDate ,103)   ASC ,"
                . " CASE WHEN  left(TransDescription ,1)  ='R' OR  left(TransDescription ,1)  ='F' THEN 0 ELSE 1 END ,"
                . " CAST(TransNum as int) ASC ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function Manu_Item_Cost_Update($txtFromDate, $txtToDate) {

        $queryInsert = " INSERT INTO MV_Manu_Item_Cost_Update_history"
                . " (click_start,click_type)"
                . " VALUES (getdate(),'update item cost')";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $queryInsert);

        $txtFromDate = $txtFromDate . " 00:00:00.000";
        $txtToDate = $txtToDate . " 23:59:59.000";
        $query = " DECLARE @return_value int,"
                . " @ReturnVal nvarchar(100) "
                . " EXEC @return_value = [dbo].[MV_Manu_Item_Cost_Update] "
                . " @start_date = N'$txtFromDate',@end_date = N'$txtToDate',"
                . " @ReturnVal = @ReturnVal OUTPUT "
                . " SELECT @ReturnVal as N'@ReturnVal' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_Recal_Matltran() {
        $queryInsert = " INSERT INTO MV_Manu_Item_Cost_Update_history"
                . " (click_start,click_type)"
                . " VALUES (getdate(),'recal')";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $queryInsert);

        $query = "EXEC STS_Recal_Matltran ";
        $cSql = new SqlSrv();
        $cSql->SqlQuery($this->StrConn, $query);
    }

    function checkStat() {
        $query = "select * FROM MV_Manu_Item_Cost_Update_history order by id desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function RPT_NEW_INVENTORY_GROUP_BY_TRANS_TYPE($txtFromDate, $txtToDate, $txtref_num, $txtItem, $txtw_c, $FinishQty, $IssueQty, $flagAllItem) {
        $txtw_c_query = "";
        $txtw_c_query_txt = "";
        if ($txtw_c) {
            $wc_explode = explode(",", $txtw_c);

            for ($i = 0; $i < count($wc_explode); $i ++) {
                $txtw_c_query = $txtw_c_query . "matltran_mst.wc like '%$wc_explode[$i]%'";
                if ($i < count($wc_explode) - 1) {
                    $txtw_c_query = $txtw_c_query . " or ";
                }
            }
            $txtw_c_query_txt = "and ( " . $txtw_c_query . " )";
        }
        $getAllItem = "";
        if ($flagAllItem == 1) {
            $getAllItem = " and matl2.item not like 'RCA%' and matl2.item not like 'RCB%' and matl2.item not like 'RZI%' and matl2.item not like 'ST%' ";
        }

        $query = " select distinct convert(date, matltran_mst.trans_date) as trans_date , matltran_mst.wc , wc_mst.description as wc_name , matltran_mst.ref_num , matltran_mst.item , item_mst.description as item_name , item_mst.unit_weight , sum(case when matltran_mst.trans_type = 'F' then matltran_mst.qty else 0 end) as Finish , submat.qty as Issue_Withdraw from matltran_mst left join item_mst on matltran_mst.item = item_mst.item and matltran_mst.trans_type = 'F' left join wc_mst on matltran_mst.wc = wc_mst.wc left join (select matl2.ref_num, matl2.wc, convert(date, trans_date) as trans_date , sum(matl2.qty) as qty from matltran_mst matl2 where matl2.trans_type in ('I', 'W') "
                . $getAllItem
                . " group by matl2.ref_num, matl2.wc, convert(date, trans_date) ) submat on submat.ref_num = matltran_mst.ref_num and submat.wc = matltran_mst.wc and convert(date, submat.trans_date) = convert(date, matltran_mst.trans_date) where matltran_mst.trans_type = 'F'  ";
        $query = $query . " AND  convert(date,matltran_mst.trans_date, 103)  BETWEEN '$txtFromDate' AND '$txtToDate'";
        $query = $query . " and	matltran_mst.ref_num like '%$txtref_num%' ";
        $query = $query . " and	matltran_mst.item like '%$txtItem%' ";
        $query = $query . " $txtw_c_query_txt ";
        $query = $query . " group by convert(date, matltran_mst.trans_date) , matltran_mst.wc , wc_mst.description , matltran_mst.ref_num , matltran_mst.item , item_mst.description , item_mst.unit_weight , submat.qty order by convert(date, matltran_mst.trans_date)";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function RPT_NEW_INVENTORY_AGEING($txtToDate, $txtFromDate, $product_code, $item, $rank_1, $rank_2, $rank_3, $rank_4, $rank_5) {

        $search_date = "";
        if ($txtToDate != "" && $txtFromDate != "") {
            $search_date = "and trans_date between '$txtToDate' and '$txtFromDate'  ";
        }
        if (substr($item, -1) == "*") {
            $item = str_replace('*', '', $item);
            $searchItem = " and matltran_mst.item like '$item%' ";
        } else {
            $searchItem = " and matltran_mst.item like '%$item%' ";
        }
        $query = " select  item_mst.uf_market,matltran_mst.Item, item_mst.description as item_name , item_mst.u_m as [U/M] , item_mst.unit_weight , convert(varchar,max(convert(date,trans_date)),105) as last_trans_date , convert(varchar,datediff(day, max(convert(date,trans_date)) ,convert(date,getdate())),105) as Diffdate , itemwhse_mst.qty_on_hand as Quantity , case when datediff(day, max(convert(date,trans_date)),convert(date,getdate()) ) between 0 and $rank_1 then 1 when datediff(day, max(convert(date,trans_date)),convert(date,getdate()) ) between $rank_1+1 and $rank_2 then 2 when datediff(day, max(convert(date,trans_date)),convert(date,getdate()) ) between $rank_2+1 and $rank_3 then 3 when datediff(day, max(convert(date,trans_date)),convert(date,getdate()) ) between $rank_3+1 and $rank_4 then 4 when datediff(day, max(convert(date,trans_date)),convert(date,getdate()) ) between $rank_4+1 and $rank_5 then 5 else 6 end as AgeRank from matltran_mst inner join item_mst on item_mst.item = matltran_mst.item left join prodcode_mst on prodcode_mst.product_code = item_mst.product_code left join itemwhse_mst on itemwhse_mst.item = matltran_mst.item where trans_type not in ('M', 'A') $search_date and itemwhse_mst.qty_on_hand <> 0 and item_mst.product_code like '%$product_code%' "
                . $searchItem
                . " group by item_mst.uf_market, matltran_mst.item, item_mst.product_code, prodcode_mst.description , itemwhse_mst.qty_on_hand ,item_mst.description, item_mst.u_m , item_mst.unit_weight order by matltran_mst.item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function product_code() {
        $query = "select product_code,description FROM prodcode_mst";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function RPT_Trial_Balance($txtFromDate, $txtToDate, $txtStartAcc, $txtEndAcc) {
        $txtStartDate = $this->_txtStartDate;
        $txtEndDate = $this->_txtEndDate;
        $txtItem = $this->_txtItem;
        $callSP = "EXEC	[dbo].[STS_TB] @Btransdate = '$txtFromDate', @Etransdate = '$txtToDate', @Bacct = '$txtStartAcc', @Eacct = '$txtEndAcc' ";
        $params = array($txtFromDate, $txtToDate, $txtStartAcc, $txtEndAcc);
        $stmt = sqlsrv_query($this->StrConn, $callSP, $params);
        if ($stmt === false) {
            return "Error in executing statement 3.\n";
            die(print_r(sqlsrv_errors(), true));
        }
        $ArrData = array();
        $ArrData2 = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ArrData2["acct"] = $row['acct'];
            $ArrData2["description"] = $row['description'];
            $ArrData2["Beginning Balance"] = $row['Beginning Balance'];
            $ArrData2["Debit (Credit)"] = $row['Debit (Credit)'];
            $ArrData2["Ending Balance"] = $row['Ending Balance'];
            array_push($ArrData, $ArrData2);
        }
        sqlsrv_free_stmt($stmt);
        return $ArrData;
    }

    Function Material_Transachtions_by_PO($txtToDate, $txtFromDate, $grn_num, $voucher_num, $po_num, $item, $lot) {


        $search_date = "and (convert(date,trans_date) between '$txtFromDate' and '$txtToDate')  ";

        $search_voucher_num = "";
        $search_grn_num = "";
        $search_item = "";
        $search_uf_lot = "";
        $search_po_num = "";


        if ($voucher_num !== "") {
            $search_voucher_num = "and aptrxp_mst.voucher like '%$voucher_num%' ";
        }

        if ($grn_num !== "") {
            $search_grn_num = "and grn_line_mst.grn_num like '%$grn_num%'  ";
        }

        if ($po_num !== "") {
            $search_po_num = "and mat.ref_num like '%$po_num%' ";
        }

        if ($item !== "") {
            $search_item = "and mat.item like '$item%'  ";
        }

        if ($lot !== "") {
            $search_uf_lot = "and mat.lot like '%$lot%'  ";
        }





        $query = "select distinct mat.trans_num ,convert(date,trans_date) as trans_date,mat.item ,mat.loc ,mat.lot ,format(mat.qty, 'N4') as qty ,mat.cost ,mat.matl_cost ,mat.lbr_cost ,mat.fovhd_cost ,mat.vovhd_cost ,out_cost , [total_posted] = (CASE WHEN (SELECT post_jour FROM parms_mst) = 1 THEN MatlTranView.TotalPost ELSE MatlTranView.TotalCost END) ,mat.createdby ,mat.ref_num as po_num ,mat.ref_line_suf as po_line ,document_num as grn_num ,aptrxp_mst.voucher ,aptrxp_mst.ref , aptrxp_mst.vend_num, vendaddr_mst.name from matltran_mst mat left join grn_line_mst on mat.ref_num = grn_line_mst.po_num and mat.ref_line_suf = grn_line_mst.po_line left join aptrxp_mst on mat.ref_num = aptrxp_mst.po_num left join vendaddr_mst on aptrxp_mst.vend_num = vendaddr_mst.vend_num left join STS_MaterialTransactionsView MatlTranView on MatlTranView.Trans_Num = mat.trans_num where 1=1 and mat.trans_type = 'R' and mat.ref_type = 'P' and aptrxp_mst.ref like 'APV%' "
                . $search_date . $search_po_num . $search_voucher_num . $search_grn_num;


        $query = "select distinct mat.trans_num ,convert(date,trans_date) as trans_date,mat.item ,mat.loc ,mat.lot ,format(mat.qty, 'N4') as qty ,mat.cost ,mat.matl_cost ,mat.lbr_cost ,mat.fovhd_cost ,mat.vovhd_cost ,out_cost , [total_posted] = (CASE WHEN (SELECT post_jour FROM parms_mst) = 1 THEN MatlTranView.TotalPost ELSE MatlTranView.TotalCost END) ,mat.createdby ,mat.ref_num as po_num ,mat.ref_line_suf as po_line ,isnull(document_num, grn_line_mst.grn_num) as grn_num ,grn_line_mst.grn_line ,vch_item_mst.voucher , aptrxp_mst.Uf_STS_Voucher as STS_VCH ,aptrxp_mst.inv_num , lot_mst.sts_no , grn_line_mst.vend_num, vendaddr_mst.name from matltran_mst mat left join grn_line_mst on mat.ref_num = grn_line_mst.po_num and mat.ref_line_suf = grn_line_mst.po_line and mat.lot = grn_line_mst.Uf_lot left join STS_MaterialTransactionsView MatlTranView on MatlTranView.Trans_Num = mat.trans_num left join lot_mst on grn_line_mst.Uf_lot = lot_mst.lot and lot_mst.sts_no is not null left join vch_item_mst on grn_line_mst.grn_num = vch_item_mst.grn_num and grn_line_mst.grn_line = vch_item_mst.grn_line and grn_line_mst.po_num = vch_item_mst.po_num and grn_line_mst.po_line = vch_item_mst.po_line left join aptrxp_mst on vch_item_mst.voucher = aptrxp_mst.voucher and aptrxp_mst.type = 'V' left join vendaddr_mst on grn_line_mst.vend_num = vendaddr_mst.vend_num where 1=1 and mat.trans_type = 'R' and mat.ref_type = 'P' "
                . $search_date . $search_po_num . $search_voucher_num . $search_grn_num . $search_item . $search_uf_lot;


        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function RPT_NEW_INVENTORY_Shipped_Date_Vs_Invoiced_Date($txtFromDate, $txtToDate, $co_num, $do_num, $inv_num) {

        $search_date = " ";
        if (($txtFromDate != "") && ($txtToDate != "")) {
            $search_date = " AND (convert(date,cosh.ship_date) between '$txtFromDate' and '$txtToDate') ";
        }



        $search_co_num = '';
        if ($co_num != '') {
            $search_co_num = "and cosh.co_num like '%$co_num%'";
        }

        $search_do_num = '';
        if ($do_num != '') {
            $search_do_num = "and cosh.do_num like '%$do_num%' ";
        }

        $search_inv_num = '';
        if ($inv_num != '') {
            $search_inv_num = "and inv.inv_num like '%$inv_num%' ";
        }
        $query = "  select distinct cosh.co_num, cosh.co_line, cosh.do_num , coi.item, item_mst.unit_weight , format(sum(cosh.qty_invoiced), 'N2') as qty_invoiced, format(sum(cosh.qty_shipped), 'N2') as qty_shipped , inv.inv_num , invh.terms_code , convert(date,invh.inv_date) as inv_date , convert(date,cosh.ship_date) as ship_date from co_ship_mst cosh left join coitem_mst coi on cosh.co_num = coi.co_num and cosh.co_line = coi.co_line left join item_mst on coi.item = item_mst.item  left join inv_item_mst inv on (inv.co_num = cosh.co_num and inv.co_line = cosh.co_line and isnull(inv.do_num,'0') = isnull(cosh.do_num,'0') and inv.do_line = cosh.do_line and inv.do_seq = cosh.do_seq) left join inv_hdr_mst invh on inv.inv_num = invh.inv_num where 1=1 "
                . $search_date . $search_co_num . $search_do_num . $search_inv_num
                . "   group by cosh.co_num, cosh.co_line, cosh.do_num, coi.item, item_mst.unit_weight , inv.inv_num, invh.terms_code, convert(date,invh.inv_date), convert(date,cosh.ship_date)";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function RPT_NEW_INVENTORY_BALANCE_Hot_Rolled_Coil_Purchase_by_Month($txtFromDate,$txtToDate ) {
        
        $query = " select Period, format(Purchase_Quantity,'#,#0.00') as Purchase_Quantity , format(Purchase_Price,'#,#0.00') as Purchase_Price , format(Landed_Cost,'#,#0.00') as Landed_Cost , format(Tax_and_Duty,'#,#0.00') as Tax_and_Duty "
                . " from sts_ad_HotRollCoilPurchase where period between '$txtFromDate' and '$txtToDate' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    
    Function RPT_NEW_INVENTORY_BALANCE_Additional_Purchasing_Cost($txtFromDate,$txtToDate,$txtFromReference_No,$txtToReference_No ) {
        $search_Reference_No = '';
        if ($txtFromReference_No != '') {
//            $search_Reference_No = "and (Reference_No between '$txtFromReference_No' and '$txtToReference_No') ";
            $search_Reference_No = "and Reference_No like '%$txtFromReference_No%' ";
        }
        
        $query = " select Reference_No, Period , format(Purchase_Quantity,'#,#0.00') as Purchase_Quantity , format(Purchase_Price,'#,#0.00') as Purchase_Price , format(Landed_Cost,'#,#0.00') as Landed_Cost , format(Tax_and_Duty,'#,#0.00') as Tax_and_Duty "
                . " from sts_ad_AdditionalPurchasingCost "
                . " where period between '$txtFromDate' and '$txtToDate' "
                . $search_Reference_No;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    
    Function RPT_NEW_INVENTORY_BALANCE_Hot_Rolled_Coil_Ending_Balance($txtFromDate,$txtToDate ) {
        
        $query = " select period, format(quantity,'#,#0.00') as Quantity , format(amount,'#,#0.00') as Amount "
                . " from sts_ad_HotRolledCoilEndingBalance "
                . " where period between '$txtFromDate' and '$txtToDate' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
