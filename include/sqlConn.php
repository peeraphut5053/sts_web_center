<?php

ini_set('display_errors', 1);
error_reporting(~0);

$host_pri = $var['mssql_webapp']['host'];
$user_pri = $var['mssql_webapp']['user'];
$pwd_pri = $var['mssql_webapp']['pass'];
$db_pri = $var['mssql_webapp']['db'];

$host_sec = $var['mssql_sl']['host'];
$user_sec = $var['mssql_sl']['user'];
$pwd_sec = $var['mssql_sl']['pass'];
$db_sec = $var['mssql_sl']['db'];

$strConnPri = array("Database" => $db_pri, "UID" => $user_pri, "PWD" => $pwd_pri, "MultipleActiveResultSets" => true, "CharacterSet" => 'utf-8');
$strConnSec = array("Database" => $db_sec, "UID" => $user_sec, "PWD" => $pwd_sec, "MultipleActiveResultSets" => true, "CharacterSet" => 'utf-8');
$ConnWebApp = sqlsrv_connect($host_pri, $strConnPri);
$ConnSL = sqlsrv_connect($host_sec, $strConnSec);

if (!$ConnWebApp) {
    echo "<pre> Primary Connection Fail  ";
    die(print_r(sqlsrv_errors(), true));
}
if (!$ConnSL) {
    echo "<pre> Secondary Connection Fail ";
    die(print_r(sqlsrv_errors(), true));
}
// print_r( sqlsrv_errors()) ;
?>
