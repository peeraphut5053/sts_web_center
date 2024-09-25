
<?php


include "../initial.php";
$temp = new ReplaceHtml("../../template/Mill_Test_Certificate/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);