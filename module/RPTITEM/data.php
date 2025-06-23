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
    $Item->_atYear = $atYear;
    $Items = $Item->GetItemLocation();

    
    
    $lines = "";
    $i = 0;
//    while ($rr = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    foreach ($Items as $ii => $rr) {
        $i++;
        $pack = 0;
//        $unit_weight = $rr["unit_weight"];
        $saling_qty = 0;
        $shipping_qty = 0;
        $shipped_qty = 0;
        $saling_qty_bndl = $lines = $lines . "<tr>"
                . "<td align='center'>$i</td>"
                . "<td align='left'>" . $rr["item_desc"] . "</td>"
                . "<td align='right'>" . $rr["item_nps"] . "</td>"
                . "<td align='right'>" . $rr["item_thick"] . "</td>"
                . "<td align='right'>" . $rr["item_width"] . "</td>"
                . "<td align='right'>" . $rr["item_pack"] . "</td>"
                . "<td align='right'>" . number_format($rr["item_weight"], 2) . "</td>"
                . "<td align='right'>"
                . "<a  OnClick='CheckQty(this.id);' id='CheckQty!!$i!!" . $rr["item_code"] . "!!'>" . number_format($rr["sum_qty_oh"], 2) . "</a></td>"
                . "<td align='right'>"
                . "<a  OnClick='BetweenSale(this.id);' id='betweenSale!!$i!!" . $rr["item_code"] . "!!'>" . number_format($rr["sum_qty_saling"], 2) . "</a></td>"
                . "<td align='right'>"
                . "<a OnClick='Shipping(this.id);' id='shipping!!$i!!" . $rr["item_code"] . "!!'>" . number_format($rr["sum_qty_shipping"], 2) . "</a></td>"
//                . "<a OnClick='Shipping(this.id);' id='shipping!!$i!!" . $rr["item_code"] . "!!'>" . number_format($rr["sum_qty_shipping"], 2) . "</a></td>"
//                . "<td align='right'>"
//                . "<a OnClick='Shipped(this.id);' id='shipped!!$i!!" . $rr["item_code"] . "!!' >" . number_format($rr["item_shipped_count"], 2) . "</a></td>"
//                . "<td></td>"
                . "</tr>";
    }
    echo $lines;
} else if ($load == "CheckQty") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $CO = new CustomerOrder();
    $CO->setConn($ConnSL);
    $BetweenSale = $CO->CheckQty($item);
    $totalQty = 0;
    $lines = "";
    $tableHead = '<thead><tr><th>#</th><th>Loc</th><th>Loc Description</th><th>Lot</th><th>qty on hand</th><th>Act Weight</th><th>Tag</th></tr></thead>';

    if (count($BetweenSale) >= 1) {
        $i = 0;
        foreach ($BetweenSale as $ii => $rr) {
            $i++;
            $totalQty = $totalQty + $rr["qty_on_hand"];
            $qty = number_format($rr["qty_on_hand"], 2);
            $lines = $lines . "<tr>"
                    . "<td align='center'>$i</td>"
                    . "<td align='center'>" . $rr["loc"] . "</td>"
                    . "<td align='center'>" . $rr["description"] . "</td>"
                    . "<td align='center'>" . $rr["lot"] . "</td>"
                    . "<td align='right'>$qty</td>"
                    . "<td align='center'>" . $rr["uf_act_weight"] . "</td>"
                    . "<td align='center'>" . $rr["tag"] . "</td>"
                    . "</tr>";
        }
        $lines = $lines . "<tr>"
                . "<td align='center'></td><td align='center'>Total</td>"
                . "<td></td>"
                . "<td></td>"
                . "<td align='right'>" . number_format($totalQty, 2) . "</td>"
                . "</tr>";
    } else {
        $lines = $lines . "<tr><td  align='center' colspan='5'>..No data..</td></tr>";
    }
    $tlines = $tableHead . $lines;
    echo $tlines;
} else if ($load == "BetweenSale") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $CO = new CustomerOrder();
    $CO->setConn($ConnSL);
    $CO->_atYear = $atYear;
    $BetweenSale = $CO->GetRowsBetweenSale($item);
    $lines = "";
    $unit_weight = 0;
    $qty = 0;
    $bndl = 0;
    $totalQty = 0;
    $tableHead = '<thead>
                        <tr>
                            <th>#</th>   
                            <th>Order_Date</th>                            
                            <th>Cust_name</th> 
                            <th>Co_num</th> 
                            <th>Co_line</th>                            
                            <th>qty</th>
                        </tr>
                    </thead>';

    $tempLoc = "";
    if (count($BetweenSale) >= 1) {
        $i = 0;
        foreach ($BetweenSale as $ii => $rr) {
            $i++;
            $qty = $rr["qty_remain"];
            $totalQty = $totalQty + $qty;
            $qty = number_format($qty, 2);
//             if ($rr["loc_desc"]) {
//                $tempLoc = $rr["loc_desc"];
//            } else {
//                $tempLoc = "<i style='color:red;'>ยังไม่ขึ้นของ</i>";
//            }
            $lines = $lines . "<tr>"
                    . "<td align='center'>$i</td>"
                    . "<td>" . $rr["Order_date"]->format('d/m/Y') . "</td>"
                    . "<td>" . $rr["cust_name"] . "</td>"
                    . "<td>" . $rr["co_num"] . "</td>"
                    . "<td align='center'>" . $rr["co_line"] . "</td>"
                    . "<td align='right'>$qty</td>"
                    . "</tr>";
        }
        $lines = $lines . "<tr>"
                . "<td align='center'></td><td>Total</td>"
                . "<td></td>"
                . "<td></td>"
                . "<td align='center'></td>"
                . "<td align='right'>" . number_format($totalQty, 2) . "</td>"
                . "</tr>";
    } else {
        $lines = $lines . "<tr><td  align='center' colspan='5'>..No data..</td></tr>";
    }
    echo $tableHead . $lines;
} else if ($load == "GetRowsShipping") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $CO = new CustomerOrder();
    $CO->_atYear = $atYear;
    $CO->setConn($ConnSL);
    $BetweenSale = $CO->GetRowsShipping($item, $loc);
    $lines = "";
    $unit_weight = 0;
    $qty = 0;
    $bndl = 0;
    $shipdate = "";
    $totalQty = 0;
    $tableHead = '<thead>
                        <tr>
                            <th>#</th>   
                            <th>pick_date</th>                           
                            <th>Cust_name</th> 
                            <th>Co_num</th> 
                            <th>Co_line</th>    
                            <th>Do_num</th> 
                            <th>Do_line</th>  
                            <th>qty</th>
                        </tr>
                    </thead>';


    if (count($BetweenSale) >= 1) {
        $i = 0;
        foreach ($BetweenSale as $ii => $rr) {
            $i++;
            $qty = $rr["qty_shipped"];
            $totalQty = $totalQty + $qty;
            $qty = number_format($qty, 2);
            if ($rr["pickup_date"]) {
                $shipdate = $rr["pickup_date"]->format("d/m/Y");
            }
            $lines = $lines . "<tr>"
                    . "<td align='center'>$i</td>"
                    . "<td>$shipdate</td>"
                    . "<td align='center'>" . $rr["cust_name"] . "</td>"
                    . "<td align='center'>" . $rr["co_num"] . "</td>"
                    . "<td align='center'>" . $rr["co_line"] . "</td>"
                    . "<td align='center'>" . $rr["do_num"] . "</td>"
                    . "<td align='center'>" . $rr["do_line"] . "</td>"
                    . "<td align='right'>$qty</td>"
                    . "</tr>"
                    . "</thead>";
        }
        $lines = $lines . "<tr>"
                . "<td align='center'></td><td>Total</td>"
                . "<td></td>"
                . "<td></td>"
                . "<td align='center'></td>"
                . "<td align='center'></td>"
                . "<td align='center'></td>"
                . "<td align='right'>" . number_format($totalQty, 2) . "</td>"
                . "</tr>";
    } else {
        $lines = $lines . "<tr><td colspan='8' align='center'>..No data..</td></tr>";
    }


    echo $tableHead . $lines;
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
    $totalQty = 0;
    if (count($BetweenSale) >= 1) {
        $i = 0;
        foreach ($BetweenSale as $ii => $rr) {
            $i++;
            $qty = $rr["qty_shipped"];
            $totalQty = $totalQty + $qty;
            $qty = number_format($qty, 2);
            $lines = $lines . "<tr>"
                    . "<td align='center'>$i</td><td>" . $rr["ship_date"]->format("d/m/Y") . "</td>"
                    . "<td>" . $rr["cust_name"] . "</td>"
                    . "<td>" . $rr["co_num"] . "</td>"
                    . "<td align='center'>" . $rr["co_line"] . "</td>"
                    . "<td align='center'>" . $rr["do_num"] . "</td>"
                    . "<td align='center'>" . $rr["do_line"] . "</td>"
                    . "<td align='right'>$qty</td>"
                    . "</tr>"
                    . "</thead>";
        }
        $lines = $lines . "<tr>"
                . "<td align='center'></td><td>Total</td>"
                . "<td></td>"
                . "<td></td>"
                . "<td align='center'></td>"
                . "<td align='center'></td>"
                . "<td align='center'></td>"
                . "<td align='right'>" . number_format($totalQty, 2) . "</td>"
                . "</tr>";
    } else {
        $lines = $lines . "<tr><td colspan='5' align='center'>..No data..</td></tr>";
    }


    echo $lines;
}
