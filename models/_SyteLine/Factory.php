<?php

class Factory {

    var $StrConn = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function RPT_STS_factory_fix_report($TransactionDateStarting,$TransactionDateEnding,$unit1) {
        $cSql = new SqlSrv();

        $query = "exec STS_factory_fix_report @TransactionDateStarting = '$TransactionDateStarting' , @TransactionDateEnding= '$TransactionDateEnding' ,@unit1= '$unit1' ";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        echo $query;
        return $rs[0];
    }
	
}


