<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//
$getForm = "";
if (!empty($_GET["form"])) {
    $getForm = $_GET["form"];
}
//if ($getForm == "view") {
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");
$temp->setReplace("{content}", $temp->getTemplate("./template/bp_report.html"));
//}

$formAction = "";
if (isset($_POST["formAction"])) {
    $formAction = $_POST["formAction"];
}

//$HeadCode = "";
//$action = "";
$selBoat = "";
$fromDate = "";
$toDate = "";
$boatLighterNo = "";
$reportContent = "";
$tableFromDate = "-";
$tableToDate = "-";
$tableBoatMV = "-";
$tableBoatLighterNo = "-";
$itemPos1 = array();
$itemPos2 = array();
$itemPos3 = array();
$itemPos4 = array();
$item1 = "";
$item2 = "";
$item3 = "";
$item4 = "";
$qty1 = "";
$qty2 = "";
$qty3 = "";
$qty4 = "";
$Cond1 = "";
$Cond2 = "";
$Cond3 = "";
$Cond4 = "";
$OutputCO1 = "";
$OutputCO2 = "";
$OutputCO3 = "";
$OutputCO4 = "";
$LisQty1 = array();
$LisQty2 = array();
$LisQty3 = array();
$LisQty4 = array();
$ListItem1 = array();
$ListItem2 = array();
$ListItem3 = array();
$ListItem4 = array();
$ListCO = array();
$ListItem = array();
$Str1 = "";
$Str2 = "";
$Str3 = "";
$Str4 = "";
$x = 0;
$xx = 0;
$NewLine = "";
$TotalPos1 = 0;
$TotalPos2 = 0;
$TotalPos3 = 0;
$TotalPos4 = 0;
$TotalAll = 0;
if (!empty($_GET["selBoat"])) {
    $selBoat = $_GET["selBoat"];
} else {
    if (isset($_POST["selBoat"])) {
        $selBoat = $_POST["selBoat"];
    }
}

if (isset($_POST["fromDate"])) {
    $fromDate = $_POST["fromDate"];
}
if (isset($_POST["toDate"])) {
    $toDate = $_POST["toDate"];
}

$Boat = new Boat();
$Boat->setConn($conn);
$boatList = "";

if ($selBoat != "") {
//    $selBoat = $_GET["selBoat"];
    $Boat->_IdRun = $selBoat;
    $Boat->GetProperty();
    $boatList = $Boat->PopulateAll(0, $selBoat);
    $boatLighterNo = $Boat->Ship_LighterNo;
} else {
    $boatList = $Boat->PopulateAll(0, 0);
}

$thisDate = date("Y-m-d H:m:s");
$date1 = "";
///Auto decrease 30 days 
if ($fromDate == "") {
    $date1 = str_replace('-', '/', $thisDate);
    $dateFilter1 = date('Y-m-d H:m:s', strtotime($date1 . "-30 days"));
    $fromDate = $dateFilter1;
}
if ($toDate == "") {
    $toDate = date("Y-m-d H:m:s");
}
if ($formAction != "") { /// Form submit incomming 
    $tableFromDate = $fromDate;
    $tableToDate = $toDate;
    $tableBoatMV = $Boat->Ship_MV;
    $tableBoatLighterNo = $Boat->Ship_LighterNo;
    $BPLists = new BoatPlanList();
    $BPLists->setConn($conn);
    $Cond = array();
    $CondCo = array();
    $CondItem = array();
    $itemPos = array();
    $OutputCO = array();
    $Debug = "";
    $Handle = "";
    $Item = new Item();
    $Item->setConn($conn_tag);
    $tQty = 0;
    $tQty2 = 0;
    $tQty3 = 0;
    $tQty4 = 0;
    for ($i = 1; $i <= 4; $i++) {

        $Cond[$i] = " ( Boat_Id = $selBoat )   AND ( StartDate BETWEEN '$fromDate' AND '$toDate' ) ";
        $CondCo[$i] = $Cond[$i] . " AND itemPosition = $i  GROUP BY co_num";
//        $Debug = $Debug."<br> Debug Cond[$i]".  $Cond[$i] ; 
//        $Debug = $Debug."<br> Debug CondCo[$i]".  $CondCo[$i] ; 
        $ListCO[$i] = $BPLists->GetRowsCO_WithCond($CondCo[$i]);
    }
    $ListCO1 = $BPLists->GetRowsCO_WithCond($CondCo[1]);
    $ListCO2 = $BPLists->GetRowsCO_WithCond($CondCo[2]);
    $ListCO3 = $BPLists->GetRowsCO_WithCond($CondCo[3]);
    $ListCO4 = $BPLists->GetRowsCO_WithCond($CondCo[4]);
    $tQty = 0;
    $tQty2 = 0;
    $tQty3 = 0;
    $tQty4 = 0;
    foreach ($ListCO1 as $id => $rows) {



        $x = $id + 1;
        $xx = 0;
        $OutputCO1 = $OutputCO1 . "CO#" . $rows["co_num"] . "<br>";
        $Str1 = $Cond[$x] . " AND itemPosition = 1  AND co_num = '" . $rows["co_num"] . "' ";
        $ListItem1 = $BPLists->GetRowsWithCond($Str1);

        foreach ($ListItem1 as $idInLoop => $rowsInLoop) {
            $Item->_id = $rowsInLoop["itemTag"];
            $xx++;
            $Item->qty1 ? $tQty = $Item->qty1 : $tQty = 0;
            if ($xx == 1) {
                $NewLine = "";
            } else {
                $NewLine = "<br>";
            }
            $OutputCO1 = $OutputCO1 . "  $xx " . $Item->item . "<br>";
            $qty1 = $qty1 . "<br>" . number_format($tQty, 2);
            $TotalPos1 = $TotalPos1 + $tQty;
        }
        $qty1 = $qty1 . "<hr>";
        $OutputCO1 = $OutputCO1 . "<hr>";
    }

    foreach ($ListCO2 as $id => $rows) {
        $x = $id + 1;
        $xx = 0;
     
     
        $OutputCO2 = $OutputCO2 . "CO#" . $rows["co_num"] . "<br>";
        $Str2 = $Cond[$x] . " AND itemPosition = 2  AND co_num = '" . $rows["co_num"] . "' ";
        $ListItem2 = $BPLists->GetRowsWithCond($Str2);
        foreach ($ListItem2 as $idInLoop => $rowsInLoop) {
            $xx++;
               $Item->_id = $rowsInLoop["itemTag"];
                  $Item->qty1 ? $tQty2 = $Item->qty1 : $tQty2 = 0;
            if ($xx == 1) {
                $NewLine = "";
            } else {
                $NewLine = "<br>";
            }
            $OutputCO2 = $OutputCO2 . "  $xx " . $Item->item . "<br>";
            $qty2 = $qty2 . "<br>" . number_format($tQty2, 2);
            $TotalPos2 = $TotalPos2 + $tQty2;
        }
        $qty2 = $qty2 . "<hr>";
        $OutputCO2 = $OutputCO2 . "<hr>";
    }

    foreach ($ListCO3 as $id => $rows) {
        $x = $id + 1;
        $xx = 0;
        $OutputCO3 = $OutputCO3 . "CO#" . $rows["co_num"] . "<br>";
        $Str3 = $Cond[$x] . " AND itemPosition = 3  AND co_num = '" . $rows["co_num"] . "' ";
        $ListItem3 = $BPLists->GetRowsWithCond($Str3);
        foreach ($ListItem3 as $idInLoop => $rowsInLoop) {
            $xx++;
               $Item->_id = $rowsInLoop["itemTag"];
                  $Item->qty1 ? $tQty3 = $Item->qty1 : $tQty3 = 0;
            if ($xx == 1) {
                $NewLine = "";
            } else {
                $NewLine = "<br>";
            }
            $OutputCO3 = $OutputCO3 . "  $xx " . $Item->item . "<br>";
            $qty3 = $qty3 . "<br>" . number_format($tQty3, 2);
            $TotalPos3 = $TotalPos3 + $tQty3;
        }
        $qty3 = $qty3 . "<hr>";
        $OutputCO3 = $OutputCO3 . "<hr>";
    }
    foreach ($ListCO4 as $id => $rows) {
        $x = $id + 1;
        $xx = 0;
        $OutputCO4 = $OutputCO4 . "CO#" . $rows["co_num"] . "<br>";
        $Str4 = $Cond[$x] . " AND itemPosition = 3  AND co_num = '" . $rows["co_num"] . "' ";
        $ListItem4 = $BPLists->GetRowsWithCond($Str4);
        foreach ($ListItem4 as $idInLoop => $rowsInLoop) {
            $xx++;
                   $Item->_id = $rowsInLoop["itemTag"];
                  $Item->qty1 ? $tQty4 = $Item->qty1 : $tQty4 = 0;
            if ($xx == 1) {
                $NewLine = "";
            } else {
                $NewLine = "<br>";
            }
            $OutputCO4 = $OutputCO4 . "  $xx " .$Item->item . "<br>";
            $qty4 = $qty4 . "<br>" . number_format($tQty4, 2);
            $TotalPos4 = $TotalPos4 + $tQty4;
        }
        $qty4 = $qty4 . "<hr>";
        $OutputCO4 = $OutputCO4 . "<hr>";
    }


    $TotalAll = $TotalPos1 + $TotalPos2 + $TotalPos3 + $TotalPos4;
    $reportContent = $reportContent . "<tr>"
            . "<td>$OutputCO1</td>"
            . "<td class='tab-sub-head-qty'>$qty1</td>"
            . "<td>$OutputCO2</td>"
            . "<td class='tab-sub-head-qty'>$qty2</td>"
            . "<td>$OutputCO3</td>"
            . "<td class='tab-sub-head-qty'>$qty3</td>"
            . "<td>$OutputCO4</td>"
            . "<td class='tab-sub-head-qty'>$qty4</td>"
            . "</tr>";
} else {
    $reportContent = "<td  align='center' colspan='8'> ... No data ... </td>";
}

$temp->setReplace("{boatLists}", $boatList);
$temp->setReplace("{fromDate}", $fromDate);
$temp->setReplace("{toDate}", $toDate);
$temp->setReplace("{boatLighterNo}", $boatLighterNo);

$temp->setReplace("{tableFromDate}", $tableFromDate);
$temp->setReplace("{tableToDate}", $tableToDate);
$temp->setReplace("{tableBoatMV}", $tableBoatMV);
$temp->setReplace("{tableBoatLighterNo}", $tableBoatLighterNo);

$temp->setReplace("{TotalPos1}", number_format($TotalPos1, 2));
$temp->setReplace("{TotalPos2}", number_format($TotalPos2, 2));
$temp->setReplace("{TotalPos3}", number_format($TotalPos3, 2));
$temp->setReplace("{TotalPos4}", number_format($TotalPos4, 2));
$temp->setReplace("{TotalAll}", number_format($TotalAll, 2));
$temp->setReplace("{reportContent}", $reportContent);
