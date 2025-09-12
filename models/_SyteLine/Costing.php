<?php

class Costing
{

    var $StrConn = "";

    function setConn($c)
    {
        $this->StrConn = $c;
    }

    function RPT_ACCT_COST_BY_DEPARTMENT($txtFromDate, $txtToDate)
    {
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

    function RPT_ACCT_COST_BY_DEPARTMENT2($txtFromDate, $txtToDate)
    {
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

    function RPT_ACCT_COST_BY_DEPARTMENT_Weight($txtFromDate, $txtToDate)
    {
        //        $txtFromDate= '2020-02-01';
        //        $txtToDate= '2020-02-29';
        $query = " select 'Weight' as acct,'' as description , isnull(sum([ตัดแบ่ง sts]),0) as [ตัดแบ่ง sts], isnull(sum([ตัดแบ่ง wng]),0) as [ตัดแบ่ง wng] , isnull(sum([ฟอร์มมิ่ง sts]),0) as [ฟอร์มมิ่ง sts], isnull(sum([ฟอร์มมิ่ง wng]),0) as [ฟอร์มมิ่ง wng] , isnull(sum([ฟอร์มมิ่ง-JHP]),0) as [ฟอร์มมิ่ง-JHP] , isnull(sum([เตาชุบ]),0) as [เตาชุบ] , isnull(sum([ต๊าปเกลียว sts]),0) as [ต๊าปเกลียว sts], isnull(sum([ต๊าปเกลียว wng]),0) as [ต๊าปเกลียว wng] , isnull(sum([พ่นสี sts]),0) as [พ่นสี sts], isnull(sum([พ่นสี wng]),0) as [พ่นสี wng] , isnull(sum([พ่นสี-KRC]),0) as [พ่นสี-KRC] , isnull(sum([มัดท่อ sts]),0) as [มัดท่อ sts], isnull(sum([มัดท่อ wng]),0) as [มัดท่อ wng] , isnull(sum([รีดลดความหนา]),0) as [รีดลดความหนา] , isnull(sum([แผนกเครื่องรีดหลังคา]),0) as [แผนกเครื่องรีดหลังคา] , isnull(sum([ Prefabrication]),0) as [ Prefabrication] , [no group] = 0 , Total = isnull(sum([ตัดแบ่ง sts]),0) + isnull(sum([ตัดแบ่ง wng]),0) + isnull(sum([ฟอร์มมิ่ง sts]),0) + isnull(sum([ฟอร์มมิ่ง wng]),0) + isnull(sum([ฟอร์มมิ่ง-JHP]),0) + isnull(sum([เตาชุบ]),0) + isnull(sum([ต๊าปเกลียว sts]),0) + isnull(sum([ต๊าปเกลียว wng]),0) + isnull(sum([พ่นสี sts]),0) + isnull(sum([พ่นสี wng]),0) + isnull(sum([พ่นสี-KRC]),0) + isnull(sum([มัดท่อ sts]),0) + isnull(sum([มัดท่อ wng]),0) + isnull(sum([รีดลดความหนา]),0) + isnull(sum([แผนกเครื่องรีดหลังคา]),0) + isnull(sum([ Prefabrication]),0) from (select matltran_mst.item , sum(matltran_mst.qty) as sumQTY , item_mst.unit_weight , sum(matltran_mst.qty) * item_mst.unit_weight as [weight] , sts_wc_acct_unit.wc ,sts_wc_acct_unit.acct_unit1, LA.acct_unit_group_name from matltran_mst inner join sts_wc_acct_unit on sts_wc_acct_unit.wc = matltran_mst.wc and sts_wc_acct_unit.acct_unit1 not in ('1221','1242','2244','2242') inner join (select distinct L.acct_unit1, L.acct_unit_group_name from sts_ledger_acct_unit L ) LA on LA.acct_unit1 = sts_wc_acct_unit.acct_unit1 and sts_wc_acct_unit.acct_unit1 not in ('1221','1242','2244','2242') inner join item_mst on matltran_mst.item = item_mst.item where matltran_mst.trans_type = 'F' and matltran_mst.ref_type ='J' and LA.acct_unit_group_name <> 'no group' and (matltran_mst.trans_date BETWEEN '$txtFromDate 00:00:00.000' AND '$txtToDate 00:00:00.000') group by matltran_mst.item, sts_wc_acct_unit.wc , item_mst.unit_weight ,sts_wc_acct_unit.acct_unit1 , LA.acct_unit_group_name) a PIVOT ( sum(a.[weight]) for acct_unit_group_name in ( [ตัดแบ่ง sts], [ตัดแบ่ง wng] , [ฟอร์มมิ่ง sts], [ฟอร์มมิ่ง wng], [ฟอร์มมิ่ง-JHP] , [เตาชุบ] , [ต๊าปเกลียว sts], [ต๊าปเกลียว wng] , [พ่นสี sts], [พ่นสี wng], [พ่นสี-KRC] , [มัดท่อ sts], [มัดท่อ wng] , [รีดลดความหนา] , [แผนกเครื่องรีดหลังคา] , [ Prefabrication]) ) piv";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportItemLot($item, $lot)
    {

        $wh = '';

        if ($item != "") {
            $wh .= " and lot.item = '" . $item . "'";
        }

        if ($lot != "") {
            $wh .= " and lot.lot = '" . $lot . "'";
        }

        $query = "select lot.item, item.description, convert(decimal(10,2),lot.qty_on_hand) as qty_on_hand
  , lot.lot,mat.ref_num
  ,po.vend_num, va.name
  ,convert(decimal(10,5),poi.item_cost) as item_cost
  ,ven.curr_code
from lot_loc_mst lot 
  inner join item_mst item on lot.item = item.item
  left join matltran_mst mat on lot.item = mat.item and lot.lot = mat.lot
     and mat.ref_type = 'P' and qty <> 0
  left join poitem_mst poi on mat.item = poi.item and mat.ref_num = poi.po_num
  left join po_mst po on po.po_num = poi.po_num
  left join vendor_mst ven on ven.vend_num = po.vend_num
  left join vendaddr_mst va on ven.vend_num = va.vend_num
where (lot.item like 'RHR%' or lot.item like 'RSHR%')
 and lot.qty_on_hand <> 0 $wh";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportMatltranMst($fromdate, $todate, $item, $lot, $po)
    {

        $wh = '';

        if ($fromdate != "" && $todate != "") {
            $wh .= " and convert(date,mat.trans_date) between '" . $fromdate . "' and '" . $todate . "'";
        }

        if ($item != "") {
            $wh .= " and mat.item = '" . $item . "'";
        }

        if ($lot != "") {
            $wh .= " and mat.lot = '" . $lot . "'";
        }

        if ($po != "") {
            $wh .= " and matsub.po_num = '" . $po . "'";
        }

        $query = "select distinct mat.trans_num, convert(date,mat.trans_date) as trans_date , trans.trans_description as trans_type, ref.ref_description as ref_type
  , mat.ref_num, mat.lot, mat.document_num
  , mat.item, item.[description]
  , convert(decimal(10,2),mat.qty) as qty
  , matsub.po_num, matsub.vend_num, matsub.name, matsub. item_cost, matsub.curr_code
from matltran_mst mat 
 inner join item_mst item on mat.item = item.item
 inner join trans_type_mst trans on mat.trans_type = trans.trans_type
 inner join ref_type_mst ref on ref.ref_type = mat. ref_type
 left join (select mat.ref_num as po_num, mat.item, mat.lot
         ,po.vend_num, va.name
      ,convert(decimal(10,5),poi.item_cost) as item_cost
      ,ven.curr_code
      from matltran_mst mat
     inner join poitem_mst poi on mat.item = poi.item and mat.ref_num = poi.po_num
     inner join po_mst po on po.po_num = poi.po_num
     inner join vendor_mst ven on ven.vend_num = po.vend_num
     inner join vendaddr_mst va on ven.vend_num = va.vend_num
      where (mat.item like 'RHR%' or mat.item like 'RSHR%')
     and mat.ref_type = 'P' and mat.qty <> 0
     ) matsub
     on matsub.item = mat.item and matsub.lot = mat.lot
where (mat.item like 'RHR%' or mat.item like 'RSHR%')
  and mat.trans_type <> 'M'
     and mat.ref_type <> 'P' and mat.qty <> 0
$wh
order by lot, trans_num";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportInvWeight($StartDate, $EndDate)
    {
        $query = "EXEC [dbo].[STS_inv_weight]
  @SInvDateStarting = '$StartDate',
  @EInvDateEnding = '$EndDate'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportDeliveryTemp($doc_num, $StartDate, $EndDate, $name){
        $wh = '';

        if ($doc_num != "") {
            $wh .= " and hdr.doc_num = '" . $doc_num . "'";
        }

        if ($StartDate != "" && $EndDate != "") {
            $wh .= " and convert(date,hdr.delivery_date) between '" . $StartDate . "' and '" . $EndDate . "'";
        }

        if ($name != "") {
            $wh .= " and cust.name like '%" . $name . "%'";
        }

        $query = "select hdr.doc_num, hdr.cust_num, cust.name, hdr.cust_seq, hdr.ref_no, hdr.cust_po, hdr.delivery_date
  ,hdr.inv_num,hdr.pickPlace, item.item, item.actweight, item.qty, qty_amend = isnull(item.qty_modif,0)
  , item.u_m, hdr.car, hdr.remark , addr##1 as add1, addr##2 as add2, addr##3 as add3,addr##4 as add4, zip
from STS_delivery_temp_hdr hdr 
  inner join STS_delivery_temp_item item on hdr.doc_num = item.doc_num
  inner join custaddr_mst cust on cust.cust_num = hdr.cust_num and cust.cust_seq = hdr.cust_seq
where 1=1 $wh
  ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetCustomer()
    {
        $query = "select cust_num, name from custaddr_mst
where cust_seq = 0";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    function GetReportPO($StartDate, $EndDate){
        $query = "select po.po_num
    , order_date = convert(date,order_date)  
    , ven.name
    , item = itm.[description]
 , poi.u_m
 , qty_ordered = convert(decimal(18,2),poi.qty_ordered)
 , item_cost = convert(decimal(18,2),poi.item_cost)
    , total = convert(decimal(18,2), poi.qty_ordered * poi.item_cost)
from po_mst po
     inner join vendaddr_mst ven on po.vend_num = ven.vend_num
  inner join poitem_mst poi on po.po_num = poi.po_num
  inner join item_mst itm on poi.item = itm.item
where po.po_num like 'POS%' 
  and convert(date,po.order_date) between '$StartDate' and '$EndDate'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetCustomerSeq($cust_num)
    {
        $query = "select cust_num,cust_seq, name
from custaddr_mst
where cust_num = '$cust_num'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetCustomerSeqQuote($cust_num)
    {
        $query = "select cust.cust_num,cust.cust_seq, cust_to = cust.name, cust_tel = coalesce(cus.phone##1,cus.phone##2)
from custaddr_mst cust
inner join customer_mst cus on cus.cust_num = cust.cust_num and cus.cust_seq = cust.cust_seq
where cust.cust_num = '$cust_num'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function CreateDeliveryTemp($cust_num, $cust_seq, $delivery_date, $cust_po, $remark, $inv_num, $car, $ref_no, $arr_item, $arr_qty, $arr_u_m, $arr_act_weight,$pick_address)
    {
        $year = date('y');
        $month = date('m');
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 doc_num FROM STS_delivery_temp_hdr WHERE doc_num LIKE 'T$year$month%' ORDER BY doc_num DESC";
        $cSql = new SqlSrv();
        $result = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($result, count($result) - 1, 1);

        if (count($result) > 0) {
            $row = $result[0];
            $lastDoc = $row['doc_num'];
            // ดึงเลขลำดับจากเลขเอกสารล่าสุด (T2407001 -> 001)
            $lastNumber = intval(substr($lastDoc, -3));
            $newNumber = $lastNumber + 1;
        } else {
            // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
            $newNumber = 1;
        }
        // สร้างเลขเอกสารใหม่
        $docNumber = sprintf("T%s%02d%03d", $year, $month, $newNumber);
        $query = "INSERT INTO STS_delivery_temp_hdr (doc_num,cust_num,cust_seq,delivery_date,cust_po,remark,inv_num,car,ref_no,pickPlace) VALUES ('$docNumber','$cust_num','$cust_seq','$delivery_date','$cust_po','$remark','$inv_num','$car','$ref_no','$pick_address')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        // foreach insert item act weight qty u_m
        foreach ($arr_item as $key => $item) {
            $qty = $arr_qty[$key];
            $u_m = $arr_u_m[$key];
            $act_weight = $arr_act_weight[$key];
            $query2 = "INSERT INTO STS_delivery_temp_item (doc_num, item, qty, u_m, ActWeight) 
        VALUES (?, ?, ?, ?, ?)";
            $params = array($docNumber, $item, $qty, $u_m, $act_weight);
            $cSql = new SqlSrv();
            $rs = $cSql->SqlQuery2($this->StrConn, $query2, $params);
        }
        array_splice($rs, count($rs) - 1, 1);
        return $docNumber;
    }

     function UpdateDeliveryTemp($doc_num, $cust_num, $cust_seq, $delivery_date, $cust_po, $remark, $inv_num, $car, $ref_no, $item, $qty, $u_m, $act_weight, $pick_address,$item_old) {
        $query = "UPDATE STS_delivery_temp_hdr SET cust_num='$cust_num', cust_seq='$cust_seq', delivery_date='$delivery_date',cust_po='$cust_po', remark='$remark', inv_num='$inv_num',car='$car', ref_no='$ref_no', pickPlace = '$pick_address',updateDate = GETDATE() WHERE doc_num = '$doc_num'";
        $query2 = "UPDATE STS_delivery_temp_item SET item = '$item', qty_modif = '$qty', u_m = '$u_m', ActWeight = '$act_weight', updateDate = GETDATE() WHERE doc_num = '$doc_num' AND item = '$item_old'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        $rs = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
     function GetItem() {
        $query = "select distinct[description] from item_mst";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function GetItemQuote($search) {
        $query = "select distinct[description], item from item_mst where description like '%$search%'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function GetNominee($nominee) {
        $query = "select * from STS_delivery_temp_nominee where num = '$nominee'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function DeleteDeliveryTempItem($doc_num, $item) {
        $query = "DELETE FROM STS_delivery_temp_item WHERE doc_num = '$doc_num' AND item = '$item'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function CreateQuote($cust_num, $cust_seq,  $remark,$ref_no, $arr_item, $arr_qty, $arr_u_m, $arr_unit,$arr_weight, $cust_to,$tel,$subject,$remark2,$salesman) {
        $year = date('y');
        $month = date('m');
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 doc_num FROM STS_quote_hdr WHERE doc_num LIKE 'Q$year$month%' ORDER BY doc_num DESC";
        $cSql = new SqlSrv();
        $result = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($result, count($result) - 1, 1);

        if (count($result) > 0) {
            $row = $result[0];
            $lastDoc = $row['doc_num'];
            // ดึงเลขลำดับจากเลขเอกสารล่าสุด (T2407001 -> 001)
            $lastNumber = intval(substr($lastDoc, -3));
            $newNumber = $lastNumber + 1;
        } else {
            // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
            $newNumber = 1;
        }
        // สร้างเลขเอกสารใหม่
        $docNumber = sprintf("Q%s%02d%03d", $year, $month, $newNumber);
        $query = "INSERT INTO STS_quote_hdr (doc_num,cust_num,cust_seq,remark,ref_no,cust_to,cust_tel,cust_subject,remark2,salesman) VALUES ('$docNumber','$cust_num','$cust_seq','$remark','$ref_no', '$cust_to','$tel','$subject','$remark2','$salesman')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        // foreach insert item act weight qty u_m
        foreach ($arr_item as $key => $item) {
            $qty = $arr_qty[$key];
            $u_m = $arr_u_m[$key];
            $unit = $arr_unit[$key];
            $act_weight = $arr_weight[$key];
            $query2 = "INSERT INTO STS_quote_item (doc_num,item,qty,u_m,unit_price,WeightPCS) VALUES (?,?,?,?,?,?)";
            $params = array($docNumber, $item, $qty, $u_m, $unit, $act_weight);
            $cSql = new SqlSrv();
            $rs = $cSql->SqlQuery2($this->StrConn, $query2, $params);
        }
        array_splice($rs, count($rs) - 1, 1);
        return $docNumber;
    }

     function GetReportQuote($doc_num, $StartDate, $EndDate, $name){
        $wh = '';

        if ($doc_num != "") {
            $wh .= " and hdr.doc_num = '" . $doc_num . "'";
        }

        if ($StartDate != "" && $EndDate != "") {
            $wh .= " and convert(date,hdr.createdate) between '" . $StartDate . "' and '" . $EndDate . "'";
        }

        if ($name != "") {
            $wh .= " and cust.name like '%" . $name . "%'";
        }

        $query = "select hdr.doc_num, hdr.cust_num, hdr.cust_seq, hdr.createdate, hdr.ref_no, hdr.salesman
  , cust_to = isnull(hdr.cust_to,cust.name), cust_tel = coalesce(hdr.cust_tel,cus.phone##1,cus.phone##2)
  , hdr.cust_subject
  , item.item, item.[description], item.qty , item.u_m,item.unit_price, item.weightPCS
  , hdr.revised, hdr.remark, hdr.remark2
from STS_quote_hdr hdr 
  inner join STS_quote_item item on hdr.doc_num = item.doc_num
  inner join custaddr_mst cust on cust.cust_num = hdr.cust_num and cust.cust_seq = hdr.cust_seq
  inner join customer_mst cus on cus.cust_num = hdr.cust_num and cus.cust_seq = hdr.cust_seq
where 1=1 $wh
  ";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function UpdateQuote($doc_num, $cust_num, $cust_seq, $remark, $ref_no, $item, $qty, $u_m, $unit_price, $item_old, $weight_pcs,$cust_to,$tel,$subject,$remark2,$revised,$salesman) {
        $query = "UPDATE STS_quote_hdr SET cust_num='$cust_num', cust_seq='$cust_seq', remark='$remark', ref_no='$ref_no', cust_to = '$cust_to', cust_tel = '$tel', cust_subject = '$subject', remark2 = '$remark2', salesman = '$salesman', updateDate = GETDATE() WHERE doc_num = '$doc_num'";

        if ($revised == 1) {
            $query = "UPDATE STS_quote_hdr SET cust_num='$cust_num', cust_seq='$cust_seq', remark='$remark', ref_no='$ref_no', cust_to = '$cust_to', cust_tel = '$tel', cust_subject = '$subject', remark2 = '$remark2', salesman = '$salesman', revised = $revised, updateDate = GETDATE() WHERE doc_num = '$doc_num'";
        }

        $query2 = "UPDATE STS_quote_item SET item='$item', qty='$qty', u_m='$u_m', unit_price='$unit_price', WeightPCS='$weight_pcs', updateDate = GETDATE() WHERE doc_num = '$doc_num' AND item = '$item_old'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        $rs = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function DeleteQuoteItem($doc_num, $item) {
        $query = "DELETE FROM STS_quote_item WHERE doc_num = '$doc_num' AND item = '$item'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function AddQuoteItem($doc_num, $item, $qty, $u_m, $unit_price, $weight_pc) {
        $query = "INSERT INTO STS_quote_item (doc_num, item, qty, u_m, unit_price, WeightPCS) VALUES (?,?,?,?,?,?)";
        $params = array($doc_num, $item, $qty, $u_m, $unit_price, $weight_pc);
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery2($this->StrConn, $query, $params);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    function AddDeliveryTempItem($doc_num, $item, $qty, $u_m, $act_weight) {
        $query = "INSERT INTO STS_delivery_temp_item (doc_num,item,qty,u_m,ActWeight) VALUES (?,?,?,?,?)";
        $params = array($doc_num, $item, $qty, $u_m, $act_weight);
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery2($this->StrConn, $query, $params);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetSalesByWeek($type, $year) {
        $query = "";

        if ($type == "amount") {
            $query = "EXEC [dbo].[STS_salesperson_byWEEK]
  @year = N'$year'";
        } else {
            $query = "EXEC [dbo].[STS_salesperson_byWEEK_KG]
  @year = N'$year'";
        }
            

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetSalesByMonth($type, $year) {
        $query = "";

        if ($type == "amount") {
            $query = "SELECT    name 
  , [1] AS JAN, [2] AS FEB, [3] AS MAR, [4] AS APR, [5] AS MAY, [6] AS JUN
  , [7] AS JUL, [8] AS AUG, [9] AS SEP, [10] AS OCT, [11] AS NOV, [12] AS DEC
FROM ( SELECT [total_INVamount],[month],name  FROM V_WebApp_InvItem_IN_slsman  where [year]='$year' ) AS TABLE1
PIVOT ( SUM([total_INVamount]) FOR [MONTH] IN ( [1], [2], [3], [4], [5], [6], [7], [8], [9], [10], [11], [12] ) ) AS PIV";
        } else {
            $query = "SELECT    name 
  , [1] AS JAN, [2] AS FEB, [3] AS MAR, [4] AS APR, [5] AS MAY, [6] AS JUN
  , [7] AS JUL, [8] AS AUG, [9] AS SEP, [10] AS OCT, [11] AS NOV, [12] AS DEC
FROM ( SELECT [QtyKG],[month],name  FROM V_WebApp_InvItem_IN_slsman  where [year]='$year' ) AS TABLE1
PIVOT ( SUM([QtyKG]) FOR [MONTH] IN ( [1], [2], [3], [4], [5], [6], [7], [8], [9], [10], [11], [12] ) ) AS PIV";
        }
        

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetSalesByYear($type, $year) {
        $query = "";

        if ($type == "amount") {
            $query = "EXEC [dbo].[STS_salesperson_byYEAR]
  @year = $year";
        } else {
            $query = "EXEC [dbo].[STS_salesperson_byYEAR_KG]
  @year = $year";
        }

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }


}

