<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";

$temp = new ReplaceHtml("../../template/STS_Tag_Trace_Lot/index.html");
$CallModel = new CallModel();
$CallModel->SyteLine_Models();

echo $temp->getReplace();
sqlsrv_close($ConnSL);
