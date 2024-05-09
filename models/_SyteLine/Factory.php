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

    Function RPT_STS_factory_fix_report_sub($ref,$acct_unit1) {
        $cSql = new SqlSrv();

        $query = "exec STS_factory_fix_report_sub @ref = '$ref', @unit1 = '$acct_unit1' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function Getunit1List() {
        $cSql = new SqlSrv();

        $query = "select distinct led.acct_unit1, unit1.[description] as unit1_description from ledger_mst led left join unitcd1_mst unit1 on led.acct_unit1=unit1.unit1 where led.ref like 'AP%' and led.acct like '5315%' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    
    
}


