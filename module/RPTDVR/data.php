<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";
if ($load == "form") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
    $DVR->_txtDoNumFrom = $txtDoNumFrom;
    $DVR->_txtDoNumTo = $txtDoNumTo;
    $DVR->_txtCoFrom = $txtCoFrom;
    $DVR->_txtCoTo = $txtCoTo;
    $DVR->_txtCustFrom = $txtCustFrom;
    $DVR->_txtCustTo = $txtCustTo;
    $DVR->_txtDoDateFrom = $txtDoNumFrom;
    $DVR->_txtDoDateTo = $txtDoNumTo;
    $DVR->_txtItemFrom = $txtItemFrom;
    $DVR->_txtItemTo = $txtItemTo;
    $DVR->_txtPickFrom = $txtPickFrom;
    $DVR->_txtPickTo = $txtPickTo;
    $DVR->_HdrOnly = "1";
    $DVRS = $DVR->GetDoHdr();

    $lines = "";
    $lines2 = "";
    $tmpHdrDate = "";
    $tmpPickupDate = "";
    $tmpDo = "";
    $DVRS2 = null;
    $tmpHead = "";
    $pack_qty = 0;
    $item_pack = 0;
    $net_weight = 0;
    $qty = 0;
    $diff_pack_qty = 0;


    $ShowDo = "";
    $i = 0;
    $KeepI = 0;
    $KeepDo = "";
    $tdDo = "";
    $tmpId = "";
    $SumQty = 0;
    $SumWeight = 0;
    $style11 = "style='border-bottom:none;border-right:none;'";
    $style12 = "style='border-bottom:none;border-right:none;border-left:none;font-weight:bold;'";
    $style13 = "style='border-bottom:none;border-left:none;padding-top:90px;'";

    foreach ($DVRS as $ii => $rr) {
        $pack_qty = 0;
        $diff_pack_qty = 0;
        $ShowDo = $rr["do_num"];
        $rr["do_hdr_date"] ? $tmpHdrDate = $rr["do_hdr_date"]->format('Y-m-d H:i:s') : $tmpHdrDate = "";
        $rr["pickup_date"] ? $tmpPickupDate = $rr["pickup_date"]->format('Y-m-d H:i:s') : $tmpPickupDate = "";
        $bgcolor = "";
        $KeepDo = $rr["do_num"];
        if ($KeepI == 1) {
            $bgcolor = 'bgcolor2';
        }

        $headTable = "<table  class='table  style='width:100%;'>"
                . "<tr >"
                . "<td colspan='2'  $style11 >"
                . "<div  class='divbarcode'>*$ShowDo*</div>"
                . "<br><h6 >รหัสลูกค้า : <b>" . $rr["cust_num"] . "</b><h6><br>"
                . "<div  class='divbarcode'>*" . $rr["cust_num"] . "*</div>"
                . "<br><h6>นามลูกค้า : <b>" . $rr["consignee_name"] . "</h6></td>"
                . "<td colspan='3'   $style12 align='center' valign='top' ><b><h3>ใบส่งสินค้า</h3></b></td>"
                . "<td colspan='6' $style13 align='right' valign='middle' >วันที่เอกสาร : <b>$tmpHdrDate</b><br>วันที่ส่งของ : <b>$tmpPickupDate</b><br>เลขที่เอกสาร <b>$ShowDo"
                . "</td>"
                . "</tr>";

        $headTable2 = "<tr class='##page-break' >"
                . "<td align='center'>#</td>"
                . "<td align='center' width='40%'>รหัสสินค้า</td>"
                . "<td align='center'>Co.Order<br>P/O No.</td>"
                . "<td align='center'>Co.Line</td>"
                . "<td align='center'>Location</td>"
                . "<td align='center'>เส้น/มัด</td>"
                . "<td align='center'>จ.น.มัด<br>เศษ</td>"
                . "<td align='center'>รวมเส้น</td>"
                . "<td align='center'>หน่วยนับ</td>"
                . "<td align='center'>น.น./เส้น(KG.)</td>"
                . "<td align='center'>น.น.รวม</td>"
                . "</tr>";



        $tdDo = "";
        $tdDp2 = "";
        $tmpId = "DO_" . $rr["do_num"] . "_" . $rr["do_seq"];
        if ($rr["do_seq"] == 1) {
            $tdDo = "";
            $tdDp2 = "";
        } else {
            $tdDo = "style='visibility:hidden;'";
            $tdDp2 = "visibility:hidden;";
        }
        $lines = $lines . $headTable;
        $lines = $lines . $headTable2;
//        $DoSeq = new DeliveryOrder();
//        $DoSeq->setConn($ConnSL);
//        $DoSeqs = $DoSeq->GetDoSeq($ShowDo);
//        $DoSeq = null;
//        $CountItemToPageBreak = 0;
//
//        foreach ($DoSeqs as $iii => $rrr) {
//            $CountItemToPageBreak++;
//            $unit_weight = $rrr["unit_weight"];
//            $qty = $rrr["qty"];
//            $item_pack = $rrr["item_pack"];
//            $net_weight = $qty * $unit_weight;
//            $tmpDo = $rr["do_num"];
//            //====ไม่มีจำนวนเส้นต่อมัด ให้ qty เปน เศษทั้งหมด
//            if ($item_pack > 0) {
//                $pack_qty = 0;
//                $diff_pack_qty = $qty;
//            } else {
//                //======== มีจำนวนเส้นต่อมัด ===== ให้คำนวน
//                if (($qty > 0) && ($item_pack > 0)) {
//                    $pack_qty = floor($qty / $item_pack);
//                    $diff_pack_qty = $qty % $item_pack;
//                }
//            }
//            $SumQty = $SumQty + $qty;
//            $SumWeight = $SumWeight + $net_weight;
//
//            $pack_qty = number_format($pack_qty, 2);
//            $diff_pack_qty = number_format($diff_pack_qty, 2);
//            $qty = number_format($qty, 2);
//            $tClass = "";
//            if ($CountItemToPageBreak == 4) {
//                $tClass = "tr-page-break";
//                $CountItemToPageBreak = 0;
//            }
//            $lines = $lines . "<tr class='$tClass'>"
//                    . "<td align='center'>" . $rrr["do_seq"] . "</td>"
//                    . "<td align='left' width='20%'>" . $rrr["item"] . "<br>" . $rrr["item_desc"] . "<br><div class='divbarcode'>*" . $rrr["item"] . "*</div></td>"
//                    . "<td align='left'>" . $rrr["co_num"] . "<br>" . $rrr["cust_po"] . "</td>"
//                    . "<td align='center'>" . $rrr["co_line"] . "</td>"
//                    . "<td align='center'></td>"
//                    . "<td align='right'>$item_pack</td>"
//                    . "<td align='right'><h5>$pack_qty</h5><h5>$diff_pack_qty</h5></td>"
//                    . "<td align='right'>$qty</td>"
//                    . "<td align='center'>" . $rr["um"] . "</td>"
//                    . "<td align='right'>$unit_weight</td>"
//                    . "<td align='right'>$net_weight</td>"
//                    . "</tr>";
//            if ($CountItemToPageBreak == 4) {
//                
//            }
//        }
        $lines = $lines . "<tr>"
                . "<td colspan='6' align='left'>หมายเหตุ : </td>"
                . "<td  align='right'>รวม</td>"
                . "<td align='right'>" . number_format($SumQty) . "</td>"
                . "<td></td>"
                . "<td></td>"
                . "<td align='right'>" . number_format($SumWeight) . "</td>"
                . "</tr>";
        $lines = $lines . "</table><div class='page-break'></div>";
        $DoSeqs = null;
        $i++;
    }
    echo $lines;
} else if ($load == "ajax_sp") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $DVR = new DeliveryOrder();
    $DVR->setConn($ConnSL);
//    $DVR->_txtDoNumFrom = $txtDoNumFrom;
//    $DVR->_txtDoNumTo = $txtDoNumTo;
//    $DVR->_txtCoFrom = $txtCoFrom;
//    $DVR->_txtCoTo = $txtCoTo;
//    $DVR->_txtCustFrom = $txtCustFrom;
//    $DVR->_txtCustTo = $txtCustTo;
//    $DVR->_txtItemFrom = $txtItemFrom;
//    $DVR->_txtItemTo = $txtItemTo;
//    $DVR->_txtPickFrom = $txtPickFrom;
//    $DVR->_txtPickTo = $txtPickTo;
//    $DVR->_txtDoDateFrom = $txtDoDateFrom;
//    $DVR->_txtDoDateTo = $txtDoDateTo;
//    $DVR->_HdrOnly = "1";
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

        $query = sqlsrv_query($ConnSL, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        
    $DVRS = $DVR->GetDataWith_SP_DO_Hdr();

//    echo print_r($DVRS);
//    echo $DVRS;
//
//    echo count($DVRS);
    $lines = "";
    $lines2 = "";
    $tmpHdrDate = "";
    $tmpPickupDate = "";
    $tmpDo = "";
    $DVRS2 = null;
    $tmpHead = "";
    $pack_qty = 0;
    $item_pack = 0;
    $net_weight = 0;
    $qty = 0;
    $diff_pack_qty = 0;

//
    $ShowDo = "";
    $i = 0;
    $KeepI = 0;
    $KeepDo = "";
    $tdDo = "";
    $tmpId = "";
    $SumQty = 0;
    $SumWeight = 0;
    $style11 = "style='border-bottom:none;border-right:none;'";
    $style12 = "style='border-bottom:none;border-right:none;border-left:none;font-weight:bold;'";
    $style13 = "style='border-bottom:none;border-left:none;padding-top:90px;'";
    $SumWeightTotal = 0;
    $QtySumLineTotal = 0;

    foreach ($DVRS as $ii => $rr) {
        $ShowDo = "";
        $pack_qty = 0;
        $diff_pack_qty = 0;
        $ShowDo = $rr["do_num"];
        $rr["do_hdr_date"] ? $tmpHdrDate = $rr["do_hdr_date"]->format('Y-m-d H:i:s') : $tmpHdrDate = "";
        $rr["pickup_date"] ? $tmpPickupDate = $rr["pickup_date"]->format('Y-m-d H:i:s') : $tmpPickupDate = "";
        $bgcolor = "";
        $KeepDo = $rr["do_num"];
        if ($KeepI == 1) {
            $bgcolor = 'bgcolor2';
        }

        $headTable = "<table  class='table  style='width:100%;'>"
                . "<tr >"
                . "<td colspan='2'  $style11 >"
                . "<div  class='divbarcode'>*$ShowDo*</div>"
                . "<br><h6 >รหัสลูกค้า : <b>" . $rr["cust_num"] . "</b><h6>"
                . "<div  class='divbarcode'>*" . $rr["cust_num"] . "*</div>"
                . "<br><h6>นามลูกค้า : <b>" . $rr["name"] . "</h6></td>"
                . "<td colspan='3'   $style12 align='center' valign='top' ><b><h3>ใบส่งสินค้า</h3></b></td>"
                . "<td colspan='6' $style13 align='right' valign='middle' >วันที่เอกสาร : <b>$tmpHdrDate</b><br>วันที่ส่งของ : <b>$tmpPickupDate</b><br>เลขที่เอกสาร <b>$ShowDo"
                . "</td>"
                . "</tr>";

        $headTable2 = "<tr class='##page-break' >"
                . "<td align='center'>#</td>"
                . "<td align='center' width='40%'>รหัสสินค้า</td>"
                . "<td align='center'>Co.Order<br>P/O No.</td>"
                . "<td align='center'>Co.Line</td>"
                . "<td align='center'>Location</td>"
                . "<td align='center'>เส้น/มัด</td>"
                . "<td align='center'>จ.น.มัด<br>เศษ</td>"
                . "<td align='center'>รวมเส้น</td>"
                . "<td align='center'>หน่วยนับ</td>"
                . "<td align='center'>น.น./เส้น(KG.)</td>"
                . "<td align='center'>น.น.รวม</td>"
                . "</tr>";
        $tdDo = "";
        $tdDp2 = "";
        $tmpId = "DO_" . $rr["do_num"];
//        if ($rr["do_seq"] == 1) {
//            $tdDo = "";
//            $tdDp2 = "";
//        } else {
//            $tdDo = "style='visibility:hidden;'";
//            $tdDp2 = "visibility:hidden;";
//        }
        $lines = $lines . $headTable;
        $lines = $lines . $headTable2;
        $DoSeqs = null;
        $DoSeq = new DeliveryOrder();
        $DoSeq->setConn($ConnSL);
        $DoSeq->_txtDoNum = $ShowDo;
        $DoSeqs = $DoSeq->GetDataWith_SP_DO_Line();
        $DoSeq = null;
        $CountItemToPageBreak = 0;
        $SumWeightTotal = 0;
        $QtySumLineTotal = 0;
        $unit_weight=0;
//        echo count($DoSeqs);
        if (count($DoSeqs) > 0) {
//            echo print_r($DoSeqs);
            foreach ($DoSeqs as $iii => $rrr) {
                $CountItemToPageBreak++;
                $tmpDo = $rr["do_num"];
                $unit_weight = $rrr["unit_weight"];
                $QtyBundle = $rrr["QtyBundle"];
                $QtyRemainder = $rrr["QtyRemainder"];
                $Uf_Pack = $rrr["Uf_Pack"];
                $QtySumLine = ( $Uf_Pack * $QtyBundle ) + $QtyRemainder;
                $SumWeightLine = (( $Uf_Pack * $QtyBundle ) + $QtyRemainder) * $unit_weight;
                $tClass = "";
                if ($CountItemToPageBreak == 4) {
                    $tClass = "tr-page-break";
                    $CountItemToPageBreak = 0;
                }
                $SumWeightTotal = $SumWeightTotal + $SumWeightLine;
                $QtySumLineTotal = $QtySumLineTotal + $QtySumLine;
                $unit_weight = number_format($unit_weight, 5);
                $QtySumLine = number_format($QtySumLine, 0);
                $SumWeightLine = number_format($SumWeightLine, 5);
                
                $lines = $lines . "<tr class='$tClass'>"
                        . "<td align='center'>" . $rrr["do_seq"] . "</td>"
                        . "<td align='left' width='20%'>" . $rrr["item"] . "<br>" . $rrr["description"] . "<br><div class='divbarcode'>*" . $rrr["item"] . "*</div></td>"
                        . "<td align='left'>" . $rrr["Co_num"] . "<br>" . $rrr["cust_po"] . "</td>"
                        . "<td align='center'>" . $rrr["Co_line"] . "</td>"
                        . "<td align='center'>" . $rrr["loc"] . "</td>"
                        . "<td align='right'>$Uf_Pack</td>"
                        . "<td align='right'><h5>$QtyBundle</h5><h5>$QtyRemainder</h5></td>"
                        . "<td align='right'>$QtySumLine</td>"
                        . "<td align='center'>" . $rrr["u_m"] . "</td>"
                        . "<td align='right'>$unit_weight</td>"
                        . "<td align='right'>$SumWeightLine</td>"
                        . "</tr>";
            }

            $lines = $lines . "<tr>"
                    . "<td colspan='6' align='left'>หมายเหตุ :" . $rrr["NoteContent"] . "</td>"
                    . "<td  align='right'>รวม</td>"
                    . "<td align='right'>" . $QtySumLineTotal . "</td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td align='right'>" . number_format($SumWeightTotal, 5) . "</td>"
                    . "</tr>";
        }

        $lines = $lines . "</table><div class='page-break'></div>";
        $DoSeqs = null;
//        $i++;
    }


    echo $lines;
}
