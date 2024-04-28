<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
require_once "../initial.php";

$CallModel = new CallModel();
$CallModel->SyteLine_Models();
$CallModelObj = new Factory();
$CallModelObj->setConn($ConnSL);

if ($load == "ajax") {
    (isset($TransactionDateStarting)) ? $TransactionDateStarting = $TransactionDateStarting : $TransactionDateStarting = "";
    (isset($TransactionDateEnding)) ? $TransactionDateEnding = $TransactionDateEnding : $TransactionDateEnding = "";
	(isset($unit1)) ? $unit1 = $unit1 : $unit1 = null;

    $RPT_STS_factory_fix_report = $CallModelObj->RPT_STS_factory_fix_report($TransactionDateStarting,$TransactionDateEnding,$unit1);
    echo json_encode($RPT_STS_factory_fix_report);

} else if ($load == "RPT_STS_factory_fix_report") {
    (isset($TransactionDateStarting)) ? $TransactionDateStarting = $TransactionDateStarting : $TransactionDateStarting = "";
    (isset($TransactionDateEnding)) ? $TransactionDateEnding = $TransactionDateEnding : $TransactionDateEnding = "";
	(isset($unit1)) ? $unit1 = $unit1 : $unit1 = 0;

    $RPT_STS_factory_fix_report = $CallModelObj->RPT_STS_factory_fix_report($TransactionDateStarting,$TransactionDateEnding,$unit1);
    echo json_encode($RPT_STS_factory_fix_report);
}

