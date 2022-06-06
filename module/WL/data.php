<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";


//if (!isset($load)) {
//    $load = $_POST["load"];
//}
if ($load == "form") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "ajax") {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $CallModel->SyteLine_Models();
    $lines = "";

    $s = " WHERE 1=1 ";
    if (($StartDate != "") && ($EndDate != "")) {
        if ($selDateType == "P") {
            $s = $s . " AND ( po_date BETWEEN '$StartDate' AND '$EndDate' )  ";
        } else if ($selDateType == "I") {
            $s = $s . " AND ( i_date BETWEEN '$StartDate' AND '$EndDate' )  ";
        } else if ($selDateType == "U") {
            $s = $s . " AND ( u_date BETWEEN '$StartDate' AND '$EndDate' )  ";
        }
    }
    if ($isTransfer == "N") {
        $s = $s . " AND (  transfer =0 OR transfer is null ) ";
    }
    if ($isTransfer == "T") {
        $s = $s . " AND (  transfer =1 ) ";
    }
    $DataAll = array();
    $FldThick = "";
    $FldThicks = "";
    $FldWidth = "";
    $FldWidths = "";
    $FldWeight = "";
    $FldWeights = "";
    if ($selDB == "O") {
        $poqc = new PO_QC();
        $poqc->setConn($var);
        $DataAll = $poqc->GetRowsWithCond($s);
        $FldThick = "thick";
        $FldThicks = "thicks";
        $FldWidth = "width";
        $FldWidths = "widths";
        $FldWeight = "weight";
        $FldWeights = "realweight";
    } else {
        $WL = new WeightList();
        $WL->setConn($ConnSL);
        $DataAll = $WL->GetRowsWithCond($s);
        $FldThick = "thick";
        $FldThicks = "real_thick";
        $FldWidth = "width";
        $FldWidths = "real_width";
        $FldWeight = "weight";
        $FldWeights = "real_weight";
    }

    $isTrans = "";
    $tooltip = "";
    $po_date = "";
    $i_date = "";
    $u_date = "";
    $trans = "";
    $tPass = "";
    foreach ($DataAll as $ii => $rr) {
        if ($selDB == "O") {
            $po_date = $rr["po_date"];
            $i_date = $rr["i_date"];
            $u_date = $rr["u_date"];
            $trans = $rr["transfer"];
        } else {
            $rr["po_date"] ? $po_date = $rr["po_date"]->format('Y-m-d') : $po_date = "-";
            $rr["i_date"] ? $i_date = $rr["i_date"]->format('Y-m-d') : $i_date = "-";
            $rr["u_date"] ? $u_date = $rr["u_date"]->format('Y-m-d') : $u_date = "-";
            $trans = 1;
        }
        $tPass = "";
        $isTrans = "";
        $rr["pass"] == 1 ? $tPass = "<i style='color:green;' class='fa fa-check-circle'></i>" : $tPass = "<i style='color:gray;' class='fa fa-check-circle'></i>";
        $trans == 1 ? $isTrans = "<i style='color:green;' class='fa fa-upload'></i>" : $isTrans = "<i style='color:gray;' class='fa fa-upload'></i>";
        $tooltip = "<div class='tooltip'><i class='fa fa-eye'></i></span>"
                . "<span class='tooltiptext text-right'>"
                . "s_no : " . $rr["sno"] . ""
                . "<br>grade : " . $rr["grade"] . ""
                . "<br>reference : " . $rr["reference"] . ""
                . "</div>";
        $lines = $lines . "<tr  id='Row_" . $rr["sts_no"] . "'>"
                . "<td id='StsNo_" . $rr["sts_no"] . "'>" . $rr["sts_no"] . " </td>"
                . "<td id='Sno_" . $rr["sts_no"] . "'>" . $rr["sno"] . "</td>"
                . "<td id='PoDate_" . $rr["sts_no"] . "'>$po_date</td>"
                . "<td id='IDate_" . $rr["sts_no"] . "'>$i_date</td>"
                . "<td id='UDate_" . $rr["sts_no"] . "'>$u_date</td>"
                . "<td id='CNo_" . $rr["sts_no"] . "'>" . $rr["c_no"] . "</td>"
                . "<td id='HNo_" . $rr["sts_no"] . "'>" . $rr["h_no"] . "</td>"
                . "<td id='Weight_" . $rr["sts_no"] . "'>" . $rr[$FldWeight] . "</td>"
                . "<td id='RealWeight_" . $rr["sts_no"] . "'>" . $rr[$FldWeights] . "</td>"
                . "<td id='WidthXThick_" . $rr["sts_no"] . "'>" . $rr[$FldWidth] . " x " . $rr[$FldThick] . "</td>"
                . "<td id='RealWidthXThick_" . $rr["sts_no"] . "'>" . $rr[$FldWidths] . " x " . $rr[$FldThicks] . "</td>"
                . "<td id='Pass_" . $rr["sts_no"] . "'>$tPass</td>"
                . "<td align='center' id='Transfer_" . $rr["sts_no"] . "'>$isTrans</td>"
                . "<td id='ToolTip_" . $rr["sts_no"] . "'>$tooltip</td>"
                . "<td id='Edit_" . $rr["sts_no"] . "'></td>"
                . "</tr>";
    }
    $WL = null;
    echo $lines;
} else if ($load == "ajax_checkdata") {
    $CallModel2 = new CallModel();
    $CallModel2->MGT_Models();
    $UpWl = New PO_QC();
    $UpWl->setConn($var);
    $s = " WHERE 1=1 ";
    if (($StartDate != "") && ($EndDate != "")) {
        $s = $s . " AND ( po_date BETWEEN '$StartDate' AND '$EndDate' )  ";
    }
    if ($selTransfer == "N") {
        $s = $s . " AND (  transfer =0 OR transfer is null ) ";
    }
    if ($selTransfer == "T") {
        $s = $s . " AND (  transfer =1 ) ";
    }
    $UpWls = $UpWl->GetRowsWithCond($s);
    $Count = count($UpWls);
    $UpWls = null;
    echo $Count;
} else if ($load == "ajax_transfer") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $CallModel2 = new CallModel();
    $CallModel2->MGT_Models();
    $UpWl = New PO_QC();
    $UpWl->setConn($var);
    $s = " WHERE 1=1 ";
    if (($StartDate != "") && ($EndDate != "")) {
        $s = $s . " AND ( po_date BETWEEN '$StartDate' AND '$EndDate' )  ";
    }
    if ($selTransfer == "N") {
        $s = $s . " AND (  transfer =0 OR transfer is null ) ";
    }
    if ($selTransfer == "T") {
        $s = $s . " AND (  transfer =1 ) ";
    }

    $UpWls = $UpWl->GetRowsWithCond($s);

    $WlModel = new WeightList();
    $WlModel->setConn($ConnSL);
    $Success = 0;
    $Error = 0;
//    $x = count($sts_no);
    foreach ($UpWls as $ii => $rr) {
//    for ($i = 0; $i < $x; $i++) {
        $WlModel->_sts_no = $rr["sts_no"];
        $WlModel->_po_date = $rr["po_date"];
        $WlModel->_c_no = $rr["c_no"];
        $WlModel->_h_no = $rr["h_no"];
        $WlModel->_weight = $rr["weight"];
        $WlModel->_realweight = $rr["realweight"];
        $WlModel->_grade = $rr["grade"];
        $WlModel->_thick = $rr["thick"];
        $WlModel->_thicks = $rr["thicks"];
        $WlModel->_width = $rr["width"];
        $WlModel->_widths = $rr["widths"];
        $WlModel->_reference = $rr["reference"];
        $WlModel->_qa = $rr["qa"];
        $WlModel->_location = $rr["location"];
        $WlModel->_i_date = $rr["i_date"];
        $WlModel->_u_date = $rr["u_date"];
        $WlModel->_s_no = $rr["sno"];
        $WlModel->_pass = $rr["pass"];
        $WlModel->_rows = $rr["rows"];
        $WlModel->_remark = $rr["remark"];
        $WlModel->_upload_status = $rr["upload_status"];
        $WlModel->_upload_grn_num = $rr["upload_grn_num"];
        $WlModel->_upload_po = $rr["upload_po"];
        $WlModel->_upload_po_line = $rr["upload_po_line"];
        $WlModel->_sl_lot = $rr["sl_lot"];
        $WlModel->_sl_tag_status = $rr["sl_tag_status"];
        $WlModel->_sl_trans = $rr["sl_trans"];
        $WlModel->_sl_tag_id = $rr["sl_tag_id"];
        if ($WlModel->Insert() == 1) {
            $UpWl->UpdateTransfer($rr["sts_no"]);
            $Success++;
        } else {
            $Error++;
        }
    }
    $Result = array();
    $Result["Success"] = $Success;
    $Result["Error"] = $Error;

    $UpWls = null;
    $WlModel = null;
//    $Return = "error";
//    if ($Result == $x) {
//        $Return = "success";
//    }
    $UpWl = null;
    echo json_encode($Result);
} else if ($load == "ajax_upload") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $CallModel2 = new CallModel();
    $CallModel2->MGT_Models();


    $UpWl = New PO_QC();
    $UpWl->setConn($var);

    $WlModel = new WeightList();
    $WlModel->setConn($ConnSL);
    $Result = 0;
    $x = count($sts_no);

    for ($i = 0; $i < $x; $i++) {
        $WlModel->_sts_no = $sts_no[$i];
        $WlModel->_po_date = $po_date[$i];
        $WlModel->_c_no = $c_no[$i];
        $WlModel->_h_no = $h_no[$i];
        $WlModel->_weight = $weight[$i];
        $WlModel->_realweight = $realweight[$i];
        $WlModel->_grade = $grade[$i];
        $WlModel->_thick = $thick[$i];
        $WlModel->_thicks = $thicks[$i];
        $WlModel->_width = $width[$i];
        $WlModel->_widths = $widths[$i];
        $WlModel->_reference = $reference[$i];
        $WlModel->_qa = $qa[$i];
        $WlModel->_location = $location[$i];
        $WlModel->_i_date = $i_date[$i];
        $WlModel->_u_date = $u_date[$i];
        $WlModel->_s_no = $s_no[$i];
        $WlModel->_pass = $pass[$i];
        $WlModel->_rows = $rows[$i];
        $WlModel->_remark = $remark[$i];
        $WlModel->_upload_status = $upload_status[$i];
        $WlModel->_upload_grn_num = $upload_grn_num[$i];
        $WlModel->_upload_po = $upload_po[$i];
        $WlModel->_upload_po_line = $upload_po_line[$i];
        $WlModel->_sl_lot = $sl_lot[$i];
        $WlModel->_sl_tag_status = $sl_tag_status[$i];
        $WlModel->_sl_trans = $sl_trans[$i];
        $WlModel->_sl_tag_id = $sl_tag_id[$i];
        if ($WlModel->Insert() == 1) {
            $UpWl->UpdateTransfer($sts_no[$i]);
            $Result++;
        }
    }
    $WlModel = null;
    $Return = "error";
    if ($Result == $x) {
        $Return = "success";
    }
    $UpWl = null;
    echo $Return;
} else if ($load == "ajax_clear") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $CallModel2 = new CallModel();
    $CallModel2->MGT_Models();
    $UpWl = New PO_QC();
    $UpWl->setConn($var);
    $Rs1 = $UpWl->ClearTransfer($StartDate, $EndDate);
    $WlModel = new WeightList();
    $WlModel->setConn($ConnSL);
    $Rs2 = $WlModel->ClearData($StartDate, $EndDate);

    $CallModel = null;
    $CallModel2 = null;
    $WlModel = null;
    $UpWl = null;
} else if ($load == "ajax_savedata") {
//    $alldata = $_POST["alldata"] ;

    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $WLModel = new WeightList();
    $WLModel->setConn($ConnSL);

    $alldata_grade = $_POST["alldata_grade"];
    $alldata_thick = $_POST["alldata_thick"];
    $alldata_width = $_POST["alldata_width"];
    $alldata_weight = $_POST["alldata_weight"];
    $alldata_h_no = $_POST["alldata_h_no"];
    $alldata_c_no = $_POST["alldata_c_no"];
    $alldata_qa = $_POST["alldata_qa"];
    $alldata_reference = $_POST["alldata_reference"];



    $GenSno = "";
    $GenStsNo = "";
    $result = "";
    for ($i = 0; $i < $x; $i++) {
        $WLModel->_grade = $grade[$i];
        $WLModel->_h_no = $h_no[$i];
        $WLModel->_c_no = $c_no[$i];
        $WLModel->_qa = $qa[$i];
        $WLModel->_reference = $reference[$i];
        $WLModel->_thick = $thick[$i];
        $WLModel->_width = $width[$i];
        $WLModel->_weight = $weight[$i];
        $result = $result . $WLModel->InsertWithSP();
    }
//    echo $result;
    $WLModel = null;
    $CallModel = null;
//    echo $grade[0];
} 
