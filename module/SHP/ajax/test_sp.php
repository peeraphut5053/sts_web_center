<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";
$sql = "EXEC TestAddFromPHP  @Param1 = '$Param1'  ";
$stmt = sqlsrv_query($ConnWebApp, $sql);
 
if(!$stmt){
  echo  sqlsrv_errors() ;
}else{
    echo "Run SP Complete" ;
}

sqlsrv_close($ConnWebApp);