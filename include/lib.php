<?php

include $var['path']['root'] . '/include/config.php';
include $var['path']['root'] . '/include/function.php';
include $var['path']['root'] . '/include/class.ReplaceHtml.php';
include $var['path']['root'].'/include/class.FunctionCenter.php';
include $var['path']['root'] . '/include/sqlConn.php';
include $var['path']['root'] . '/include/class.SqlSvr.php';

class CallModel {

    function MGT_Models() {
        $folder = dirname(__DIR__) . './models/MGT/*.php';
        foreach (glob($folder) as $filename) {
            require_once $filename;
        }
    }

    function WebApp_Models() {
        $folder = dirname(__DIR__) . './models/_WebApp/*.php';
        foreach (glob($folder) as $filename) {
            require_once $filename;
        }
    }

    function SyteLine_Models() {
        $folder = dirname(__DIR__) . './models/_SyteLine/*.php';
        foreach (glob($folder) as $filename) {
            require_once $filename;
        }
    }

}


//include $var['path']['root'].'../models/DepartmentPosition.php';
?>
