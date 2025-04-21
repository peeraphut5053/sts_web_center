<?php

class Costing {

    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function RPT_ACCT_COST_BY_DEPARTMENT($txtFromDate, $txtToDate) {
//        $txtFromDate= '2020-02-01';
//        $txtToDate= '2020-02-29';
        $query = "select * , Total = isnull([ตัดแบ่ง sts],0) + isnull([ตัดแบ่ง wng],0) + isnull([ต๊าปเกลียว sts],0) + isnull([ต๊าปเกลียว wng],0) + isnull([เตาชุบ],0) + isnull([แผนกเครื่องรีดหลังคา],0) + isnull([พ่นสี sts],0) + isnull([พ่นสี wng],0) + isnull([พ่นสี-KRC],0) + isnull([ฟอร์มมิ่ง sts],0) + isnull([ฟอร์มมิ่ง wng],0) + isnull([ฟอร์มมิ่ง-JHP],0) + isnull([มัดท่อ sts],0) + isnull([มัดท่อ wng],0) + isnull([รีดลดความหนา],0) + isnull([ Prefabrication],0) from ( select sts_ledger_acct_unit.acct, chart_mst.description ,acct_unit_group_name, sum(ledger_mst.dom_amount) as amount from sts_ledger_acct_unit left join chart_mst ON sts_ledger_acct_unit.acct = chart_mst.acct left join ledger_mst ON sts_ledger_acct_unit.acct = ledger_mst.acct and sts_ledger_acct_unit.acct_unit1= ledger_mst.acct_unit1 "
                . " and (trans_date BETWEEN '$txtFromDate 00:00:00.000' AND '$txtToDate 00:00:00.000')  "
                . " where sts_ledger_acct_unit.acct like '52%' "
                . "group by sts_ledger_acct_unit.acct,chart_mst.description, sts_ledger_acct_unit.acct_unit_group_name ) a PIVOT ( max(amount) for acct_unit_group_name in ( [ตัดแบ่ง sts], [ตัดแบ่ง wng], [ต๊าปเกลียว sts], [ต๊าปเกลียว wng], [เตาชุบ], [แผนกเครื่องรีดหลังคา], [พ่นสี sts], [พ่นสี wng] , [พ่นสี-KRC], [ฟอร์มมิ่ง sts], [ฟอร์มมิ่ง wng], [ฟอร์มมิ่ง-JHP], [มัดท่อ sts], [มัดท่อ wng], [รีดลดความหนา], [ Prefabrication], [no group]) ) piv order by acct ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function RPT_ACCT_COST_BY_DEPARTMENT2($txtFromDate, $txtToDate) {
//        $txtFromDate= '2020-02-01';
//        $txtToDate= '2020-02-29';
        $query = "select * , Total = isnull([ตัดแบ่ง sts],0) + isnull([ตัดแบ่ง wng],0) + isnull([ฟอร์มมิ่ง sts],0) + isnull([ฟอร์มมิ่ง wng],0) + isnull([ฟอร์มมิ่ง-JHP],0) + isnull([เตาชุบ],0) + isnull([ต๊าปเกลียว sts],0) + isnull([ต๊าปเกลียว wng],0) + isnull([พ่นสี sts],0) + isnull([พ่นสี wng],0) + isnull([พ่นสี-KRC],0) + isnull([มัดท่อ sts],0) + isnull([มัดท่อ wng],0) + isnull([รีดลดความหนา],0) + isnull([แผนกเครื่องรีดหลังคา],0) + isnull([ Prefabrication],0) from ( select sts_ledger_acct_unit.acct, chart_mst.description ,acct_unit_group_name, sum(ledger_mst.dom_amount) as amount from sts_ledger_acct_unit left join chart_mst ON sts_ledger_acct_unit.acct = chart_mst.acct left join ledger_mst ON sts_ledger_acct_unit.acct = ledger_mst.acct and sts_ledger_acct_unit.acct_unit1= ledger_mst.acct_unit1 "
                . "and (trans_date BETWEEN '$txtFromDate 00:00:00.000' AND '$txtToDate 00:00:00.000') "
                . "where sts_ledger_acct_unit.acct not like '52%' group by sts_ledger_acct_unit.acct,chart_mst.description , sts_ledger_acct_unit.acct_unit_group_name ) a PIVOT ( max(amount) for acct_unit_group_name in ( [ตัดแบ่ง sts], [ตัดแบ่ง wng] , [ฟอร์มมิ่ง sts], [ฟอร์มมิ่ง wng], [ฟอร์มมิ่ง-JHP], [เตาชุบ] , [ต๊าปเกลียว sts], [ต๊าปเกลียว wng] , [พ่นสี sts], [พ่นสี wng], [พ่นสี-KRC] , [มัดท่อ sts], [มัดท่อ wng] , [รีดลดความหนา] , [แผนกเครื่องรีดหลังคา] , [ Prefabrication], [no group]) ) piv order by acct ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function RPT_ACCT_COST_BY_DEPARTMENT_Weight($txtFromDate, $txtToDate) {
//        $txtFromDate= '2020-02-01';
//        $txtToDate= '2020-02-29';
        $query = " select 'Weight' as acct,'' as description , isnull(sum([ตัดแบ่ง sts]),0) as [ตัดแบ่ง sts], isnull(sum([ตัดแบ่ง wng]),0) as [ตัดแบ่ง wng] , isnull(sum([ฟอร์มมิ่ง sts]),0) as [ฟอร์มมิ่ง sts], isnull(sum([ฟอร์มมิ่ง wng]),0) as [ฟอร์มมิ่ง wng] , isnull(sum([ฟอร์มมิ่ง-JHP]),0) as [ฟอร์มมิ่ง-JHP] , isnull(sum([เตาชุบ]),0) as [เตาชุบ] , isnull(sum([ต๊าปเกลียว sts]),0) as [ต๊าปเกลียว sts], isnull(sum([ต๊าปเกลียว wng]),0) as [ต๊าปเกลียว wng] , isnull(sum([พ่นสี sts]),0) as [พ่นสี sts], isnull(sum([พ่นสี wng]),0) as [พ่นสี wng] , isnull(sum([พ่นสี-KRC]),0) as [พ่นสี-KRC] , isnull(sum([มัดท่อ sts]),0) as [มัดท่อ sts], isnull(sum([มัดท่อ wng]),0) as [มัดท่อ wng] , isnull(sum([รีดลดความหนา]),0) as [รีดลดความหนา] , isnull(sum([แผนกเครื่องรีดหลังคา]),0) as [แผนกเครื่องรีดหลังคา] , isnull(sum([ Prefabrication]),0) as [ Prefabrication] , [no group] = 0 , Total = isnull(sum([ตัดแบ่ง sts]),0) + isnull(sum([ตัดแบ่ง wng]),0) + isnull(sum([ฟอร์มมิ่ง sts]),0) + isnull(sum([ฟอร์มมิ่ง wng]),0) + isnull(sum([ฟอร์มมิ่ง-JHP]),0) + isnull(sum([เตาชุบ]),0) + isnull(sum([ต๊าปเกลียว sts]),0) + isnull(sum([ต๊าปเกลียว wng]),0) + isnull(sum([พ่นสี sts]),0) + isnull(sum([พ่นสี wng]),0) + isnull(sum([พ่นสี-KRC]),0) + isnull(sum([มัดท่อ sts]),0) + isnull(sum([มัดท่อ wng]),0) + isnull(sum([รีดลดความหนา]),0) + isnull(sum([แผนกเครื่องรีดหลังคา]),0) + isnull(sum([ Prefabrication]),0) from (select matltran_mst.item , sum(matltran_mst.qty) as sumQTY , item_mst.unit_weight , sum(matltran_mst.qty) * item_mst.unit_weight as [weight] , sts_wc_acct_unit.wc ,sts_wc_acct_unit.acct_unit1, LA.acct_unit_group_name from matltran_mst inner join sts_wc_acct_unit on sts_wc_acct_unit.wc = matltran_mst.wc and sts_wc_acct_unit.acct_unit1 not in ('1221','1242','2244','2242') inner join (select distinct L.acct_unit1, L.acct_unit_group_name from sts_ledger_acct_unit L ) LA on LA.acct_unit1 = sts_wc_acct_unit.acct_unit1 and sts_wc_acct_unit.acct_unit1 not in ('1221','1242','2244','2242') inner join item_mst on matltran_mst.item = item_mst.item where matltran_mst.trans_type = 'F' and matltran_mst.ref_type ='J' and LA.acct_unit_group_name <> 'no group' and (matltran_mst.trans_date BETWEEN '$txtFromDate 00:00:00.000' AND '$txtToDate 00:00:00.000') group by matltran_mst.item, sts_wc_acct_unit.wc , item_mst.unit_weight ,sts_wc_acct_unit.acct_unit1 , LA.acct_unit_group_name) a PIVOT ( sum(a.[weight]) for acct_unit_group_name in ( [ตัดแบ่ง sts], [ตัดแบ่ง wng] , [ฟอร์มมิ่ง sts], [ฟอร์มมิ่ง wng], [ฟอร์มมิ่ง-JHP] , [เตาชุบ] , [ต๊าปเกลียว sts], [ต๊าปเกลียว wng] , [พ่นสี sts], [พ่นสี wng], [พ่นสี-KRC] , [มัดท่อ sts], [มัดท่อ wng] , [รีดลดความหนา] , [แผนกเครื่องรีดหลังคา] , [ Prefabrication]) ) piv";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportItemLot() {
        $query = "select lot.item, item.description, convert(decimal(10,2),lot.qty_on_hand) as qty_on_hand
  , lot.lot,mat.ref_num
  ,po.vend_num, va.name
  ,convert(decimal(10,5),poi.item_cost) as item_cost
  ,ven.curr_code
from lot_loc_mst lot 
  inner join item_mst item on lot.item = item.item
  inner join matltran_mst mat on lot.item = mat.item and lot.lot = mat.lot
     and mat.ref_type = 'P' and qty <> 0
  inner join poitem_mst poi on mat.item = poi.item and mat.ref_num = poi.po_num
  inner join po_mst po on po.po_num = poi.po_num
  inner join vendor_mst ven on ven.vend_num = po.vend_num
  inner join vendaddr_mst va on ven.vend_num = va.vend_num
where (lot.item like 'RHR%' or lot.item like 'RSHR%')";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
}
