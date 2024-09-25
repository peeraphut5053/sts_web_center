<?php

include "../initial.php";

$temp = new ReplaceHtml("../../template/APP_Qc_TestLab/upexcel_zinc.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);