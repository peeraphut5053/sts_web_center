<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
	${$key} = trim($data);
}

include "./initial.php";

$temp = new ReplaceHtml("../template/bn_print.html");
$HeadCode = "";
$action = "";

$HeadCode = $_GET["HeadCode"];

$lists = "";
$cSql = new SqlSrv();

$Head = new BoatNoteHead();
$Head->setConn($conn) ;
$Head->_HeadCode =  $HeadCode;
$Head->GetProperty();
$List = new BoatNoteList();
$List->setConn($conn) ;
$List->_HeadCode =  $HeadCode;
$ListResult = $List->GetRowsWithHeader();

$total  = 0;
foreach ($ListResult as $index => $rows) {
    $lists = $lists . "<tr>"
            . "<td>" .$rows["LotNo"] . "</td>"
            . "<td>" .$rows["HB_Name"] . "</td>"
            . "<td>" .number_format($rows["Bundles"],2 ). "</td>"
            . "<td>" .$rows["Description"] . "</td>"
            . "<td>" .number_format($rows["GrossWeight"],2) . "</td>"
            . "</tr>";
      $total =$total + $rows["Bundles"] ;
}

$temp->setReplace("{VisiblePrintEdit}", "visible");
$temp->setReplace("{MV}", $Head->Ship_MV );
$temp->setReplace("{Total}", number_format($total));
$temp->setReplace("{LighterNo}", $Head->Ship_LighterNo );
$temp->setReplace("{BerthedAt}", $Head->HB_Name );
$temp->setReplace("{CMDate}", $Head->CommencedDate );
$temp->setReplace("{CPDate}", $Head->CompleteDate );
$temp->setReplace("{docno}", $Head->HeadCode  );
$temp->setReplace("{lists}", $lists);

echo $temp->getReplace();
