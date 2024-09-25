<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/WL/excel_receive.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);


//$Dt = 'S'.date('y');

// $temp->setReplace("{Test}", $Dt);
