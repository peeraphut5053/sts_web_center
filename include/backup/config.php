<?php

ini_set('memory_limit', '1024M'); //3024
//=====//\\//\/\/\\\\==SET CONN 0 = inside 1 = outside   ===\\//\/\/\/\/\\/\/\/\
$SetConnectionTo =0;
$DbSet =0; //0= live 1=Train15_AppDemo
//=====//\\//\/\/\\\\==SET CONN ===\\//\/\/\/\/\\/\/\/\
//Out IP
$OutSideMySql = "61.90.156.165:33006";
$outSideHost = "61.90.156.167,8998";
//In IP
$InSideMySql = "172.18.1.193";
$inSideHost = "sts-sldb";
//=== Sql server 
$SqlServerUser = "sa";
$SqlServerPass = "Sts@2017";
$Db = "";
if ($DbSet == 0) {
    $Db = "Live_App";
} else {
    $Db = "Live_App_Test2";
}
$SqlServerDB_SL = $Db;
$SqlServerDB_Tag = $Db;
$SqlServerDB_WebApp = "STS_WebApp";
//'==MySql'
$MySqlUser = "root3";
$MySqlPass = "1234";
$MySqlDB = "milltest";
//=== Set IN-OUT Server 

if ($SetConnectionTo == 0) {
    $setHostSqlServer = $inSideHost;
    $setHostMySql = $InSideMySql;
} else {
    $setHostSqlServer = $outSideHost;
    $setHostMySql = $OutSideMySql;
}
//================================================//


$var['mssql_sl']['host'] = $setHostSqlServer;
$var['mssql_sl']['user'] = $SqlServerUser;
$var['mssql_sl']['pass'] = $SqlServerPass;
$var['mssql_sl']['db_sl'] = $SqlServerDB_SL;
$var['mssql_sl']['db'] = $SqlServerDB_SL;
$var['mssql_sl']['db_tag'] = $SqlServerDB_SL;


$var['mssql_webapp']['host'] = $setHostSqlServer;
$var['mssql_webapp']['user'] = $SqlServerUser;
$var['mssql_webapp']['pass'] = $SqlServerPass;
$var['mssql_webapp']['db'] = $SqlServerDB_WebApp;

$var['mysql']['host'] = $setHostMySql;
$var['mysql']['user'] = $MySqlUser;
$var['mysql']['pass'] = $MySqlPass;
$var['mysql']['db'] = $MySqlDB;



date_default_timezone_set("Asia/Bangkok");
$tolerances_weight = 10 //����� %
?>
