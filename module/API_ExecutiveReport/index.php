

<?php



include "../initial.php";
$temp = new ReplaceHtml("../../template/API_ExecutiveReport/index.html");

echo $temp->getReplace();
sqlsrv_close($ConnSL);


