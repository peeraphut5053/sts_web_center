<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

require_once "../initial.php";
if ($load == "ajax") {
//    $temp = new ReplaceHtml("../../template/SHP/sl_search_co.html");
//    echo $temp->getReplace();
} else if ($load == "CustomerSelect") {
    $temp->setReplace("{content}", $temp->getTemplate("./template/CER_DO/index.html"));
} else if ($load == "itemList") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Item = new QuantityMove();
    $Item->setConn($ConnSL);
    $Item->_ItemSearch = $ItemSearch;
    $Item = $Item->GetListItem();
    echo json_encode($Item);
} else if ($load == "savedata") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $qtyArr = $_POST['qty'];
    $itemArr = $_POST['itemArr'];
    $FromLoc = $_POST['FromLoc'];
    $ToLoc = $_POST['ToLoc'];

    $lastdoc = new QuantityMove();
    $lastdoc->setConn($ConnWebApp);
    $lastdoc = $lastdoc->getLastDoc();

    $Item = new QuantityMove();
    $Item->setConn($ConnWebApp);
    $Item->_doc_no = $doc_no;
    $Item->_create_date = $create_date;
    $Item->_create_by = $create_by;
    $Item->_ArrQty = $qtyArr;
    $Item->_ArrItem = $itemArr;
    $Item->_ArrFrom_loc = $FromLoc;
    $Item->_ArrTo_loc = $ToLoc;
    $Item = $Item->SaveData($lastdoc);
    echo json_encode($qtyArr);
} else if ($load == "DocList") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Doc = new QuantityMove();
    $Doc->setConn($ConnWebApp);
    if (isset($Fromdate)) {
        $Doc->_Fromdate = $Fromdate;
    }
    if (isset($Todate)) {
        $Doc->_Todate = $Todate;
    }
    $DocList = $Doc->GetListDoc();
    echo json_encode($DocList);
} else if ($load == "GetLocation") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $Loc = new QuantityMove();
    $Loc->setConn($ConnSL);
    $LocList = $Loc->GetLocation();
    echo json_encode($LocList);
} else if ($load == "GetDocumentDetail") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DocDetail = new QuantityMove();
    $DocDetail->setConn($ConnWebApp);
    $DocDetail = $DocDetail->GetDocumentDetail($doc_no);
    echo json_encode($DocDetail);
} else if ($load == "GetAllDocumentDetail") {
    $CallModel = new CallModel();
    $CallModel->SyteLine_Models();
    $DocDetail = new QuantityMove();
    $DocDetail->setConn($ConnWebApp);
    $DocDetail = $DocDetail->GetAllDocumentDetail();
    echo json_encode($DocDetail);
}

