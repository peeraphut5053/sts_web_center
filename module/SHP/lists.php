<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//echo "1234" ;
$temp->setReplace("{content}", $temp->getTemplate("./template/SHP/lists.html"));
$CallModel = new CallModel();
$CallModel->SHP_Models();
//CallModel::CallShipPlanModel();
////$results = "" ;
//$temp->setReplace("{test}",$results);

$advCMDate = null;
$advCPDate = null;

//=== Filter ===//
$txtKeyword = "";
$advancesearch = "false";
$searchPart1 = "";
$searchPart2 = "";
$advKeyword = "";
$advSelect = "";
$advStartDate = "";
$advEndDate = "";
//=== quick search  ===//
if (isset($_POST["txtKeyword"])) {
    $txtKeyword = $_POST["txtKeyword"];
} else {
    if (!empty($_GET["txtKeyword"])) {
        $txtKeyword = $_GET["txtKeyword"];
    }
}

$cSql = new SqlSrv();
$BPH = new BoatPlanHead();
$BPL = new BoatPlanList();
$BPH->_StartDate = "" ;
$BPH->_EndDate="";
$BPH->_Berthed_Id ="" ;
$BPH->setConn($ConnWebApp);
$BPL->setConn($ConnWebApp);

$result = $BPH->GetRowsWithCond($searchPart1);



$i = 0;
$CountHC = 0;
$lists = "";
foreach ($result as $indx => $rows) {
    $CountHC = 0;
    $CountHC = $BPL->CountRowsWithHeader($rows['HeadCode']);

    $i = $indx + 1;
    $lists = $lists . "<tr>"
            . "<td>" . $i . "</td>"
            . "<td></td>"
            . "<td></td>"
            . "<td></td>"
            . "<td></td>"
            . "<td>$CountHC <a href='?SHP/view&HeadCode=" . $rows['HeadCode'] . "'><i class='fa fa-search'></i></a></td>"
            . "<td>"
            . "<a href='?SHP/edit&HeadCode=" . $rows['HeadCode'] . "'   <i  class='fa fa-edit'></i></a> "
            . "<a href='' id = 'h-" . $rows['HeadCode'] . "' style='color:red;' OnClick = 'return Cancel(this.id);' ><i class='fa fa-close'></i></a> </td> "
            . "</tr>";
}

$thisDate = date("Y-m-d H:m:s");
$date1 = "";
///Auto decrease 30 days for adv search
if ($advStartDate == "") {
    $date1 = str_replace('-', '/', $thisDate);
    $dateFilter1 = date('Y-m-d H:m:s', strtotime($date1 . "-30 days"));
    $advStartDate = $dateFilter1;
}
if ($advEndDate == "") {
    $advEndDate = date("Y-m-d H:m:s");
}

$temp->setReplace("{advKeyword}", $advKeyword);
$temp->setReplace("{advStartDate}", $advStartDate);
$temp->setReplace("{advEndDate}", $advEndDate);
$temp->setReplace("{keyword}", $txtKeyword);
$temp->setReplace("{lists}", $lists);
