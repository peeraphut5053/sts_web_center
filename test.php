<?php

session_start();

$host = $_SERVER['HTTP_HOST'];
$root = $_SERVER['DOCUMENT_ROOT'];
$self = $_SERVER['PHP_SELF'];


echo "HOST :" . $host;
echo "<br />Root :" . $root;
echo "<br />Self: " . $self;
$self_a = explode("/",$self);

echo "<br /> Self A: ";
print_r($self_a);
echo "<br /> Self FD: ";
print_r(explode(".",$self_a[1]));

$self_fd = (explode(".",$self_a[1]));
$path =$self_fd[0]  ;

echo "<br />Path: " . $path;
/*
$host = $_SERVER['HTTP_HOST'];
$root = $_SERVER['DOCUMENT_ROOT'];
$self = $_SERVER['PHP_SELF'];
$self_a = explode("/",$self);
$path="";
$self_fd = explode(".",$self_a[1]);
  $path =$self_fd[0]  ;
 
$var['path']['root'] = $root."/".$path;
//echo "initial validate : " . $var['path']['root'];
$var['url']['root'] = ((isset($host) && $host=='on') ? 'https://' : 'http://').$host.'/'.$path;
$var['url']['filename'] = basename($self);

include($var['path']['root']."/include/lib.php");	
*/
?>