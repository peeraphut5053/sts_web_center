<?php

class LogOrder {

    var $StrConn = "";
    public $_po_num = "";
    public $_doc_no = "";
    public $_user_id = "";
    public $_date_time = "";
    public $_cust_num = "";
    public $_log_action = "";
    public $po_num = "";
    public $doc_no = "";
    public $user_id = "";
    public $date_time = "";
    public $cust_num = "";
    public $log_action = "";
    function setConn($c) {
        $this->StrConn = $c;
    }
    function Insert() {
        $user_id = $_SESSION["login_user_id"];
        $date = date("Y-m-d");
        $dateTime = date("Y-m-d H:i:s");
        $cSql = new SqlSrv();
        $sql = "INSERT INTO log_order "
                . "("
                . "user_id,"
                . "date_time,"
                . "cust_num,"
                . "po_num,"
                . "log_action,"
                . "doc_no ) "
                . "VALUES "
                . "($user_id,"
                . "CONVERT(datetime,'$dateTime'),"
                . "'" . $this->_cust_num . "',"
                . "'" .  $this->_po_num . "',"
                . "'Insert',"
                . "'" .  $this->_doc_no . "' ) ";
        $result = $cSql->IsUpDel($this->StrConn, $sql);
        return $result;
    }
}
