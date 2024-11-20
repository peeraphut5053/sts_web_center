<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/QC_Data_Analysis/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);

?>