<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

include "../initial.php";
$temp = new ReplaceHtml("../../template/WL/excel_receive.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);


//$Dt = 'S'.date('y');

// $temp->setReplace("{Test}", $Dt);
