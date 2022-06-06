<?php

class ChartGraph {

    var $StrConn = "";

    // public $_StartDate = "";
    // public $_EndDate = "";
    // public $_Acct = array();

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function AllSellSummaryGarphLineChart() {
        $cSql = new SqlSrv();
        $q = "SELECT top 6 FORMAT(inv_date,'yyyy-MM') as y,sum(net_amt_2) as item1 FROM V_WebApp_ArTran "
                . "where inv_date between DATEADD(month, -6, GETDATE()) and DATEADD(month, 0, GETDATE()) "
                . "group by  FORMAT(inv_date,'yyyy-MM')";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetGraphList() {
        $cSql = new SqlSrv();
        $q = "SELECT * FROM STS_Graph";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function selectGarphGroup($garph_id) {
        $cSql = new SqlSrv();
        $q = "SELECT * FROM STS_Graph where id = $garph_id";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

}
