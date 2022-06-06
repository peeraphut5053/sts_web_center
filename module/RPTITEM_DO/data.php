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
    $Item->_cust_name = $txtCustName;
    $Item->_start_shipdate = $txtFromDate;
    $Item->_end_shipdate = $txtToDate;
    $Items = $Item->GetItemDO();
    $lines = "";

    $i = 0;
    
    foreach ($Items as $ii => $rr) {
        $i++;
        $saling_qty_bndl = $lines = $lines . "<tr>"
                . "<td align='center'>$i</td>"
                . "<td align='center'>" . $rr["ship_date"]->format('Y-m-d') . "</td>"
                . "<td align='left'>" . $rr["ref_num"] . "</td>"
                . "<td align='left'>" . $rr["do_num"] . "</td>"
                . "<td align='left'>" . $rr["cust_name"] . "</td>"
                . "<td align='left'>" . $rr["item_code"] . "<br>" . $rr["item_desc"] . "</td>"
                . "<td align='right'>" . number_format($rr["qty_shipped"]  , 2) . "</td>"
                . "<td align='left' >" . $rr["lot"] . "</td>"              
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
