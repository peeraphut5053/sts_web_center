<?php

foreach ($_GET as $key => $value) {
  $$key = trim($value);
}

foreach ($_POST as $key => $value) {
  $$key = trim($value);
}
include "./initial.php";
//
$po_QC = new PO_QC();
$ret = " Return from AJAX : ";
$ret =$ret. $action ;
// if ($action == "GetTable") {
//     $GetTable = $po_QC->Ajax_GetRowsAll_Limit($limit);
//     //echo count($GetTable);
//     foreach ($GetTable as $index => $rows) {
//         $ret = $ret . "<tr>"
//                 . "<td>" . $rows["sts_no"] . "</td>"
//                 . "<td>" . $rows["sno"] . "</td>"
//                 . "<td>" . $rows["reference"] . "</td>"
//                 . "<td>" . $rows["qa"] . "</td>"
//                 . "<td>" . $rows["u_date"] . "</td>"
//                 . "<td>" . $rows["user"] . "</td>"
//                 . "<td>" . $rows["po_date"] . "</td>"
//                 . "<td>" . $rows["c_no"] . "</td>"
//                 . "<td>" . $rows["i_date"] . "</td>"
//                 . "</tr>";
//     }
// }
 if ($action == "GetTable2") {
   echo $ret;
    $tmpPass = "";
  	$tmpStsNo = "" ;
    $tmpSno = "" ;
    $tmpPoDate = "";
    $tmpGrnNum = "" ;
    $tmpPoNum = "" ;
    $tmpPoLine = "" ;
  //  echo $action ;
//     $GetTable = $po_QC->Ajax_GetRowsWithDate($startDate, $endDate);
//     $ret = count($GetTable);
//
// //    echo $startDate;
//     if (count($GetTable) <= 0) {
//         $ret = "<tr style='height:200px;'><td style='padding-top:28px;' colspan='11'> .. $startDate - $endDate No data ..</tr>";
//     } else {
//         $x= 0 ;
//         foreach ($GetTable as $index => $rows) {
//             $x++ ;
//             $rows["pass"] == 1 ? $tmpPass = "<i class='fa fa-check'></i>" : $tmpPass = "<i class='fa fa-close'></i>";
//       			$tmpStsNo= $rows["sts_no"]  ;
//             $tmpSno =  $rows["sno"]  ;
//             $tmpPoDate=$rows["po_date"]  ;
//             $tmpRef = $rows["reference"]  ;
//                   $ret = $ret . "<tr>"
//                           . "<td>" . $rows["sts_no"] . "</td>"
//                           . "<td>" . $rows["reference"] . "</td>"
//                           . "<td>" . $rows["c_no"] . "</td>"
//                           . "<td>" . $rows["h_no"] . "</td>"
//                           . "<td>" . $rows["weight"] . "</td>"
//                           . "<td>" . $rows["grade"] . "</td>"
//                           . "<td>" . $rows["thick"] . " x " . $rows["width"] . "</td>"
//                           . "<td>" . $rows["thicks"] . " x " . $rows["widths"] . "</td>"
//                           . "<td>$tmpPass</td>"
//                           . "<td class='col-po'>"
//                           . "<div class='input-group'>"
//                           ."<input readonly type='text' id='txtGrnNum_". $rows["sts_no"]."' value=''  style='width:45%;padding-left:10px;padding-right:10px;' >"
//                           ."<input readonly type='text' id='txtPoNum_". $rows["sts_no"]."' value=''  style='width:45%;padding-left:10px;padding-right:10px;' >"
//                           ."<input readonly type='text' id='txtPoLine_". $rows["sts_no"]."' value=''  style='width:10%;padding-left:10px;padding-right:10px;'  >"
//                         //  ."<input readonly type='text' id='txtPoLine_". $rows["sts_no"]."' value='".$rows["upload_po_line"] ."'  data-po-num='' data-po-line='' data-vend-num='' data-u-date='' data-po-date='' data-sno='$tmpSno' data-reference='$tmpRef'  data-sts-no='$tmpStsNo' >"
//                           ."<span class='input-group-addon' id='spanSLPO_$x'> <a href='#' OnClick= 'CallDialog(this.id)' id='$tmpStsNo'><i class='fa fa-search'></i></a></span>"
//                           ."<span class='input-group-addon' id='spanRMSLPO_$x'>  <a href='#' OnClick= 'ClearRowsTextBox(this.id)' id='Clr_$tmpStsNo'><i class='fa fa-close'></i></a></span>"
//                           . "</div></td>"
//                           . "<td class='col-po'></td>"
//                           . "</tr>";
//               }
//     }
}
sqlsrv_close($ConnSL);
