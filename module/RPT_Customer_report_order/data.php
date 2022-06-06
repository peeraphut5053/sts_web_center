<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";




if ($load == "form") {
    
} else if ($load == "ajax") {
    if (isset($_POST["Locs"])) {
//        $Locations = $_POST["Locs"];
        $Locations = 1;
    }

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $ItemSL = new CustomerOrder();
    $ItemSL->setConn($ConnSL);
    $lines = "";
    $i = 0;
//    $ItemSL->_thick = $Thick;
//    $ItemSL->_width = $Width;

    $Thicks = $ItemSL->Customer_report_order($txtStartDate, $txtToDate);
//    print_r($Thicks[6]["co_num"]);
//    print_r($Thicks[7]["co_num"]);
//    echo $Thicks[7]["co_num"];
//    print_r($Thicks);
    $TotalRoll = 0;
    $TotalWeight = 0;
    $lines_result = "";
    $GrandTotalQty = 0;
    $GrandTotalWeight = 0;
    $cb1 = "";
    if (isset($cb)) {
        $cb1 = $cb;
    }
    $j = 1;
    do {

        if ($j == 1) {
            
            
           
            
            
            $lines = $lines . "<tr>"
                    . "<td  align='left' >" . $Thicks[$j]["OrderDate"]->format("Y-m-d") . "</td>"
                    . "<td  align='left' >" . $Thicks[$j]["co_num"] . "</td>"
                    . "<td  align='left' >" . $Thicks[$j]["custName"] . "</td>"
                    . "<td  align='left' >" . $Thicks[$j]["ShipName"] . "</td>"
                    . "<td  align='left' >" . $Thicks[$j]["cust_po"] . "</td>"
                    . "<td  align='left' >" . $Thicks[$j]["terms_code"] . "</td>"
                    . "<td align='center'>" . $Thicks[$j]["co_line"] . "</td>"
                    . "<td align='center'>" . $Thicks[$j]["ItemDesc"] . "</td>"
                    . "<td align='center'>" . $Thicks[$j]["qty_ordered_conv"] . "</td>"
                    . "<td align='center'>" . $Thicks[$j]["u_m"] . "</td>"
                    . "<td align='right'>" . number_format($Thicks[$j]["UnitPrice"], 2) . "</td>"
                    . "<td align='right'>" . number_format($Thicks[$j]["Uf_PricePerKG"], 2) . "</td>"
                    . "<td align='right'>" . number_format($Thicks[$j]["NetPrice"], 2) . "</td>"
                    . "<td align='left'>" . $Thicks[$j]["uf_note"] . "</td>"
                    . "</tr>";

        } else {


            $lines = $lines . "<tr>"
                    . "<td  align='left' >" . (($Thicks[$j]["co_num"] !== $Thicks[$j - 1]["co_num"]) ? $Thicks[$j]["OrderDate"]->format("Y-m-d") : "") . "</td>"
                    . "<td  align='left' >" . (($Thicks[$j]["co_num"] !== $Thicks[$j - 1]["co_num"]) ? $Thicks[$j]["co_num"] : "") . "</td>"
                    . "<td  align='left' >" . (($Thicks[$j]["co_num"] !== $Thicks[$j - 1]["co_num"]) ? $Thicks[$j]["custName"] : "") . "</td>"
                    . "<td  align='left' >" . (($Thicks[$j]["co_num"] !== $Thicks[$j - 1]["co_num"]) ? $Thicks[$j]["ShipName"] : "") . "</td>"
                    . "<td  align='left' >" . (($Thicks[$j]["co_num"] !== $Thicks[$j - 1]["co_num"]) ? $Thicks[$j]["cust_po"] : "") . "</td>"
                    . "<td  align='left' >" . (($Thicks[$j]["co_num"] !== $Thicks[$j - 1]["co_num"]) ? $Thicks[$j]["terms_code"] : "") . "</td>"
                    . "<td align='center'>" . $Thicks[$j]["co_line"] . "</td>"
                    . "<td align='center'>" . $Thicks[$j]["ItemDesc"] . "</td>"
                    . "<td align='center'>" . $Thicks[$j]["qty_ordered_conv"] . "</td>"
                    . "<td align='center'>" . $Thicks[$j]["u_m"] . "</td>"
                    . "<td align='right'>" . number_format($Thicks[$j]["UnitPrice"], 2) . "</td>"
                    . "<td align='right'>" . number_format($Thicks[$j]["Uf_PricePerKG"], 2) . "</td>"
                    . "<td align='right'>" . number_format($Thicks[$j]["NetPrice"], 2) . "</td>"
                    . "<td align='left'>" . $Thicks[$j]["uf_note"] . "</td>"
                    . "</tr>";
        }







        $j++;
    } while ($j < count($Thicks));



//    for ($j = 1; $j < count($Thicks); $j++) {
//        if ($j - 1 !== 0) {
////            if ($Thicks[$j]["co_num"] !== $Thicks[$j - 1]["co_num"]) {
////                $lines = $lines . "<tr> <td  align='left' colspan='10'><b>ความหนา " . $Thicks[$j]["co_num"] . "</b></td></tr>";
////            }
//            $lines = $lines . "<tr>"
//                    . "<td  align='left' >".(($Thicks[$j]["co_num"] !== $Thicks[$j - 1]["co_num"])? $Thicks[$j]["co_num"]:"")."</td>"
//                    . "<td align='center' >0</td>"
//                    . "<td align='center'>" . $Thicks[$j]["co_num"] . "</td>"
//                    . "<td align='center'>" . $Thicks[$j]["co_line"] . "</td>"
//                    . "<td align='center'>" . $Thicks[$j]["co_num"] . "</td>"
//                    . "<td align='center'>" . $Thicks[$j]["co_num"] . "</td>"
//                    . "<td align='center'>" . $Thicks[$j]["OrderDate"]->format("Y-m-d") . "</td>"
//                    . "<td align='center'>" . $Thicks[$j]["co_num"] . "</td>"
//                    . "<td align='right'>" . $Thicks[$j]["co_num"] . "</td>"
//                    . "<td align='right'>" . number_format($Thicks[$j]["co_line"], 2) . "</td>"
//                    . "</tr>";
//        }
//    }
//    

    echo $lines;
} 