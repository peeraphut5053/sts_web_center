<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

//$CM = new CallModel();
//$CM->SyteLine_Models();
//$Location = new SlLocation();
//$Location->setConn($ConnSL);
//$Locations = $Location->GetRowsAll();

$result = "";
//foreach ($Locations as $key => $value) {
//    $result = $result . "<option value='" . $value["loc"] . "'>" . $value["description"] . "</option>";
//}
//$CM = null;
//$Locations = null;
//$Location = null;
$txtDoNumFrom=$_POST["txtDoNumFrom"];
$txtDoNumTo=$_POST["xtDoNumTo"];
$txtCoFrom=$_POST["txtCoFrom"];
$txtCoTo=$_POST["txtCoTo"];
$txtItemFromm=$_POST["txtItemFrom"];
$txtItemTo=$_POST["txtItemTo"];
$txtPickFrom=$_POST["txtPickFrom"];
$txtPickTo=$_POST["txtPickTo"];
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$DVR = new DeliveryOrder();
$DVR->setConn($ConnSL);
$DVR->_txtDoNumFrom = $txtDoNumFrom;
$DVR->_txtDoNumTo = $txtDoNumTo;
$DVR->_txtCoFrom = $txtCoFrom;
$DVR->_txtCoTo = $txtCoTo;
$DVR->_txtItemFrom = $txtItemFrom;
$DVR->_txtItemTo = $txtItemTo;
$DVR->_txtPickFrom = $txtPickFrom;
$DVR->_txtPickTo = $txtPickTo;
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
//    echo $DVRS;

$ShowDo = "";
$i = 0;
$KeepI = 0;
$KeepDo = "";
$tdDo = "";
$tmpId = "";
foreach ($DVRS as $ii => $rr) {
    $pack_qty = 0;
    $diff_pack_qty = 0;
    $ShowDo = $rr["do_num"];
    $rr["do_hdr_date"] ? $tmpHdrDate = "วันที่ส่งของ :" . $rr["do_hdr_date"]->format('Y-m-d H:i:s') : $tmpHdrDate = "";
    $rr["pickup_date"] ? $tmpPickupDate = "วันที่เอกสาร :" . $rr["pickup_date"]->format('Y-m-d H:i:s') : $tmpPickupDate = "";

    $bgcolor = "";

    $KeepDo = $rr["do_num"];
    if ($KeepI == 1) {
        $bgcolor = 'bgcolor2';
    }
    $unit_weight = $rr["unit_weight"];
    $qty = $rr["qty"];
    $item_pack = $rr["item_pack"];
    $net_weight = $qty * $unit_weight;
    $tmpDo = $rr["do_num"];
    //====ไม่มีจำนวนเส้นต่อมัด ให้ qty เปน เศษทั้งหมด
    if ($item_pack > 0) {
        $pack_qty = 0;
        $diff_pack_qty = $qty;
    } else {
        //======== มีจำนวนเส้นต่อมัด ===== ให้คำนวน
        if (($qty > 0) && ($item_pack > 0)) {
            $pack_qty = floor($qty / $item_pack);
            $diff_pack_qty = $qty % $item_pack;
        }
    }

    $lines2 = "";
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

    $pack_qty = number_format($pack_qty, 2);
    $diff_pack_qty = number_format($diff_pack_qty, 2);
    $qty = number_format($qty, 2);
    $unit_weight = number_format($unit_weight, 2);
    $net_weight = number_format($net_weight, 2);
    $lines = $lines . "<tr id='$tmpId' >"
            . "<td  align='left'>"
            . "<h5 $tdDo>$ShowDo</h5><div style='font-size:30px;$tdDp2'  class='divbarcode'>$ShowDo</div>"
            . "<h6 $tdDo>$tmpPickupDate</h6>"
            . "<h6 $tdDo>$tmpHdrDate</h6></td>"
            . "<td  align='left'>" . $rr["do_seq"] . "</td>"
            . "<td  align='left'>" . $rr["item"] . "</h5><h6>" . $rr["description"] . "</h6><div style='font-size:24px;width:400px;margin-top:50px;'  class='divbarcode'>" . $rr["item"] . "</div></td>"
            . "<td  align='left'>" . $rr["co_num"] . "</td>"
            . "<td  align='left'>" . $rr["co_line"] . "</td>"
            . "<td  align='left'>" . $rr["cust_po"] . "</td>"
            . "<td  align='left'>$item_pack</td>"
            . "<td  align='right'><h5>$pack_qty</h5><h5>$diff_pack_qty</h5></td>"
            . "<td  align='right'><h5>$qty</h5></td>"
            . "<td  align='center'>" . $rr["um"] . "</td>"
            . "<td  align='right'>$unit_weight</td>"
            . "<td  align='right'>$net_weight</td>"
            . "</tr>";

    $i++;
}



$temp->setReplace("{content}", $temp->getTemplate("./template/RPTDVR/render_report.html"));
$temp->setReplace("{table_data}", $lines);

// $temp->setReplace("{ConSlVal}", $ConnWebAppSL);
