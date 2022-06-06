<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");

$HeadCode = "";
$action = "";


if (!empty($_GET["HeadCode"])) {
    $HeadCode = $_GET["HeadCode"];
} else {
    if (isset($_POST["HeadCode"])) {
        $HeadCode = $_POST["HeadCode"];
    }
}
$temp->setReplace("{content}", $temp->getTemplate("./template/view_cancel.html"));

$lists = "";
$Head = new BoatPlanHead();
$Head->setConn($conn);
$Head->_HeadCode = $HeadCode;
$Head->_Cancel = 1 ;
$Head->GetProperty();

$List = new BoatPlanList();
$List->setConn($conn);
$List->_HeadCode = $HeadCode;
$result = $List->GetRowsWithHeader();

foreach ($result as $id => $rows) {
    $i = $id + 1;
    $lists = $lists . "<tr>"
            . "<td>" . $rows["Position"] . "</td>"
            . "<td>" . $rows["itemTag"] . "</td>"
            . "<td></td>"
            . "<td></td>"
//            . "<td>" . $rows["GrossWeight"] . "</td>"
            . "</tr>";
}
//$copy = "<a href='?view_copy&HeadCode=".$HeadCode."' class='btn btn-round'><i class='fa fa-file-o'></i></a>" ;
//$temp->setReplace("{copy}", $copy);
//$temp->setReplace("{rollback}", "");
$temp->setReplace("{VisiblePrintEdit}", "visible");
$temp->setReplace("{bgcolor}", "#ffffff");
$temp->setReplace("{MV}", $Head->Boat_MV);
$temp->setReplace("{LighterNo}", $Head->Boat_LighterNo);
$temp->setReplace("{BerthedAt}", $Head->HB_Name);
$temp->setReplace("{StartDate}", $Head->StartDate);
//$temp->setReplace("{CPDate}", $rsHead[1]["CPDate"]);
$temp->setReplace("{docno}", $Head->HeadCode);
$temp->setReplace("{lists}", $lists);
