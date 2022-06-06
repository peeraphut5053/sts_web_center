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
/// Head Object ///
$BoatNoteHead = new BoatNoteHead();
$BoatNoteHead->setConn($conn);
$BoatNoteHead->_HeadCode = $HeadCode;
$BoatNoteHead->GetProperty();
///Lists object ////
$BoatNoteList= new BoatNoteList();
$BoatNoteList->setConn($conn);
$BoatNoteList->_HeadCode = $HeadCode ;
$result  = $BoatNoteList->GetRowsWithHeader();
$total  = 0;
foreach($result AS $index => $rows ) {
    
    $lists = $lists . "<tr>"
            . "<td>" . $rows["LotNo"] . "</td>"
            . "<td>" . $rows["HB_Name"] . "</td>"
            . "<td>" . number_format($rows["Bundles"],2) . "</td>"
            . "<td>" . $rows["Description"] . "</td>"
            . "<td>" . number_format($rows["GrossWeight"],2) . "</td>"
            . "</tr>";
    $total =$total + $rows["Bundles"] ;
}
$copy = "<a href='?view_copy&HeadCode=".$HeadCode."' class='btn btn-round'><i class='fa fa-file-o'></i></a>" ;

//$temp->setReplace("{copy}", $copy);
$temp->setReplace("{rollback}", "");
$temp->setReplace("{Total}", number_format($total));
$temp->setReplace("{VisiblePrintEdit}", "visible");
$temp->setReplace("{bgcolor}", "#ffffff");
$temp->setReplace("{MV}", $BoatNoteHead->Ship_MV );
$temp->setReplace("{LighterNo}", $BoatNoteHead->Ship_LighterNo);
$temp->setReplace("{BerthedAt}",$BoatNoteHead->HB_Name);
$temp->setReplace("{CMDate}",$BoatNoteHead->CommencedDate);
$temp->setReplace("{CPDate}",$BoatNoteHead->CompleteDate);
$temp->setReplace("{docno}", $BoatNoteHead->HeadCode);
$temp->setReplace("{lists}", $lists);
