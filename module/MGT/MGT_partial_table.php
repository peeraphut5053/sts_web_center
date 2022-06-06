<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
$temp = new ReplaceHtml("../template/MGT/partial/main_table.html");
$toDay = date('Y-m-d');
$Last30Days = date('Y-m-d', strtotime('-30 days', strtotime($toDay)));


$line = "";
if (isset($searchType)) {
    $po_QC = new PO_QC();
    $po_QC->setConn($var);
    $ret = "";

    $GetTable = $po_QC->Ajax_GetRowsWithDate($startDate, $endDate, $status, $filterRefNo, $filterStsNo, $searchType);
    $arrReturn = array();
    $btnPrintTag = "";
    $CountData = 0;
    $x = 0;
    $Tmp = "";
    $tmpPass = "";
    $tmpStsNo = "";
    $tmpSno = "";
    $tmpPoDate = "";
    $tmpRef = "";
    $tmpGrnNum = "";
    $tmpPo = "";
    $tmpPoLine = "";
    $tmpCoilNo = "";
    $tmpStatus = "";
    $tmpWeight = "";
    $tmpWeights = "";
    $tmpGrade = "";
    $tmpHNo = "";
    $tmpThick = "";
    $tmpWidth = "";
    $tmpThicks = "";
    $tmpWidths = "";
    $transDate = "";
    $FontColor = "";
    $btnPrintTag = "";
    $TagId = "";
    $bgColor = "";
    $line = "";
    $tempLot = "";
    $DisabledOnTagPrint = "";
    $CheckBoxName = "";
    $btnColSearch = "";
    $btnColCopy = "";
    $colBarcode = "";
    foreach ($GetTable as $index => $value1) {
        $tmpPo = "";
        $tmpGrnNum = "";
        $btnPrintTag = "";
        $TagId = "";
        $CheckBoxName = "";
        $colBarcode = "";
        $x++;

        $value1["pass"] == 1 ? $tmpPass = "<i class = 'fas fa-check'></i>" : $tmpPass = "<i class = 'fa fa-times'></i>";
        $tmpStsNo = $value1["sts_no"];
        $tmpSno = $value1["sno"];
        $tmpPoDate = $value1["po_date"];
        $tmpRef = $value1["reference"];
        $tmpGrnNum = $value1["upload_grn_num"];
        $tempLot = $value1["sl_lot"];
        if ($value1["upload_po"] != null) {
            $tmpPo = $value1["upload_po"];
        }
        $tmpPoLine = $value1["upload_po_line"];
        $tmpCoilNo = $value1["c_no"];
        $tmpStatus = $value1["upload_status"];
        $tmpWeights = $value1["realweight"];
        $tmpWeight = $value1["weight"];
        $tmpGrade = $value1["grade"];
        $tmpHNo = $value1["h_no"];
        $tmpThick = $value1["thick"];
        $tmpWidth = $value1["width"];
        $tmpThicks = $value1["thicks"];
        $tmpWidths = $value1["widths"];
        $transDate = $value1["i_date"];
        $TagId = $value1["sl_tag_id"];

        $FontColor = "";
        $bgColor = "";
        $searchType == "normal" ? $CheckBoxName = "chTag_$tmpStsNo" : $CheckBoxName = "chTag2_$tmpStsNo";
        if ($tmpGrnNum) {
            $bgColor = "#efebeb";
            $FontColor = "red";
        } else {
            $bgColor = "#FFFFFF";
            $FontColor = "#666";
        }
        $btnColSearch = "<td><a class='btn btn-info form-control' href='#' OnClick= 'CallDialog(this.id)' id='$tmpStsNo'><i class='fa fa-search'></i></a></td>";
//        $btnColCopy = "<td><a class = 'btn btn-info form-control' style = 'color:#FFFFFF;' href = '#' OnClick = 'CopyThisRowToAll(this.id)' id = 'Copy_$tmpStsNo'><i class = 'fa fa-copy'></i></a></td>";
        if ($value1["upload_status"] == "Y") {
            if ($value1["sl_tag_id"] == null) {

                $btnPrintTag = "<input type='checkbox' class='form-control' tag-id='$TagId' href='#' $DisabledOnTagPrint   id='$CheckBoxName' value='1'>";
            } else {
                $btnPrintTag = "";
            }
        } else {
            $btnPrintTag = "";
        }
        $FixedStyle = "";
        $FixedStyle2 = "";
//        $FixedStyle3 = "style = 'width:5%;'";
        if ($searchType != "normal") {
            $btnPrintTag = "<input type='checkbox' class='form-control' tag-id='$TagId' href='#'  $DisabledOnTagPrint   id='$CheckBoxName' value='1'>";
            $btnColSearch = "";
            $btnColCopy = "";
//            $FixedStyle = "style = 'width:8%;'";
//            $FixedStyle2 = "style = 'width:7%;'";
//            $FixedStyle3 = "style = 'width:10%;'";
            $colBarcode = "<td align = 'left'  style = 'vertical-align:middle;color:green;'>$TagId</td>";
        }

        $line = $line . "<tr style = 'background-color:$bgColor'>";
        $line = $line . "<td>$x</td>";
        $line = $line . "<td>$tmpSno</td>";
        $line = $line . "<td>$tmpRef</td>";
        $line = $line . "<td>$tmpCoilNo</td>";
        $line = $line . "<td>$tmpHNo</td>";
        $line = $line . "<td>$tmpWeight</td>";
        $line = $line . "<td>$tmpGrade</td>";
        $line = $line . "<td>$tmpThick X $tmpWidth</td>";
        $line = $line . "<td>$tmpThicks X $tmpWidths</td>";
        $line = $line . "<td>$tmpPass</td>";
        $line = $line . "<td $FixedStyle >";
        $line = $line . "<input type = 'hidden' id = 'hdfTemp_$tmpStsNo' data-grade = '$tmpGrade' ";
        $line = $line . "data-uf-manu = '' data-widths = '$tmpWidths' data-width = '$tmpWidth' ";
        $line = $line . "data-thick = '$tmpThick' data-thicks = '$tmpThicks' data-lot = '$tempLot' ";
        $line = $line . "data-item = '' data-vend-name = '' data-qty = '' data-um = '' data-trans-date = '$transDate' ";
        $line = $line . "data-weight = '$tmpWeight' data-weights = '$tmpWeights' data-grn-num = '' data-po-num = '' ";
        $line = $line . " data-po-line = '' data-vend-num = '' data-po-date = '' data-coil-no = '$tmpCoilNo' data-sno = '$tmpSno' ";
        $line = $line . " data-reference = '$tmpRef' data-sts-no = '$tmpStsNo' >";
        $line = $line . " <input readonly style = 'color:$FontColor;' class = 'form-control' type = 'text'  id = 'txtGrnNum_$tmpStsNo' value = '$tmpGrnNum' ></td>";
        $line = $line . " <td $FixedStyle2><input style = 'color:$FontColor;' readonly class = 'form-control' type = 'text' id = 'txtPoNum_$tmpStsNo' value = '$tmpPo' ></td>";
        $line = $line . " <td $FixedStyle><input style = 'color:$FontColor;font-size:16px;font-weight:bold;' readonly class = 'form-control' type = 'text' id = 'txtPoLine_$tmpStsNo' value = '$tmpPoLine' ></td> ";
        $line = $line . "$btnColSearch $btnColCopy $colBarcode <td>$btnPrintTag</td>";
        $line = $line . "</div></td>";
        $line = $line . " </tr>";
    }
} else {
    $line = '<tr>';
    $line = $line . ' <td colspan="16">';
    $line = $line . '  <br>  <br>  <br>  <br>';
    $line = $line . '   <h1 style="color:#cdcdcd;">.. To show any data use search filter ...</h1>';
    $line = $line . '    <br>  <br>  <br>  <br>';
    $line = $line . '  </td>';
    $line = $line . '</tr>';
}



$temp->setReplace("{content_table}", $line);
echo $temp->getReplace();
sqlsrv_close($ConnSL);
