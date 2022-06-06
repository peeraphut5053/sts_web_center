<?php

class LogUpload {

    var $StrConn = "";
    public $_msg = "";
    public $_sl_table = "";
    public $msg = "";
    public $sl_table = "";

    function setConn($c) {
        $this->StrConn = $c;
    }
    function Insert($msg,$sl_table) {
        $user_id = $_SESSION["login_user_id"];
        $date = date("Y-m-d");
        $dateTime = date("Y-m-d H:i:s");
        $cSql = new SqlSrv();
        $sql = "INSERT INTO log_upload "
                . "("
                . "user_id,"
                . "log_date,"
                . "msg,"
                . "sl_table) "
                . "VALUES "
                . "($user_id,"
                . "CONVERT(datetime,'$dateTime'),"
                . "'$msg',"
                . "'$sl_table' ) ";
        $result = $cSql->IsUpDel($this->StrConn, $sql);
        return $result;
    }
}
