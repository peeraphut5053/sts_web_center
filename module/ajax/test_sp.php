<?php
foreach ($_GET as $key => $value) {
  $$key = trim($value);
}

foreach ($_POST as $key => $value) {
  $$key = trim($value);
}
include "./initial.php";
$sql = "EXEC TestAddFromPHP  @Param1 = '$Param1'  ";
$stmt = sqlsrv_query($conn, $sql);
 
if(!$stmt){
  echo  sqlsrv_errors() ;
}else{
    echo "Run SP Complete" ;
}

sqlsrv_close($conn);