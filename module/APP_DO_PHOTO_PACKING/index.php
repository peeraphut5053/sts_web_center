<?php
include "../initial.php";

$temp = new ReplaceHtml("../../template/APP_DO_PHOTO_PACKING/index.html");
echo $temp->getReplace();

sqlsrv_close($ConnSL);




