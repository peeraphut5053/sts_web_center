<?php

class Document_control {

    var $StrConn = "";
    public $_prj_code = "";
    public $_doc_no = "";
    public $_user_id = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function InsertDocNo() {
        $cSql = new SqlSrv();
        $user_id = $_SESSION["login_user_id"];
        $sql = "INSERT INTO Document_control (prj_code,doc_no,date_time,user_id) "
                . "VALUES "
                . "('" . $this->_prj_code . "','" . $this->_doc_no . "',GETDATE(),$user_id) ";
        $rs = $cSql->IsUpDel($this->StrConn, $sql);
        return $rs;
    }

    Function GenDocNo() {
        $cSql = new SqlSrv();
        $user_id = $_SESSION["login_user_id"];
        $sql = "SELECT TOP 1 doc_no FROM Document_control WHERE prj_code = '" . $this->_prj_code . "' ORDER BY date_time DESC ";
        $RunningDigit = 7;
        $RunningInit = "0000001";
        $CurrDate = date("ymd");
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs, count($rs) - 1, 1);
        $tmpDocNo = "";
        $tmpDocNoCutPrefix = "";
        $tmpDocNoDate = "";
        $tmpDocNoCutDate = "";
        $val = "";

        if (count($rs) < 1) {
            return $CurrDate . $RunningInit;
        } else {
            $tmpDocNo = trim($rs[0]["trans_no"]);
            $tmpDocNoDate = substr($tmpDocNo, 0, 6);
            $tmpDocNoCutDate = substr($tmpDocNo, 6, 10);

            if ($CurrDate == $tmpDocNoDate) {
                $val = str_pad(strval($tmpDocNoCutDate) + 1, $RunningDigit, '0', STR_PAD_LEFT);
                return $CurrDate . "" . $val;
            } else {
                return $CurrDate . $RunningInit;
            }
        }
        $cSql = null;
    }

}
