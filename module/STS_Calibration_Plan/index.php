<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/STS_Calibration_Plan/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);