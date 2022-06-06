<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");
$temp->setReplace("{content}", $temp->getTemplate("./template/bn_view_cancel.html"));
$HeadCode = "";
if (!empty($_GET["HeadCode"])) {
    $HeadCode = $_GET["HeadCode"];
} else {
    if (isset($_POST["HeadCode"])) {
        $HeadCode = $_POST["HeadCode"];
    }
}

$action = "";

$Head = new BoatNoteHead();
$Head->setConn($conn) ;
$Head->_HeadCode= $HeadCode ;
$Head->_Cancel=1 ;
$Head->GetProperty();

$List = new BoatNoteList();
$List->setConn($conn) ;
$List->_HeadCode= $HeadCode ;


$lists = "";
$result = $List->GetRowsWithHeader();
foreach ($result as $id => $rows) {
    $i = $id +1 ;
    $lists = $lists . "<tr>"
            . "<td>" . $rows["LotNo"] . "</td>"
            . "<td>" . $rows["ShipTo"] . "</td>"
            . "<td>" . $rows["Bundles"] . "</td>"
            . "<td>" . $rows["Description"] . "</td>"
            . "<td>" . $rows["GrossWeight"] . "</td>"
            . "</tr>";
}
$copy = "<a href='?view_copy&HeadCode=".$HeadCode."' class='btn btn-round'><i class='fa fa-file-o'></i></a>" ;
//$temp->setReplace("{copy}", $copy);
$temp->setReplace("{rollback}", "");
$temp->setReplace("{VisiblePrintEdit}", "visible");
$temp->setReplace("{bgcolor}", "#ffffff");
$temp->setReplace("{MV}", $Head->Ship_MV);
$temp->setReplace("{LighterNo}", $Head->Ship_LighterNo);
$temp->setReplace("{BerthedAt}", $Head->HB_Name);
$temp->setReplace("{CMDate}", $Head->CommencedDate);
$temp->setReplace("{CPDate}", $Head->CompleteDate);
$temp->setReplace("{docno}", $Head->HeadCode);
$temp->setReplace("{lists}", $lists);
