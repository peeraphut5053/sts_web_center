<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";




if ($load == "form") {
    
} else if ($load == "ajax") {
    if (isset($_POST["Locs"])) {
        $Locations = $_POST["Locs"];
    }

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $ItemSL = new ItemSyteLine();
    $ItemSL->setConn($ConnSL);
    $lines = "";
    $i = 0;
    $ItemSL->_thick = $Thick;
    $ItemSL->_width = $Width;

    $Thicks = $ItemSL->GetItem_Thickness_List();
    $TotalRoll = 0;
    $TotalWeight = 0;
    $lines_result = "";
    $GrandTotalQty = 0;
    $GrandTotalWeight = 0;
    $cb1 = "";
    if (isset($cb)) {
        $cb1 = $cb;
    }

    foreach ($Thicks as $ii => $rr) {
        $ItemSL->_rpt_thick = $rr["uf_thickness"];
        if (isset($Locations)) {
            $ItemSL->_locations = $Locations;
        }
        $ItemSL->_cb = $cb1;
        $ItemSL->_StartDate = $txtStartDate;
        $ItemSL->_ToDate = $txtToDate;

        $Items = $ItemSL->GetItemHR();
//        echo $Items ;
        $i = 0;
        $TotalRoll = 0;
        $TotalWeight = 0;
        $itemGroupName = [];
        $TotalRoll = 0;
        $TotalWeight = 0;
        $flagGetSubComplete = 0;
        if ($Items) {
//            $lines = $lines . "<tr>"
//                    . "<td  align='left' colspan='9'><b>ความหนา " . $rr["uf_thickness"] . "</b></td></tr>";

            foreach ($Items as $iii => $rrr) {
                $i++;
                if ($i == 1) {
                    $itemGroupName = explode("-", $rrr["item_code"]);
                }
                $lines = $lines . "<tr>"
                        . "<td  align='left' >" . $rrr["item_code"] . "</td>"
                        . "<td align='center' >" . $rrr["item_desc"] . "</td>"
                        . "<td align='center'>" . $rrr["Uf_thickness"] . "</td>"
                        . "<td align='center'>" . $rrr["item_width"] . "</td>"
                        . "<td align='center'>" . $rrr["item_standard"] . "</td>"
                        . "<td align='center'>" . $rrr["loc_desc"] . "</td>"
                        . "<td align='center'>" . $rrr["create_date"]->format("Y-m-d") . "</td>"
                        . "<td align='center'>" . $rrr["serial_no"] . "</td>"
                        . "<td align='right'>" . $rrr["roll"] . "</td>"
                        . "<td align='right'>" . number_format($rrr["qty_on_hand"], 2) . "</td>"
                        . "</tr>";
                $TotalRoll = $TotalRoll + $rrr["roll"];
                $TotalWeight = $TotalWeight + $rrr["qty_on_hand"];
            }
            $lines = $lines . "<tr>"
                    . "<td><b>Total Item : <u>" . $itemGroupName[0] . "-" . $itemGroupName[1] . "</u></b></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td align='left'></td>"
                    . "<td align='right'><b><u>$TotalRoll</u></b></td>"
                    . "<td align='right'><b><u>" . number_format($TotalWeight, 2) . "</u></b></td>"
                    . "</tr>";
        }
        $GrandTotalQty = $GrandTotalQty + $TotalRoll;
        $GrandTotalWeight = $GrandTotalWeight + $TotalWeight;
    }
    $wLoc = "";
    
    if (isset($Locations)) {
        foreach ($Locations as $li => $ri) {
            $wLoc = $wLoc . " " . $ri;
        }
    }

    if ($Thick != "") {
        $Thick = " Thick : " . $Thick;
    }
    if ($Width != "") {
        $Width = " Width : " . $Width;
    }
    $lines = $lines . "<tr>"
            . "<td><b>Grand Total for Location : $wLoc    $Thick   $Width </b></td>"
            . "<td></td>"
            . "<td></td>"
            . "<td></td>"
            . "<td></td>"
            . "<td></td>"
            . "<td></td>"
            . "<td align='left'></td>"
            . "<td align='right'><b><u>" . number_format($GrandTotalQty, 2) . "</u></b></td>"
            . "<td align='right'><b><u>" . number_format($GrandTotalWeight, 2) . "</u></b></td>"
            . "</tr>";
    $ItemSL = null;
    $Items = null;
    $CM = null;
    echo $lines;
} 