<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");

$HeadCode = "";
$action = "";

$HeadCode = $_GET["HeadCode"];
$temp->setReplace("{content}", $temp->getTemplate("./template/bn_view.html"));

$lists = "";
$cSql = new SqlSrv();
$txtMV = "";
$txtLN = "";
$txtBA = "";
$txtCMDate = "";
$txtCPDate = "";

/// Head Object ///
$BoatNoteHead = new BoatNoteHead();
$BoatNoteHead->setConn($conn);
$BoatNoteHead->_HeadCode = $HeadCode;
$BoatNoteHead->_UseTable = "BoatNote_head";
$BoatNoteHead->GetProperty();

/// Head Object Copy///
$BoatNoteHead_Copy = new BoatNoteHead();
$BoatNoteHead_Copy->setConn($conn);
$BoatNoteHead_Copy->_HeadCode = $HeadCode;
$BoatNoteHead_Copy->_UseTable = "BoatNote_head_copy";
$BoatNoteHead_Copy->GetProperty();


if ($BoatNoteHead->MV != $BoatNoteHead_Copy->MV) {
    $txtMV = "<font color='red'><strong>" . $BoatNoteHead_Copy->MV . "</strong></font>";
} else {
    $txtMV = $BoatNoteHead_Copy->MV;
}
if ($BoatNoteHead->LighterNo != $BoatNoteHead_Copy->LighterNo) {
    $txtLN = "<font color='red'><strong>" . $BoatNoteHead_Copy->LighterNo . "</strong></font>";
} else {
    $txtLN = $BoatNoteHead_Copy->LighterNo;
}
if ($BoatNoteHead->BerthedAt != $BoatNoteHead_Copy->BerthedAt) {
    $txtBA = "<font color='red'><strong>" . $BoatNoteHead_Copy->BerthedAt . "</strong></font>";
} else {
    $txtBA = $BoatNoteHead_Copy->BerthedAt;
}
if ($BoatNoteHead->CommencedDate != $BoatNoteHead_Copy->CommencedDate) {
    $txtCMDate = "<font color='red'><strong>" . $BoatNoteHead_Copy->CommencedDate . "</strong></font>";
} else {
    $txtCMDate = $BoatNoteHead_Copy->CommencedDate;
}
if ($BoatNoteHead->CompleteDate != $BoatNoteHead_Copy->CompleteDate) {
    $txtCPDate = "<font color='red'><strong>" . $BoatNoteHead_Copy->CompleteDate . "</strong></font>";
} else {
    $txtCPDate = $BoatNoteHead_Copy->CompleteDate;
}

///Lists object Main ////
$BoatNoteList = new BoatNoteList();
$BoatNoteList->setConn($conn);
$BoatNoteList->_UseTable = "STS_BoatNote_List";
$BoatNoteList->_HeadCode = $HeadCode ;
$result = $BoatNoteList->GetRowsWithHeader();
///Lists object Main ////
$BoatNoteList_Copy = new BoatNoteList();
$BoatNoteList_Copy->setConn($conn);
$BoatNoteList_Copy->_UseTable = "STS_BoatNote_List_copy";
$BoatNoteList_Copy->_HeadCode = $HeadCode ;
$result_copy = $BoatNoteList_Copy->GetRowsWithHeader();
///Lists object Main Copy Detail ////
$BoatNoteList_Copy2 = new BoatNoteList();
$BoatNoteList_Copy2->setConn($conn);
$BoatNoteList_Copy2->_HeadCode = $HeadCode ;
$BoatNoteList_Copy2->_UseTable = "STS_BoatNote_List_copy";



$pointerErr = 0;

$tLN = "";
$tTo = "";
$tBD = 0;
$tDT = "";
$tGW = 0;

$ttLN = "";
$ttTO = "";
$ttBD = 0;
$ttDT = "";
$ttGW = 0;
$lists2 = "";
foreach ($result as $index => $rows) {
    $pointerErr = 0;
    if ($rows["LotNo"] != $result_copy[$index]["LotNo"]) {
        $tLN = "<font color='red'><strong>" . $result_copy[$index]["LotNo"] . "</strong></font>";
    } else {
        $tLN = $result_copy[$index]["LotNo"];
    }

    if ($rows["ShipTo"] != $result_copy[$index]["ShipTo"]) {
        $tTO = "<font color='red'><strong>" . $result_copy[$index]["ShipTo"] . "</strong></font>";
    } else {
        $tTO = $result_copy[$index]["ShipTo"];
    }

    if ($rows["Bundles"] != $result_copy[$index]["Bundles"]) {
        $tBD = "<font color='red'><strong>" . $result_copy[$index]["Bundles"] . "</strong></font>";
    } else {
        $tBD = $result_copy[$index]["Bundles"];
    }

    if ($rows["Description"] != $result_copy[$index]["Description"]) {
        $tDT = "<font color='red'><strong>" . $result_copy[$index]["Description"] . "</strong></font>";
    } else {
        $tDT = $result_copy[$index]["Description"];
    }

    if ($rows["GrossWeight"] != $result_copy[$index]["GrossWeight"]) {
        $tGW = "<font color='red'><strong>" . $result_copy[$index]["GrossWeight"] . "</strong></font>";
    } else {
        $tGW = $result_copy[$index]["GrossWeight"];
    }
    $lists = $lists . "<tr>"
            . "<td>$tLN</td>"
            . "<td>$tTO</td>"
            . "<td>$tBD</td>"
            . "<td>$tDT</td>"
            . "<td>$tGW</td>"
            . "</tr>";
    /// Different Line with add new  ///
    $sqlMain3 = " "
            . "( HeadCode ='$HeadCode'  "
            . "AND LotNo <> '" . $rows["LotNo"] . "'  "
            . "AND ShipTo <> '" . $rows["ShipTo"] . "'  "
            . "AND Bundles <> " . $rows["Bundles"] . "   "
            . "AND Description <> '" . $rows["Description"] . "'  "
            . "AND GrossWeight <> " . $rows["GrossWeight"] . " )  ";
    $BoatNoteList_Copy2->GetPropertyWithCond($sqlMain3);
    $ttLN = $result[$index]["LotNo"];
    $ttTO = $result[$index]["ShipTo"];
    $ttBD = $result[$index]["Bundles"];
    $ttDT = $result[$index]["Description"];
    $ttGW = $result[$index]["GrossWeight"];
    //prevent dup data show
    if (($ttLN != $BoatNoteList_Copy2->LotNo && ($ttTO != $BoatNoteList_Copy2->ShipTo && ($ttBD != $BoatNoteList_Copy2->Bundles) && ($ttGW != $BoatNoteList_Copy2->GrossWeight)))) {

        $lists2 = $lists2 . "<tr style='background-color:#82ffa9;'>"
                . "<td><font color='black'>$ttLN</font></td>"
                . "<td><font color='black'>$ttTO</font></td>"
                . "<td><font color='black'>$ttBD</font></td>"
                . "<td><font color='black'>$ttDT</font></td>"
                . "<td><font color='black'>$ttGW</font></td>"
                . "</tr>";
    }
}


$copy = "<a href='?view&HeadCode=" . $HeadCode . "' class='btn btn-round'><i class='fa fa-copy'></i></a>";
$rollback = "<a id='btnRollBack' href='?rollback&HeadCode=" . $HeadCode . "' class='btn btn-warning btn-small' style='width:150px;'><i class='fa fa-mail-reply'></i>&nbsp;Roll back</a>";
$temp->setReplace("{copy}", $copy);
$temp->setReplace("{rollback}", $rollback);
$temp->setReplace("{VisiblePrintEdit}", "hidden");
$temp->setReplace("{bgcolor}", "#f9f1b3");
$temp->setReplace("{MV}", $txtMV);
$temp->setReplace("{LighterNo}", $txtLN);
$temp->setReplace("{BerthedAt}", $txtBA);
$temp->setReplace("{CMDate}", $txtCMDate);
$temp->setReplace("{CPDate}", $txtCPDate);
$temp->setReplace("{docno}", $BoatNoteHead->HeadCode);
$temp->setReplace("{lists}", $lists . $lists2);
