<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
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
}  else if ($load == "RPT_STS_factory_fix_report_sub") {

    $RPT_STS_factory_fix_report = $CallModelObj->RPT_STS_factory_fix_report_sub($ref,$acct_unit1);
    echo json_encode($RPT_STS_factory_fix_report);
} else if ($load == "Getunit1List") {
    $RPT_STS_factory_fix_report = $CallModelObj->Getunit1List();
    echo json_encode($RPT_STS_factory_fix_report);
}



