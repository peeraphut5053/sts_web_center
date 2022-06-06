<?php

include $var['path']['root'] . '/include/config.php';
include $var['path']['root'] . '/include/function.php';
include $var['path']['root'] . '/include/class.ReplaceHtml.php';
include $var['path']['root'] . '/include/sqlConn.php';
include $var['path']['root'] . '/include/class.SqlSvr.php';



class CallModel {

    function SHP_Models() {
        $folder = dirname(__DIR__) . './models/SHP/*.php';
        foreach (glob($folder) as $filename) {
            include $filename;
        }
    }

    function BIP_Models() {
        $folder = dirname(__DIR__) . './models/BIP/*.php';
        foreach (glob($folder) as $filename) {
            include $filename;
        }
    }

    function SOD_Models() {
        $folder = dirname(__DIR__) . './models/SOD/*.php';
        foreach (glob($folder) as $filename) {
            include $filename;
        }
    }

    function MGT_Models() {
        $folder = dirname(__DIR__) . './models/MGT/*.php';
        foreach (glob($folder) as $filename) {
            include $filename;
        }
    }

    function WebApp_Models() {
        $folder = dirname(__DIR__) . './models/_WebApp/*.php';
        foreach (glob($folder) as $filename) {
            include $filename;
        }
    }

    function MvBcTag_Models() {
        $folder = dirname(__DIR__) . './models/_MvBcTag/*.php';
        foreach (glob($folder) as $filename) {
            include $filename;
        }
    }

    function SyteLine_Models() {
        $folder = dirname(__DIR__) . './models/_SyteLine/*.php';
        foreach (glob($folder) as $filename) {
            include $filename;
        }
    }

//     function BIP_Models() {
//        $folder = dirname(__DIR__) . './models/BIP/*.php' ;
//        foreach (glob($folder) as $filename) {
//            include $filename;
//        }
//    }
}

//include $var['path']['root'].'../models/DepartmentPosition.php';
?>
