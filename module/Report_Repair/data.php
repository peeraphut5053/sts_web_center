<?php

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

if ($load == "Search") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();

    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDataDocNo();
    echo json_encode($rs);
} else if ($load == "Create") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->InsertReportRepair($r_department, $r_name, $r_item, $remark, $detail_issue);
    echo json_encode($rs);
} else if ($load == "Get") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetReportRepair($StartDate, $EndDate, $doc_no, $types, $items);
    echo json_encode($rs);
} else if ($load == "StartRepair") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->StartRepair($doc_no, $item, $status, $detail_repair, $repair_name, $remark2, $due_date);
    echo json_encode($rs);
} else if ($load == "EndRepair") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->EndRepair($doc_no, $status, $detail_repair, $repair_name, $remark2);
    echo json_encode($rs);
} else if ($load == "SearchDocNo") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->SearchDocNo($doc_no);
    echo json_encode($rs);
} else if ($load == "Update") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->UpdateReportRepair($doc_no,$r_department, $r_item, $remark, $detail_issue, $time);
    echo json_encode($rs);
} else if ($load == "SearchCheck") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetReportRepairV2($StartDate, $EndDate, $doc_no, $types, $items, $status, $dept);
    echo json_encode($rs);    
} else if ($load == "Dept") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetDepartment();
    echo json_encode($rs);
} else if ($load == "Issue") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->GetIssue();
    echo json_encode($rs);
} else if ($load == "showImage") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $STS_Custom = new Factory();
    $STS_Custom->setConn($ConnSL);
    $rs = $STS_Custom->SelectReportRepairImage($doc_no);
    echo json_encode($rs);
}
 
