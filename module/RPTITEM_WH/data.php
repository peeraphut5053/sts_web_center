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
    $Item = new ItemSyteLine();
    $Item->setConn($ConnSL);
    $Item->_rpt_item = $txtItem;
    $Item->_rpt_size = $txtSize;
    $Item->_rpt_thick = $txtThick;
    $Item->_rpt_width = $txtWidth;
    $Item->_rpt_stock = $stock;
    $Locations = $_POST["Locs"];
    $Item->_locations = $Locations;
    $Items = $Item->GetItemBigLocation();
    $lines = "";
    $unit_weight = "";
    $saling_qty_bndl = 0;
    $saling_qty = 0;
    $saling_qty_diff = 0;
    $shipping_qty_diff = 0;
    $shipped_qty_diff = 0;
    $shipping_qty_bndl = 0;
    $shipping_qty = 0;
    $shipped_qty_bndl = 0;
    $shipped_qty = 0;
    $remain_qty = 0;
    $remain_qty_bndl = 0;
    $pack = 0;
    $i = 0;
    foreach ($Items as $ii => $rr) {
        $i++;
        $saling_qty_bndl = 0;
        $saling_qty = 0;
        $saling_qty_diff = 0;
        $shipping_qty_diff = 0;
        $shipped_qty_diff = 0;
        $shipping_qty_bndl = 0;
        $shipping_qty = 0;
        $shipped_qty_bndl = 0;
        $shipped_qty = 0;
        $remain_qty = 0;
        $remain_qty_bndl = 0;
        $pack = 0;

        $unit_weight = $rr["unit_weight"];
        $saling_qty = $rr["sum_qty_saling"];
        $shipping_qty = $rr["sum_qty_shiping"];
//        $shipped_qty = $rr["sum_qty_shipped"];
        $remain_qty = $rr["qty_on_hand"];
        $pack = $rr["Uf_pack"];
        $saling_qty = number_format($saling_qty, 2);
        $shipping_qty = number_format($shipping_qty, 2);
//        $shipped_qty = number_format($shipped_qty, 2);



        $saling_qty_bndl = $lines = $lines . "<tr>"
                . "<td align='center'>$i</td>"
                . "<td align='left'>" . $rr["item_code"] . "<br>" . $rr["item_desc"] . "</td>"
                . "<td align='right'>" . $rr["item_size"] . "</td>"
                . "<td align='right'>" . $rr["item_thick"] . "</td>"
                . "<td align='right'>" . $rr["item_width"] . "</td>"
                . "<td align='right'>" . $rr["Uf_pack"] . "</td>"
                . "<td align='right'>" . number_format($unit_weight, 2) . "</td>"
                . "<td align='left' >" . $rr["loc_desc"] . "</td>"
                . "<td align='right'>" . number_format($remain_qty, 2) . "</td>"
                . "<td align='right'>"
                . "<a  OnClick='BetweenSale(this.id);' id='betweenSale!!$ii!!" . $rr["item_code"] . "!!" . $rr["loc"] . "'>$saling_qty</a></td>"
                . "<td align='right'>"
                . "<a OnClick='Shipping(this.id);' id='shipping!!$ii!!" . $rr["item_code"] . "!!" . $rr["loc"] . "'>$shipping_qty</a></td>"
//                . "<td align='right'>"
//                . "<a OnClick='Shipped(this.id);' id='shipped!!$ii!!" . $rr["item_code"] . "!!" . $rr["loc"] . "' >$shipped_qty</a></td>"
//                . "<td></td>"
                . "</tr>";
    }
    echo $lines;
} else if ($load == "BetweenSale") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $CO = new CustomerOrder();
    $CO->setConn($ConnSL);
    $BetweenSale = $CO->GetRowsBetweenSale($item, $loc);
    $lines = "";
    $unit_weight = 0;
    $qty = 0;
    $bndl = 0;
    if (count($BetweenSale) >= 1) {
        $i = 0;
        foreach ($BetweenSale as $ii => $rr) {
            $i++;
            $qty = $rr["qty_ordered"] - $rr["qty_shipped"];
            $qty = number_format($qty, 2);
            $lines = $lines . "<tr>"
                    . "<td align='center'>$i</td><td>" . $rr["CreateDate"]->format('d/m/Y') . "</td>"
                    . "<td>" . $rr["cust_name"] . "</td>"
                    . "<td>" . $rr["co_num"] . "</td>"
                    . "<td align='center'>" . $rr["co_line"] . "</td>"
                    . "<td align='center'></td>"
                    . "<td align='center'></td>"
                    . "<td align='right'>$qty</td>"
                    . "</tr>"
                    . "</thead>";
        }
    } else {
        $lines = $lines . "<tr><td  align='center' colspan='5'>..No data..</td></tr>";
    }
    echo $lines;
} else if ($load == "GetRowsShipping") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $CO = new CustomerOrder();
    $CO->setConn($ConnSL);
    $BetweenSale = $CO->GetRowsShipping($item, $loc);
    $lines = "";
    $unit_weight = 0;
    $qty = 0;
    $bndl = 0;
    if (count($BetweenSale) >= 1) {
        $i = 0;
        foreach ($BetweenSale as $ii => $rr) {
            $i++;
            $qty = $rr["qty"];
            $qty = number_format($qty, 2);
            $lines = $lines . "<tr>"
                    . "<td align='center'>$i</td><td>" . $rr["CreateDate"]->format("d/m/Y") . "</td>"
                    . "<td>" . $rr["cust_name"] . "</td>"
                    . "<td>" . $rr["co_num"] . "</td>"
                    . "<td align='center'>" . $rr["co_line"] . "</td>"
                    . "<td align='center'>" . $rr["do_num"] . "</td>"
                    . "<td align='center'>" . $rr["do_line"] . "</td>"
                    . "<td align='right'>$qty</td>"
                    . "</tr>"
                    . "</thead>";
        }
    } else {
        $lines = $lines . "<tr><td colspan='5' align='center'>..No data..</td></tr>";
    }


    echo $lines;
} else if ($load == "GetRowsShipped") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $CO = new CustomerOrder();
    $CO->setConn($ConnSL);
    $BetweenSale = $CO->GetRowsShipped($item, $loc);
    $lines = "";
    $unit_weight = 0;
    $qty = 0;
    $bndl = 0;
    if (count($BetweenSale) >= 1) {
        $i = 0;
        foreach ($BetweenSale as $ii => $rr) {
            $i++;
            $qty = $rr["qty_shipped"];
            $qty = number_format($qty, 2);
            $lines = $lines . "<tr>"
                    . "<td align='center'>$i</td><td>" . $rr["CreateDate"]->format("d/m/Y") . "</td>"
                    . "<td>" . $rr["cust_name"] . "</td>"
                    . "<td>" . $rr["co_num"] . "</td>"
                    . "<td align='center'>" . $rr["co_line"] . "</td>"
                    . "<td align='center'>" . $rr["do_num"] . "</td>"
                    . "<td align='center'>" . $rr["do_line"] . "</td>"
                    . "<td align='right'>$qty</td>"
                    . "</tr>"
                    . "</thead>";
        }
    } else {
        $lines = $lines . "<tr><td colspan='5' align='center'>..No data..</td></tr>";
    }


    echo $lines;
}
