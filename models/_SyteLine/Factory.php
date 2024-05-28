<?php

class Factory {

    var $StrConn = "";
    public $_StartDate = "";
    public $_EndDate = "";

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

    Function RPT_General_ledger_report() {
        $StartDate = $this->_StartDate." 00:00:00" ;
        $EndDate = $this->_EndDate." 23:59:59" ;
        $cSql = new SqlSrv();
        $q = "SELECT 
        led.acct,
        chart.[description] AS acct_name,
        CONVERT(DATE, led.trans_date) AS trans_date,
        led.dom_amount,
        led.ref,
        led.vend_num,
        led.voucher
    FROM 
        ledger_mst led
    INNER JOIN 
        chart_mst chart ON chart.acct = led.acct
    WHERE 
        (led.ref LIKE '%IN%' OR led.ref LIKE '%CN%' OR led.ref LIKE '%DN%')
        AND CONVERT(DATE, led.trans_date) BETWEEN '$StartDate' AND '$EndDate'";
        $rs0 = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    
    
}


