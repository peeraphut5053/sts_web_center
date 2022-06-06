<?php

include "./initial.php";
//
//$ITEM = $_POST["ITEM"];
//$ITEM_LOT = $_POST["ITEM_LOT"];
//$SIZE = $_POST["SIZE"];
//$WEIGHT = $_POST["WEIGHT"];
//$TW = $_POST["TW"];
//$LEN = $_POST["LEN"];
//$CATE = $_POST["CATE"];
//$SCHED = $_POST["SCHED"];
//$PCSPERBNDL = $_POST["PCSPERBNDL"];
//$BNDL = $_POST["BNDL"];
//$PIECES = $_POST["PIECES"];
//$TTLEN = $_POST["TTLEN"];
//$M_TONS = $_POST["M_TONS"];
//$FOBMT = $_POST["FOBMT"];
//$FOBFT = $_POST["FOBFT"];
//$extPrice = $_POST["extPrice"];
$import_form = $_POST["import_form"];
$ITEM = explode("!!", $_POST["ITEM"]);
$ITEM_LOT = explode("!!", $_POST["ITEM_LOT"]);
$SIZE = explode("!!", $_POST["SIZE"]);
$WEIGHT = explode("!!", $_POST["WEIGHT"]);
$TW = explode("!!", $_POST["TW"]);
$LEN = explode("!!", $_POST["LEN"]);
$CATE = explode("!!", $_POST["CATE"]);
$SCHED = explode("!!", $_POST["SCHED"]);
$PCSPERBNDL = explode("!!", $_POST["PCSPERBNDL"]);
$BNDL = explode("!!", $_POST["BNDL"]);
$PIECES = explode("!!", $_POST["PIECES"]);
$TTLEN = explode("!!", $_POST["TTLEN"]);
$M_TONS = explode("!!", $_POST["M_TONS"]);
$FOBMT = explode("!!", $_POST["FOBMT"]);
$FOBFT = explode("!!", $_POST["FOBFT"]);
$extPrice = explode("!!", $_POST["extPrice"]);
$ITEM_DESC = explode("!!", $_POST["ITEM_DESC"]);

$tdStyle = "style='border-top:none;border-bottom:none;'";
$tblLines = "";
$x = count($ITEM) - 1;
$z = 0;
$SumBundles = 0;
$SumPieces = 0;
$SumTTLen = 0;
$SumMTons = 0;
$SumFOBT = 0;
$SumFOBFT = 0;
$SumExtPrice = 0;
$TmpLot = "";
$TmptLot = "";
$tmpItemDesc = "";
for ($i = 1; $i < $x; $i++) {
    $z = $i;
    $Bndl = 0;
    $PcsPerBndl = 0;
    $Pcs = (float) trim($PIECES[$i]);
    if (($import_form == "2") || ($import_form == 2) || ($import_form == 0) || ($import_form == "0")) {
        $query = "SELECT TOP 1 *  FROM Calculate_PCS_to_BNDL WHERE sched ='" . $SCHED[$i] . "' AND size='" . $SIZE[$i] . "' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($ConnWebApp, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        if (!$rs0) {
            $Bndl = 0;
            $PcsPerBndl = 0;
        } else {
            $Bndl = $rs0[0]["result"];
            $PcsPerBndl = round((float) $Pcs / (float) $Bndl, 2);
        }
        $TmpLot = $ITEM_LOT[$i];
        $TmptLot = $ITEM_LOT[$i];
        $tmpItemDesc = $ITEM_DESC[$i];
    } else if (($import_form == "1") || ($import_form == 1 )) {
        $tmpItemDesc = $ITEM_DESC[$i];
        $TmpLot = $ITEM_LOT[$i];
        $TmptLot = $ITEM_LOT[$i];
        $Bndl = $BNDL[$i];
        $PcsPerBndl = $PCSPERBNDL[$i];
    }
    $tblLines = $tblLines . "<tr id='rowIndex_$z'>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowSIZE_$z' align='right'>$z</td>";
    $tblLines = $tblLines . "<td colspan='2' style='border-top:none;border-bottom:none;width:20%;' data-lot='$TmpLot'  id='rowItem_$z'>"
            . "<input type='hidden' id='rowLot_$TmpLot' value='$TmpLot'>"
            . "<input type='hidden' id='UnitWeight_$z' value='" . $WEIGHT[$i] . "'>"
            . "<input type='hidden' id='rowDesc_$z' value='$tmpItemDesc'>"
            . "<h6 style='font-size:12px;color:blue;' id='txtItem_$z'>" . $TmptLot . $ITEM[$i] . "</h6>"
            . "<h6 style='font-size:12px;' id='txtDesc_$z'>$tmpItemDesc</h6>"
            . "</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowSIZE_$z' align='right'>" . $SIZE[$i] . "</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowTW_$z'align='right'>" . $TW[$i] . "</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowUL_$z'align='right'>" . $LEN[$i] . "</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowCATE_$z'align='center'>" . $CATE[$i] . "</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowSCHED_$z'align='right'>" . $SCHED[$i] . "</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowPCSPERBNDL_$z'align='right'>$PcsPerBndl</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowBNDL_$z'align='right'>$Bndl</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowPCS_$z'align='right'>$Pcs</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowTTLEN_$z' align='right'>" . $TTLEN[$i] . "</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowMTONS_$z'align='right'>" . $M_TONS[$i] . "</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowCFRMT_$z'align='right'>" . str_replace("$","",$FOBMT[$i]) . "</td>";
    $tblLines = $tblLines . "<td width='3%' " . $tdStyle . " id='rowCFRFT_$z' align='right'>" . str_replace("$","",$FOBFT[$i]) . "</td>";
    $tblLines = $tblLines . "<td width='7%' " . $tdStyle . " id='rowEXTPRICE_$z'  align='right'>" . str_replace("$","",$extPrice[$i]) . "</td>";
    $tblLines = $tblLines . "<td width='2%' style='border-top:none;border-bottom:none;' align='center'><a id='btnRemoveGridLines_$z' OnClick='RemoveThis($z);'  href='#' style='color:red;'><i class='fas fa-times'></i></a></td>";
    $tblLines = $tblLines . "</tr>";
    $SumBundles = $SumBundles + (float)str_replace(",", "", (float)$BNDL[$i]);
    $SumPieces = $SumPieces + (float)str_replace(",", "", $PIECES[$i]);
    $SumTTLen = $SumTTLen + (float)str_replace(",", "", $TTLEN[$i]);
    $SumMTons = $SumMTons + (float)str_replace(",", "", $M_TONS[$i]);
    $SumFOBT = $SumFOBT + (float)str_replace(",", "",str_replace("$","",$FOBMT[$i]));
    $SumFOBFT = $SumFOBFT + (float)str_replace(",", "", str_replace("$","",$FOBFT[$i]));
    $SumExtPrice = $SumExtPrice + (float)str_replace(",", "",str_replace("$","",$extPrice[$i]) );
}
//}
$tableSummary = "<tr>"
        . "<td colspan='9' align='right'><b>Total</b></td>"
        . "<td align='right'><b>" . number_format($SumBundles, 2) . "</b></td>"
        . "<td align='right'><b>" . number_format($SumPieces, 2) . "</b></td>"
        . "<td align='right'><b>" . number_format($SumTTLen, 2) . "</b></td>"
        . "<td align='right'><b>" . number_format($SumMTons, 2) . "</b></td>"
        . "<td align='right'><b>" . number_format($SumFOBT, 2) . "</b></td>"
        . "<td align='right'><b>" . number_format($SumFOBFT, 2) . "</b></td>"
        . "<td align='right'><b>" . number_format($SumExtPrice, 2) . "</b></td>"
        . "<td></td>"
        . "</tr>";
echo $tblLines . $tableSummary;
