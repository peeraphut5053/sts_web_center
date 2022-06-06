<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//
include "./initial.php";
$temp = new ReplaceHtml("../template/MGT/_dialog_vendor.html");
// $temp->setReplace("{IdRun}",$SetIdRun);
// $temp->setReplace("{MV}",$SetMV);
// $temp->setReplace("{LN}",$SetLN);
echo $temp->getReplace();
sqlsrv_close($conn);

 ?>
