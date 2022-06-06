<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");
$temp->setReplace("{content}", $temp->getTemplate("./template/lists.html"));


////$results = "" ;
//$temp->setReplace("{test}",$results);


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
//=== quick search  ===//
//===advance search ===//
if (!empty($_GET["advancesearch"])) {
    $advancesearch = $_GET["advancesearch"];
    if (!empty($_GET["advSelect"])) {
        $advSelect = $_GET["advSelect"];
    } else {
          if(isset($_POST["advSelect"])){
              $advSelect = $_POST["advSelect"];
          }

    }
    if (!empty($_GET["advKeyword"])) {
        $advKeyword = $_GET["advKeyword"];
    } else {
        if(isset($_POST["advKeyword"])){
            $advKeyword = $_POST["advKeyword"] ;
        }
    }
    if (!empty($_GET["advStartDate"])) {
        $advStartDate = $_GET["advStartDate"];
    } else {
          if(isset($_POST["advStartDate"])){
               $advStartDate = $_POST["advStartDate"];
          }

    }
      if (!empty($_GET["advEndDate"])) {
        $advEndDate = $_GET["advEndDate"];
    } else {
         if(isset($_POST["advEndDate"])){
               $advEndDate = $_POST["advEndDate"];
         }

    }
}
//===advance search ===//
$cSql = new SqlSrv();
$BoatNoteHead= new BoatPlanHead() ;
$BoatNoteList =new BoatPlanList() ;
$BoatNoteHead->setConn($conn);
$BoatNoteList->setConn($conn) ;
///====Paging =====//
if ($advancesearch == "false") {
    $searchPart1 = "( Boat_MV LIKE '%$txtKeyword%' OR Boat_LighterNo LIKE '%$txtKeyword%'  OR HB_Name LIKE '%$txtKeyword%'    )";
} else {
    if ($advSelect == "MV") {
        $searchPart1 = "( Boat_MV LIKE '%$advKeyword%' )";

    }
    else if ($advSelect == "LN") {
        $searchPart1 = "( Boat_LighterNo LIKE '%$advKeyword%'  )";
    }
     else if ($advSelect == "BA") {
        $searchPart1 = "( HB_Name LIKE '%$advKeyword%'  )";
    } else

    if ($advSelect == "ALL") {
        $searchPart1 = "( Boat_MV LIKE '%$txtKeyword%' OR Boat_LighterNo LIKE '%$txtKeyword%'  OR HB_Name LIKE '%$txtKeyword%'    )";
    }
    $searchPart2 = "  AND ( StartDate >= '$advStartDate'   AND StartDate <= '$advEndDate' )";
}

$sql0 = " $searchPart1 $searchPart2  ";
// $sql0=" HeadId > 0  ";



$Num_Rows = $BoatNoteHead->CountRowsWithCond($sql0);

$Per_Page = 10;   // Per Page
$Page = 0;
if (empty($_GET["Page"])) {
    $Page = 1;
} else {
    $Page = $_GET["Page"];
}

$Prev_Page = $Page - 1;
$Next_Page = $Page + 1;

  $Page_Start = (($Per_Page * $Page)-$Per_Page);
  $Page_End = (($Per_Page * $Page) );
if($Page > 1 ){
    $Page_Start++ ;
}
if ($Num_Rows <= $Per_Page) {
    $Num_Pages = 1;
} else if (($Num_Rows % $Per_Page) == 0) {
    $Num_Pages = ($Num_Rows / $Per_Page);
} else {
    $Num_Pages = ($Num_Rows / $Per_Page) + 1;
    $Num_Pages = (int) $Num_Pages;
}

$result = $BoatNoteHead->GetRowsWithCondAndLimit($searchPart1.$searchPart2, $Page_Start, $Page_End) ;

// $temp->setReplace("{test}", $searchPart1.$searchPart2);

$i = 0;
$CountHC = 0;
$lists ="";
foreach($result as $indx => $rows ){
    $CountHC = 0;
    $CountHC = $BoatNoteList->CountRowsWithHeader($rows['HeadCode']) ;
    $tDate1 = new DateTime($rows['StartDate']);
    $tDate2 = new DateTime($rows['EndDate']);
    $StartDate = $tDate1->format('Y-m-d H:i:s');
    $EndDate = $tDate2->format('Y-m-d H:i:s');
    $i = $indx +1 ;
    $lists = $lists . "<tr>"
            . "<td>" . $i  . "</td>"
            . "<td>" . $rows['Boat_MV'] . "</td>"
            . "<td>" . $rows['Boat_LighterNo'] . "</td>"
            . "<td>" . $rows['HB_Name'] . "</td>"
            . "<td>" . $StartDate . "</td>"
//            . "<td>" . $EndDate . "</td>"
            . "<td>$CountHC <a href='?SHP/view&HeadCode=" . $rows['HeadCode'] . "'><i class='fa fa-search'></i></a></td>"
            . "<td>"
            . "<a href='?SHP/edit&HeadCode=" . $rows['HeadCode'] . "'   <i  class='fa fa-edit'></i></a> "
            . "<a href='' id = 'h-".$rows['HeadCode']."' style='color:red;' OnClick = 'return Cancel(this.id);' ><i class='fa fa-close'></i></a> </td> "
            . "</tr>";
}
$paging = "<div class'col-3s' style='padding-top:5px;'> Total&nbsp;&nbsp; &nbsp; <font color='orange'>$Num_Rows</font> &nbsp;&nbsp;Records </div> <div class='col-9'>";
if ($Prev_Page) {
    $paging = $paging . " <a class='btn  btn-first' href='?SHP/lists&Page=$Prev_Page&txtKeyword=$txtKeyword&advancesearch=$advancesearch&advKeyword=$advKeyword&advCMDate=$advCMDate&advCPDate=$advCPDate'> <<</a> ";
}
$colorActive = "";
for ($i = 1; $i <= $Num_Pages; $i++) {
    if ($i != $Page) {
        $paging = $paging . " <a class='btn  btn-page'  href='?SHP/lists&Page=$i&txtKeyword=$txtKeyword&advancesearch=$advancesearch&advKeyword=$advKeyword&advCMDate=$advCMDate&advCPDate=$advCPDate'>$i</a> ";
    } else {
        $paging = $paging . "<b style='background-color:#17a2b8;color:#fff;border:solid 1px #17a2b8;' class='btn  btn-page'> $i </b>";
    }
}
if ($Page != $Num_Pages) {
    $paging = $paging . " <a class='btn  btn-last' href ='?SHP/lists&Page=$Next_Page&txtKeyword=$txtKeyword&advancesearch=$advancesearch&advKeyword=$advKeyword&advCMDate=$advCMDate&advCPDate=$advCPDate'> >> </a> ";
}
$paging = $paging . " </div>";
//}
// sqlsrv_close($conn);
$selected1 = "";
$selected2 = "";
$selected3 = "";
$selected4 = "";

if (($advSelect == "") || ($advSelect == "ALL")) {
    $selected1 = "selected";
} else if ($advSelect == "MV") {
    $selected2 = "selected";
} else if ($advSelect == "LN") {
    $selected3 = "selected";
} else if ($advSelect == "BA") {
    $selected4 = "selected";
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
//$copy = "<a href='?SHP/list_cancel' class='btn btn-round'><i class='fa fa-copy'></i></a>" ;
//
//
//
//$temp->setReplace("{copy}", $copy);
$temp->setReplace("{advKeyword}", $advKeyword);
$temp->setReplace("{advStartDate}", $advStartDate);
$temp->setReplace("{advEndDate}", $advEndDate);
$temp->setReplace("{selected1}", $selected1);
$temp->setReplace("{selected2}", $selected2);
$temp->setReplace("{selected3}", $selected3);
$temp->setReplace("{selected4}", $selected4);
$temp->setReplace("{keyword}", $txtKeyword);
$temp->setReplace("{paging}", $paging);
$temp->setReplace("{lists}", $lists);
