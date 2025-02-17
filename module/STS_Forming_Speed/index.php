<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_Forming_Speed/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);