<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
	${$key} = trim($data);
}

include "./initial.php";
$CallModel = new CallModel();
$CallModel->SHP_Models();
$temp = new ReplaceHtml("../template/SHP/SHP/print.html");
$HeadCode = "";
$action = "";

$HeadCode = $_GET["HeadCode"];

$lists = "";
$cSql = new SqlSrv();

$Head = new BoatPlanHead();
$Head->setConn($ConnWebApp) ;
$Head->_HeadCode =  $HeadCode;
$Head->GetProperty();
$List = new BoatPlanList();
$List->setConn($ConnWebApp) ;
$List->_HeadCode =  $HeadCode;
$ListResult = $List->GetRowsWithHeader();

$total  = 0;
foreach ($ListResult as $index => $rows) {
      $lists = $lists . "<tr>"
            . "<td>" . $rows["position"] . "</td>"
            . "<td>" . $rows["itemTag"] . "</td>"
              . "<td>" . $rows["co_num"] . "</td>"
            . "<td>" . $rows["item"] . "</td>"
            . "<td >" . number_format($rows["qty1"],2) . "</td>"
//            . "<td>" . $rows["Description"] . "</td>"
            . "</tr>";
      $total =$total + $rows["qty1"] ;
}

$temp->setReplace("{VisiblePrintEdit}", "visible");
$temp->setReplace("{MV}", $Head->Ship_MV );
$temp->setReplace("{Total}", number_format($total));
$temp->setReplace("{LighterNo}", $Head->Ship_LighterNo );
$temp->setReplace("{BerthedAt}", $Head->HB_Name );
$temp->setReplace("{StartDate}", $Head->StartDate );
$temp->setReplace("{TotalQty}", number_format($total,2));
$temp->setReplace("{docno}", $Head->HeadCode  );
$temp->setReplace("{lists}", $lists);

echo $temp->getReplace();
