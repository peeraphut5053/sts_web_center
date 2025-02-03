<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/Delivery_Order_Lines/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);

?>