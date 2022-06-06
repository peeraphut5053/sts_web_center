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
///===============Roll back Head ================//
$sqlMain = "SELECT   HeadId, HeadCode, MV, LighterNo, BerthedAt, Convert(nvarchar(30),CommenceDate) as CMDate , Convert(nvarchar(30),CompleteDate) AS CPDate FROM  BoatNote_Head WHERE HeadCode ='$HeadCode' ";
$rsHeadMain = $cSql->SqlQuery($conn, $sqlMain);

$sql1 = "SELECT   HeadId, HeadCode, MV, LighterNo, BerthedAt, Convert(nvarchar(30),CommenceDate) as CMDate , Convert(nvarchar(30),CompleteDate) AS CPDate FROM  BoatNote_Head_copy WHERE HeadCode ='$HeadCode' ";
$rsHead = $cSql->SqlQuery($conn, $sql1);

if ($rsHead[1]["MV"] != $rsHeadMain[1]["MV"]) {
    $txtMV = $rsHead[1]["MV"];
} else {
    $txtMV = $rsHeadMain[1]["MV"];
}
if ($rsHead[1]["LighterNo"] != $rsHeadMain[1]["LighterNo"]) {
    $txtLN = $rsHead[1]["LighterNo"];
} else {
    $txtLN = $rsHeadMain[1]["LighterNo"];
}
if ($rsHead[1]["BerthedAt"] != $rsHeadMain[1]["BerthedAt"]) {
    $txtBA = $rsHead[1]["BerthedAt"];
} else {
    $txtBA = $rsHeadMain[1]["BerthedAt"];
}
if ($rsHead[1]["CMDate"] != $rsHeadMain[1]["CMDate"]) {
    $txtCMDate = $rsHead[1]["CMDate"];
} else {
    $txtCMDate = $rsHeadMain[1]["CMDate"];
}
if ($rsHead[1]["CPDate"] != $rsHeadMain[1]["CPDate"]) {
    $txtCPDate = $rsHead[1]["CPDate"];
} else {
    $txtCPDate = $rsHeadMain[1]["CPDate"];
}

$sqlUpdateHead = "UPDATE BoatNote_Head SET  MV='$txtMV', LighterNo='$txtLN', BerthedAt='$txtBA',CommenceDate='$txtCMDate' , CompleteDate='$txtCPDate'    WHERE HeadCode ='$HeadCode' ";
$rsUpdateHead = $cSql->IsUpDel($conn, $sqlUpdateHead);
///===============Roll back Head ================//
//$sqlMain2 = "SELECT  IdRun, HeadCode, LotNo, ShipTo, Bundles, Description, GrossWeight  FROM    STS_BoatNote_List  WHERE HeadCode ='$HeadCode'";
//$rsListsMain2 = $cSql->SqlQuery($conn, $sqlMain2);

$sql2 = "SELECT  IdRun, HeadCode, LotNo, ShipTo, Bundles, Description, GrossWeight  FROM    STS_BoatNote_List_copy  WHERE HeadCode ='$HeadCode'";
$rsLists = $cSql->SqlQuery($conn, $sql2);
$rsClearList = $cSql->IsUpDel($conn, "DELETE FROM STS_BoatNote_List WHERE HeadCode = '$HeadCode' "); //Clear Main lists
$pointer1 = 0;

$tLN = "";
$tTo = "";
$tBD = 0;
$tDT = "";
$tGW = 0;
for ($i = 1; $i <= $rsLists[0][0]; $i++) {
    $pointer1++;
    $tLN = $rsLists[$i]["LotNo"];
    $tTO = $rsLists[$i]["ShipTo"];
    $tBD = $rsLists[$i]["Bundles"];
    $tDT = $rsLists[$i]["Description"];
    $tGW = $rsLists[$i]["GrossWeight"];
    $strIns = " INSERT INTO STS_BoatNote_List (HeadCode, LotNo, ShipTo, Bundles, Description, GrossWeight ) "
            . "VALUES ('$HeadCode','$tLN','$tTO',$tBD,'$tDT',$tGW) ";
  $rsIns = $cSql->IsUpDel($conn, $strIns);

    $lists = $lists . "<tr>"
            . "<td>$tLN</td>"
            . "<td>$tTO</td>"
            . "<td>$tBD</td>"
            . "<td>$tDT</td>"
            . "<td>$tGW</td>"
            . "</tr>";
}


$copy = "<a href='?view&HeadCode=" . $HeadCode . "' class='btn btn-round'><i class='fa fa-copy'></i></a>";
$rollback = "<a id='btnRollBack' href='?rollback&HeadCode=" . $HeadCode . "' class='btn btn-warning btn-small' style='width:150px;'><i class='fa fa-mail-reply'></i>&nbsp;Roll back</a>";
$temp->setReplace("{copy}", $copy);
$temp->setReplace("{rollback}", $rollback);
$temp->setReplace("{VisiblePrintEdit}", "hidden");
$temp->setReplace("{bgcolor}", "#ffffff");
$temp->setReplace("{MV}", $txtMV);
$temp->setReplace("{LighterNo}", $txtLN);
$temp->setReplace("{BerthedAt}", $txtBA);
$temp->setReplace("{CMDate}", $txtCMDate);
$temp->setReplace("{CPDate}", $txtCPDate);
$temp->setReplace("{docno}", $rsHead[1]["HeadCode"]);
$temp->setReplace("{lists}", $lists);
