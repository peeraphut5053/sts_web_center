<?php

// 
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        // ถ้าเป็น array ให้วนลูปอีกครั้งเพื่อ trim แต่ละ element
        foreach ($value as $subKey => $subValue) {
            $$key[$subKey] = trim($subValue);
        }
    } else {
        $$key = trim($value);
    }
}

include "../../initial.php";


if ($load == 'ajax') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $po_QC = new PO_QC();
    $po_QC->setConn($var);
    $where = "where 1=1";
    if (($txtFromDate != "") && ($txtToDate != "")) {
        $where = $where . " AND ( po_date BETWEEN '$txtFromDate' AND '$txtToDate' )  ";
    }
    if ($sno != "") {
        $where = $where . " AND sno = '" . $sno . "' ";
    }
    if ($c_no != "") {
        $where = $where . " AND c_no = '" . $c_no . "' ";
    }
    if ($h_no != "") {
        $where = $where . " AND h_no = '" . $h_no . "' ";
    }
    $po_QC = $po_QC->GetRowsWithCond2($where);
    $CallModel = null;
    echo json_encode($po_QC);
}else if ($load == 'tblReportDetail') {
    $CallModel = new CallModel();
    $CallModel->MGT_Models();
    $po_QC = new PO_QC();
    $po_QC->setConn($var);
    $where = "where 1=1";
    if ($sts_no != "") {
        $where = $where . " AND sts_no = '" . $sts_no . "' ";
    }
    $po_QC = $po_QC->tblReportDetail($where);
    $CallModel = null;
    echo json_encode($po_QC);
}


// if ($load == 'UpdateQcTestLab') {
//     $CallModel = new CallModel();
//     $CallModel->MGT_Models();
//     $UpdateQcTestLab = new PO_QC();
//     $UpdateQcTestLab->setConn($var);
//     $UpdateQcTestLab = $UpdateQcTestLab->UpdateQcTestLab($sts_no,$col_name,$valdata);
//     echo json_encode($sts_no);
// }

if ($load == 'UpdateQcTestLab_Main') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->UpdateQcTestLab_Main($sts_no,$col_name,$valdata);
    echo json_encode($sts_no);
}

if ($load == 'search') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $where = "where 1=1";
    if (($from_stsno != "") && ($to_stsno != "")) {
        $where = $where . " AND ( STS_QA_LAB.sts_no BETWEEN '$from_stsno' AND '$to_stsno' )  ";
    }
    if ($c_no != "") {
        $where = $where . " AND STS_QA_LAB.c_no = '" . $c_no . "' ";
    }
    if ($h_no != "") {
        $where = $where . " AND STS_QA_LAB.h_no = '" . $h_no . "' ";
    }
    if ($weight != "") {
        $where = $where . " AND STS_QA_LAB.thick = '" . $weight . "' ";
    }
    if ($width != "") {
        $where = $where . " AND STS_QA_LAB.width = '" . $width . "' ";
    }
    $QcTestLab = $QcTestLab->SearchQcTestLab_Main($where);
    echo json_encode($QcTestLab);    
 }

 if ($load == 'tblReportSub') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $where = "where 1=1";
    if ($sts_no != "") {
        $where = $where . " AND b.sts_no = '" . $sts_no . "' ";
    }
    $QcTestLab = $QcTestLab->SearchQcTestLab_Sub($where,$load);
    echo json_encode($QcTestLab); 
}

if ($load == 'ReportSub') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $where = "where 1=1";
    if (($from_stsno != "") && ($to_stsno != "")) {
        $where = $where . " AND b.sts_no BETWEEN '$from_stsno' AND '$to_stsno'";
    }
    // else if ($c_no != "") {
    //     $where = $where . " c_no = '" . $c_no . "' ";
    // }
    // else if ($h_no != "") {
    //     $where = $where . " h_no = '" . $h_no . "' ";
    // }
    if ($size != "") {
        $where = $where . " AND size = '" . $size . "' ";
    }
    if ($standard != "") {
        $where = $where . " AND standard_sub = '" . $standard . "' ";
    }
    if ($prod_FM_no != "") {
        $where = $where . " AND prod_FM_no = '" . $prod_FM_no . "' ";
    }
    if ($prod_date != "") {
        $where = $where . " AND prod_date = '" . $prod_date . "' ";
    }
    $QcTestLab = $QcTestLab->SearchQcTestLab_Sub($where,$load);
    echo json_encode($QcTestLab); 
}

if ($load == 'InsertQcTestLab_Main') {
    $sts_no = $_POST["sts_no"] ;
 for ($i=0;$i<count($sts_no);$i++){
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab[$i] = new QcTestLab();
    $QcTestLab[$i]->setConn($ConnSL);
    $QcTestLab[$i] = $QcTestLab[$i]->InsertQcTestLab_Main($_POST["standard"][$i], $_POST["thick"][$i], $_POST["width"][$i], $_POST["weight"][$i], $_POST["sts_no"][$i], $_POST["c_no"][$i], $_POST["h_no"][$i],
    $_POST["sup_c"][$i], $_POST["sup_si"][$i], $_POST["sup_mn"][$i], $_POST["sup_p"][$i], $_POST["sup_s"][$i], $_POST["sup_cu"][$i], $_POST["sup_v"][$i], $_POST["sup_ni"][$i], $_POST["sup_cr"][$i], $_POST["sup_mo"][$i], $_POST["sup_ti"][$i], $_POST["sup_nb"][$i], $_POST["sup_al"][$i], $_POST["sup_b"][$i], $_POST["sup_co"][$i], $_POST["sup_pb"][$i], $_POST["sup_fe"][$i], $_POST["sup_ts"][$i], $_POST["sup_ys"][$i], $_POST["sup_el"][$i], 
    $_POST["sts_c"][$i], $_POST["sts_si"][$i], $_POST["sts_mn"][$i], $_POST["sts_p"][$i], $_POST["sts_s"][$i], $_POST["sts_cu"][$i], $_POST["sts_v"][$i], $_POST["sts_ni"][$i], $_POST["sts_cr"][$i], $_POST["sts_mo"][$i], $_POST["sts_ti"][$i], $_POST["sts_nb"][$i], $_POST["sts_al"][$i], $_POST["sts_b"][$i], $_POST["sts_co"][$i], $_POST["sts_pb"][$i], $_POST["sts_fe"][$i], $_POST["sts_ts"][$i], $_POST["sts_ys"][$i], $_POST["sts_el"][$i]);
    echo json_encode($sts_no[$i]);    
   
    // if (isset ($_POST['standard'])){
        // $standard = $_POST["standard"] ;
        // $thick = $_POST["thick"] ;
        // $width = $_POST["width"] ;
        // $weight = $_POST["weight"] ;
        // $sts_no = $_POST["sts_no"] ;
        // $c_no = $_POST["c_no"] ;
        // $h_no = $_POST["h_no"] ;

        // $sup_c = $_POST["sup_c"] ;
        // $sup_si = $_POST["sup_si"] ;
        // $sup_mn = $_POST["sup_mn"] ;
        // $sup_p = $_POST["sup_p"] ;
        // $sup_s = $_POST["sup_s"] ;
        // $sup_cu = $_POST["sup_cu"] ;
        // $sup_v = $_POST["sup_v"] ;
        // $sup_ni = $_POST["sup_ni"] ;
        // $sup_cr = $_POST["sup_cr"] ;
        // $sup_mo = $_POST["sup_mo"] ;
        // $sup_ti = $_POST["sup_ti"] ;
        // $sup_nb = $_POST["sup_nb"] ;
        // $sup_al = $_POST["sup_al"] ;
        // $sup_b = $_POST["sup_b"] ;
        // $sup_co = $_POST["sup_co"] ;
        // $sup_pb = $_POST["sup_pb"] ;
        // $sup_fe = $_POST["sup_fe"] ;
        // $sup_ts = $_POST["sup_ts"] ;
        // $sup_ys = $_POST["sup_ys"] ;
        // $sup_el = $_POST["sup_el"] ;

        // $sts_c = $_POST["sts_c"] ;
        // $sts_si = $_POST["sts_si"] ;
        // $sts_mn = $_POST["sts_mn"] ;
        // $sts_p = $_POST["sts_p"] ;
        // $sts_s = $_POST["sts_s"] ;
        // $sts_cu = $_POST["sts_cu"] ;
        // $sts_v = $_POST["sts_v"] ;
        // $sts_ni = $_POST["sts_ni"] ;
        // $sts_cr = $_POST["sts_cr"] ;
        // $sts_mo = $_POST["sts_mo"] ;
        // $sts_ti = $_POST["sts_ti"] ;
        // $sts_nb = $_POST["sts_nb"] ;
        // $sts_al = $_POST["sts_al"] ;
        // $sts_b = $_POST["sts_b"] ;
        // $sts_co = $_POST["sts_co"] ;
        // $sts_pb = $_POST["sts_pb"] ;
        // $sts_fe = $_POST["sts_fe"] ;
        // $sts_ts = $_POST["sts_ts"] ;
        // $sts_ys = $_POST["sts_ys"] ;
        // $sts_el = $_POST["sts_el"] ;
    
   
    //     // echo $_POST["sup_si"][$i];
    //     // echo $sts_no[$i] ;
    //     echo $sts_el[$i] ;
    }
}
if ($load == 'InsertQcTestLab_Sub') {
    $sts_no = is_array($_POST["sts_no"]);
 for ($i=0;$i<count($sts_no);$i++){
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab[$i] = new QcTestLab();
    $QcTestLab[$i]->setConn($ConnSL);
    $QcTestLab[$i] = $QcTestLab[$i]->InsertQcTestLab_Sub($_POST["item"][$i],$_POST["opr_no"][$i], $_POST["size"][$i], $_POST["schedule"][$i], $_POST["length"][$i], $_POST["standard"][$i], $_POST["grade"][$i], $_POST["sts_no"][$i], 
    $_POST["Mec_test_TS"][$i], $_POST["Mec_test_YS"][$i], $_POST["Mec_test_EI"][$i], $_POST["Mec_test_TS_2"][$i], $_POST["Mec_test_YS_2"][$i], $_POST["Mec_test_EL_2"][$i],
    $_POST["Not_Mec_test_TS"][$i], $_POST["Not_Mec_test_YS"][$i], $_POST["Not_Mec_test_EI"][$i], $_POST["Not_Mec_test_TS_2"][$i], $_POST["Not_Mec_test_YS_2"][$i], $_POST["Not_Mec_test_EL_2"][$i], $_POST["charpy_mean"][$i], $_POST["charpy1"][$i], $_POST["charpy2"][$i], $_POST["charpy3"][$i], $_POST["prod_FM_no"][$i], $_POST["prod_date"][$i], $_POST["test_date"][$i], $_POST["remark"][$i]);
    echo json_encode($i);    
    }
}

if ($load == 'searchAll') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->ReportAll($from_stsno, $to_stsno, $do_num, $h_no, $size, $standard, $prod_FM_no, $prod_date);
    echo json_encode($QcTestLab); 
}


if ($load == 'makePDF') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->makeReport($do_num);
    echo json_encode($QcTestLab); 
}

if ($load == 'makePDF_Header') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->makePDF_Header($do_num);
    echo json_encode($QcTestLab); 
}

if ($load == 'loadTest') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->loadTest($do_num);
    echo json_encode($QcTestLab); 
}

if ($load == 'saveTest') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->saveTest($do_num,$TestOne,$TestTwo,$TestThree,$TestFour, $TestFive);
    echo json_encode($QcTestLab); 
}

if ($load == 'DeleteMain') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->DeleteMain($sts_no);
    echo json_encode($QcTestLab); 
}

if ($load == 'DeleteSub') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->DeleteSub($opr_no,$length, $sts_no, $prod_FM_no, $prod_Date, $size);
    echo json_encode($QcTestLab); 
}

if ($load == 'UpdateSub') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->UpdateSub($opr_no,$length, $sts_no, $prod_FM_no, $prod_Date, $remark);
    echo json_encode($QcTestLab); 
}

if ($load == 'UpdateSubTest') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->UpdateSubTest($opr_no,$length, $sts_no, $prod_FM_no, $prod_Date, $form, $value);
    echo json_encode($QcTestLab); 
}

if ($load == 'ReportZinc') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->GetReportZinc($month, $month2, $y);
    echo json_encode($QcTestLab);
}

if ($load == 'InsertExcelZinc') {
    $sts_no = $_POST["sts_Pb"] ;
    for ($i=0;$i<count($sts_no);$i++){
       $CallModel = new CallModel();
       $CallModel->SyteLine_Models();
       $QcTestLab[$i] = new QcTestLab();
       $QcTestLab[$i]->setConn($ConnSL);
       $QcTestLab[$i] = $QcTestLab[$i]->InsertExcelZinc(
        $_POST["sts_Pb"][$i],
        $_POST["sts_Al"][$i],
        $_POST["sts_Cd"][$i],
        $_POST["sts_Fe"][$i],
        $_POST["sts_Cu"][$i],
        $_POST["sts_Zn"][$i],
        $_POST["Test_result"][$i],
        $_POST["Date_rec"][$i],
        $_POST["Date_test"][$i],
        $_POST["LeadTime"][$i],
        $_POST["remark"][$i]
      );
       echo json_encode($i);    
       }
}

if ($load == 'Laboratory') {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $QcTestLab = new QcTestLab();
    $QcTestLab->setConn($ConnSL);
    $QcTestLab = $QcTestLab->GetLaboratory($StartDate, $EndDate);
    echo json_encode($QcTestLab);
}




