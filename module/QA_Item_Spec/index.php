<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/QA_Item_Spec/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);