<?php

foreach ($_GET as $key => $data) {
    ${$key} = trim($data);
}

foreach ($_POST as $key => $data) {
    ${$key} = trim($data);
}

include "../initial.php";

$temp = new ReplaceHtml("../../template/RPT_PO_TRACE/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);
