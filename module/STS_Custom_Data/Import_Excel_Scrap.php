<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../initial.php";

$temp = new ReplaceHtml("../../template/STS_Custom_Data/Import_Excel_Scrap.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);