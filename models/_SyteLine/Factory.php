<?php

class Factory {

    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function RPT_STS_factory_fix_report($TransactionDateStarting,$TransactionDateEnding,$unit1) {
        $cSql = new SqlSrv();

        $query = "exec STS_factory_fix_report @TransactionDateStarting = '$TransactionDateStarting' , @TransactionDateEnding= '$TransactionDateEnding' ,@unit1= '$unit1' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function RPT_STS_factory_fix_report_sub($ref) {
        $cSql = new SqlSrv();

        $query = "exec STS_factory_fix_report_sub @ref = '$ref' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
}


