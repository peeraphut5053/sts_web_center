<?php

header("Access-Control-Allow-Origin: *");
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";
$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$CallModelObj = new Executive();
$CallModelObj->setConn($ConnSL);


//ระหว่างผลิต
if ($load == "ReportProductForming") {
    $JsonValue = $CallModelObj->ReportProductForming();
    echo json_encode($JsonValue);
}

if ($load == "STS_execRpt_W_byType_Live") {
    $JsonValue = $CallModelObj->STS_execRpt_W_byType_Live($daystart, $dayend);
    echo json_encode($JsonValue);
}

if ($load == "STS_execRpt_W_bySize_Live") {
    $JsonValue = $CallModelObj->STS_execRpt_W_bySize_Live($daystart, $dayend);
    echo json_encode($JsonValue);
}

if ($load == "STS_execRpt_W_bySizeType_Live") {
    $JsonValue = $CallModelObj->STS_execRpt_W_bySizeType_Live($daystart, $dayend);
    echo json_encode($JsonValue);
}




//สำเร็จรูป
if ($load == "ReportProductFinish") {
    $JsonValue = $CallModelObj->ReportProductFinish();
    echo json_encode($JsonValue);
}

if ($load == "STS_execRpt_F_byType_Live") {
    $JsonValue = $CallModelObj->STS_execRpt_F_byType_Live($daystart, $dayend);
    echo json_encode($JsonValue);
}

if ($load == "V_STS_execRpt_F_byMKTType_Live") {
    $JsonValue = $CallModelObj->V_STS_execRpt_F_byMKTType_Live($daystart, $dayend);
    echo json_encode($JsonValue);
}

if ($load == "STS_execRpt_F_bySize_Live") {
    $JsonValue = $CallModelObj->STS_execRpt_F_bySize_Live($daystart, $dayend);
    echo json_encode($JsonValue);
}


if ($load == "STS_execRpt_F_bySizeType_Live") {
    $JsonValue = $CallModelObj->STS_execRpt_F_bySizeType_Live($daystart, $dayend);
    echo json_encode($JsonValue);
}



//สรุปราม
if ($load == "setExecutiveSummaryData1") {
    $JsonValue = $CallModelObj->setExecutiveSummaryData1();
    echo json_encode($JsonValue);
}


if ($load == "setExecutiveSummaryData2") {
    $JsonValue = $CallModelObj->setExecutiveSummaryData2();
    echo json_encode($JsonValue);
}


//กราฟเส้น
if ($load == "STS_execRpt_SUM_mthly") {
    $JsonValue = $CallModelObj->STS_execRpt_SUM_mthly($startDate, $endDate, $codeItem);
    echo json_encode($JsonValue);
}

if ($load == "STS_execRpt_SUM_mthly_all") {
    $JsonValue = $CallModelObj->STS_execRpt_SUM_mthly_all();
    
    echo json_encode($JsonValue);
}

//pie graph
if ($load == "STS_execSUM_Coil_Rec") {
    $JsonValue = $CallModelObj->STS_execSUM_Coil_Rec($startDate, $endDate);
    echo json_encode($JsonValue);
}

if ($load == "STS_execSUM_Pipe_Sale") {
    $JsonValue = $CallModelObj->STS_execSUM_Pipe_Sale($startDate, $endDate);
    echo json_encode($JsonValue);
}


//OUTSTANDDING

if ($load == "V_STS_execSUM_Outs_Coil") {
    $JsonValue = $CallModelObj->V_STS_execSUM_Outs_Coil();
    echo json_encode($JsonValue);
}

if ($load == "V_STS_execSUM_Outs_Strip") {
    $JsonValue = $CallModelObj->V_STS_execSUM_Outs_Strip();
    echo json_encode($JsonValue);
}

if ($load == "V_STS_execSUM_Outs_ProcessingPipe") {
    $JsonValue = $CallModelObj->V_STS_execSUM_Outs_ProcessingPipe();
    echo json_encode($JsonValue);
}

if ($load == "V_STS_execSUM_Outs_FinishedPipe") {
    $JsonValue = $CallModelObj->V_STS_execSUM_Outs_FinishedPipe();
    echo json_encode($JsonValue);
}

if ($load == "V_STS_execSUM_Outs_Finished_BarePipe") {
    $JsonValue = $CallModelObj->V_STS_execSUM_Outs_Finished_BarePipe();
    echo json_encode($JsonValue);
}

if ($load == "V_STS_execSUM_Outs_Finished_BundledPipe") {
    $JsonValue = $CallModelObj->V_STS_execSUM_Outs_Finished_BundledPipe();
    echo json_encode($JsonValue);
}

if ($load == "matltran_mst_count_intreval_1") {
    $JsonValue = $CallModelObj->matltran_mst_count_intreval_1($startDate,$endDate);
    echo json_encode($JsonValue);
}

if ($load == "matltran_mst_count_intreval") {
    $JsonValue = $CallModelObj->matltran_mst_count_intreval($startDate,$endDate);
    echo json_encode($JsonValue);
}

if ($load == "matltran_mst_intreval") {
    $JsonValue = $CallModelObj->matltran_mst_intreval();
    echo json_encode($JsonValue);
}



if ($load == "STS_rpt_COitem_sum") {
    $JsonValue = $CallModelObj->STS_rpt_COitem_sum($startDate,$endDate,$start_co_num,$end_co_num,$cust_num);
    echo json_encode($JsonValue);
}

if ($load == "STS_rpt_COitem") {
    $JsonValue = $CallModelObj->STS_rpt_COitem($co_num);
    echo json_encode($JsonValue);
}


if ($load == "MenuDoItem") {
    $JsonValue = $CallModelObj->MenuDoItem($co_num,$co_line);
    echo json_encode($JsonValue);
}

if ($load == "MenuInvItem") {
    $JsonValue = $CallModelObj->MenuInvItem($co_num,$co_line);
    echo json_encode($JsonValue);
}



if ($load == "auditlog") {
    $JsonValue = $CallModelObj->auditlog($startDate,$endDate);
    echo json_encode($JsonValue);
}

if ($load == "ProductionDashboardP") {
    $JsonValue = $CallModelObj->ProductionDashboardP();
    echo json_encode($JsonValue);
}

if ($load == "ProductionDashboardW") {
    $JsonValue = $CallModelObj->ProductionDashboardW();
    echo json_encode($JsonValue);
}

if ($load == "STS_execRpt_F_byType_Live_Detail") {
    $JsonValue = $CallModelObj->STS_execRpt_F_byType_Live_Detail($daystart, $dayend, $country);
    echo json_encode($JsonValue);
}
	
if ($load == "STS_execRpt_F_bySize_Live_Detail") {
    $JsonValue = $CallModelObj->STS_execRpt_F_bySize_Live_Detail($daystart, $dayend, $country);
    echo json_encode($JsonValue);
}

if ($load == "STS_execRpt_F_bySizeType_Live_Detail") {
    $JsonValue = $CallModelObj->STS_execRpt_F_bySizeType_Live_Detail($daystart, $dayend, $country);
    echo json_encode($JsonValue);
}