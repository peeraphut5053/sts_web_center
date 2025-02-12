<?php

include "../initial.php";
$temp = new ReplaceHtml("../../template/Delivery_Order_Criteria/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);

?>