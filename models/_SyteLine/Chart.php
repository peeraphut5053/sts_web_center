<?php

class Chart {

    var $StrConn = "";
    // public $_StartDate = "";
    // public $_EndDate = "";
    // public $_Acct = array();

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function GetRows() {

        $cSql = new SqlSrv();
        $q = "SELECT  acct , description  FROM chart_mst  ";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }


}
