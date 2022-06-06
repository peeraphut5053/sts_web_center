<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");
$CallModel = new CallModel();
$CallModel->SHP_Models();
$HeadCode = "";
$action = "";

$HeadCode = $_GET["HeadCode"];
$temp->setReplace("{content}", $temp->getTemplate("./template/SHP/SHP/view.html"));

$lists = "";
$cSql = new SqlSrv();
/// Head Object ///
$BoatPlanHead = new BoatPlanHead();
$BoatPlanHead->setConn($ConnWebApp);
$BoatPlanHead->_HeadCode = $HeadCode;
$BoatPlanHead->GetProperty();
///Lists object ////
$BoatPlanList = new BoatPlanList();
$BoatPlanList->setConn($ConnWebApp);
$BoatPlanList->_HeadCode = $HeadCode;
$result = $BoatPlanList->GetRowsWithHeader();
$total = 0;
// Get Item FROM db Tag /// 
$Item = new Item();
$Item->setConn($ConnWebApp_tag);



foreach ($result AS $index => $rows) {
    $Item->_id = $rows["itemTag"] ;
    $Item->GetProperty(); 
    
    $lists = $lists . "<tr>"
            . "<td>" . $rows["position"] . "</td>"
            . "<td>" . $rows["itemTag"] . "</td>"
             . "<td>" . $rows["co_num"] . "</td>"
            . "<td>" . $Item->item . "</td>"
            . "<td align='center'>" . number_format($Item->qty1,2) . "</td>"
            . "</tr>";
    $total =$total + $Item->qty1 ;
}
//$copy = "<a href='?view_copy&HeadCode=".$HeadCode."' class='btn btn-round'><i class='fa fa-file-o'></i></a>" ;
//$temp->setReplace("{copy}", $copy);
$temp->setReplace("{TotalQty}", number_format($total,2));
$temp->setReplace("{Total}", number_format($total));
$temp->setReplace("{VisiblePrintEdit}", "visible");
$temp->setReplace("{bgcolor}", "#ffffff");
$temp->setReplace("{MV}", $BoatPlanHead->Ship_MV);
$temp->setReplace("{LighterNo}", $BoatPlanHead->Ship_LighterNo);
$temp->setReplace("{BerthedAt}", $BoatPlanHead->HB_Name);
$temp->setReplace("{CMDate}", $BoatPlanHead->StartDate);
//$temp->setReplace("{CPDate}",$BoatPlanHead->CompleteDate);
$temp->setReplace("{docno}", $BoatPlanHead->HeadCode);
$temp->setReplace("{lists}", $lists);
