<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//
include "./initial.php";

$temp = new ReplaceHtml("../template/SHP/SHP/report_print.html");

$BoatId = 0;
$fromDate = "";
$toDate = "";

if (!empty($_GET["BoatId"])) {
    $BoatId = $_GET["BoatId"];
}
if (!empty($_GET["fromDate"])) {
    $fromDate = $_GET["fromDate"];
}
if (!empty($_GET["toDate"])) {
    $toDate = $_GET["toDate"];
}

//$HeadCode = "";
//$action = "";
$selBoat = "";

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
$TotalAll = 0 ;

if ($BoatId != 0) {
    $Boat = new Boat();
    $Boat->setConn($ConnWebApp);
    $Boat->_IdRun = $BoatId;
    $Boat->GetProperty();
    $tableFromDate = $fromDate;
    $tableToDate = $toDate;
    $tableBoatMV = $Boat->Ship_MV;
    $tableBoatLighterNo = $Boat->Ship_LighterNo;
    
     $BPLists = new BoatPlanList();
    $BPLists->setConn($ConnWebApp);
    $Cond = array();
    $CondCo = array();
    $CondItem = array();
    $itemPos = array();
    $OutputCO = array();
    for ($i = 1; $i <= 4; $i++) {
        $Cond[$i] = " ( Boat_Id = ".$Boat->IdRun." )  AND ( StartDate BETWEEN '$fromDate' AND '$toDate' )  ";
        $CondCo[$i] = $Cond[$i] . " AND itemPosition = $i GROUP BY co_num";
    }
    $ListCO1 = $BPLists->GetRowsCO_WithCond($CondCo[1]);
    $ListCO2 = $BPLists->GetRowsCO_WithCond($CondCo[2]);
    $ListCO3 = $BPLists->GetRowsCO_WithCond($CondCo[3]);
    $ListCO4 = $BPLists->GetRowsCO_WithCond($CondCo[4]);

    foreach ($ListCO1 as $id => $rows) {
        $x = $id + 1;
        $xx = 0;
        $OutputCO1 = $OutputCO1 . "CO#" . $rows["co_num"] . "<br>";
        $Str1 = $Cond[$x] . " AND itemPosition = 1  AND co_num = '" . $rows["co_num"] . "' ";
        $ListItem1 = $BPLists->GetRowsWithCond($Str1);

        foreach ($ListItem1 as $idInLoop => $rowsInLoop) {
            $xx++;
            if ($xx == 1) {
                $NewLine = "";
            } else {
                $NewLine = "<br>";
            }
            $OutputCO1 = $OutputCO1 . "  $xx " . $rowsInLoop["item"] . "<br>";
            $qty1 = $qty1 . "<br>" . number_format($rowsInLoop["qty1"], 2);
            $TotalPos1 = $TotalPos1 + $rowsInLoop["qty1"];
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
            if ($xx == 1) {
                $NewLine = "";
            } else {
                $NewLine = "<br>";
            }
            $OutputCO2 = $OutputCO2 . "  $xx " . $rowsInLoop["item"] . "<br>";
            $qty2 = $qty2 . "<br>" . number_format($rowsInLoop["qty1"], 2);
            $TotalPos2 = $TotalPos2 + $rowsInLoop["qty1"];
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
            if ($xx == 1) {
                $NewLine = "";
            } else {
                $NewLine = "<br>";
            }
            $OutputCO3 = $OutputCO3 . "  $xx " . $rowsInLoop["item"] . "<br>";
            $qty3 = $qty3 . "<br>" . number_format($rowsInLoop["qty1"], 2);
            $TotalPos3 = $TotalPos3 + $rowsInLoop["qty1"];
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
            if ($xx == 1) {
                $NewLine = "";
            } else {
                $NewLine = "<br>";
            }
            $OutputCO4 = $OutputCO4 . "  $xx " . $rowsInLoop["item"] . "<br>";
            $qty4 = $qty4 . "<br>" . number_format($rowsInLoop["qty1"], 2);
            $TotalPos4 = $TotalPos4 + $rowsInLoop["qty1"];
        }
        $qty4 = $qty4 . "<hr>";
        $OutputCO4 = $OutputCO4 . "<hr>";
    }
    


    $TotalAll = $TotalPos1 + $TotalPos2 + $TotalPos3 + $TotalPos4;

    $reportContent = $reportContent . "<tr>"
            . "<td valign='top'>$OutputCO1</td>"
            . "<td valign='top' class='tab-sub-head-qty'>$qty1</td>"
            . "<td valign='top' >$OutputCO2</td>"
            . "<td valign='top' class='tab-sub-head-qty'>$qty2</td>"
            . "<td valign='top' >$OutputCO3</td>"
            . "<td valign='top' class='tab-sub-head-qty'>$qty3</td>"
            . "<td valign='top' >$OutputCO4</td>"
            . "<td valign='top' class='tab-sub-head-qty'>$qty4</td>"
            . "</tr>";
} else {
    $reportContent = "<td  align='center' colspan='8'> ... No data ... </td>";
}

//$temp->setReplace("{boatLists}", $boatList);
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
echo $temp->getReplace();