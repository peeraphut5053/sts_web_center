<?php

class ArTran {

    var $StrConn = "";
    public $_StartDate = "";
    public $_EndDate = "";
    public $_docs = array();

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function GetRows() {
        $docs = $this->_docs;
        $StartDate = $this->_StartDate." 00:00:00" ;
        $EndDate = $this->_EndDate." 23:59:59" ;
        $CriteriaDocs = "" ;
        if(count($docs)==0){
            $CriteriaDocs =" AND inv_num LIKE '".$docs[0]."%' " ;
        }else{
            $CriteriaDocs.=" AND (" ;
            foreach($docs as $ii=>$rr){
                $CriteriaDocs.=" inv_num LIKE '$rr%' OR ";
            }
            $CriteriaDocs=substr($CriteriaDocs, 0, -3);
            $CriteriaDocs.=" )";
        }
        $cSql = new SqlSrv();
        $q = "SELECT * FROM V_WebApp_ArTran  WHERE 1=1 AND type <> 'P' "
                . "AND (inv_date BETWEEN '$StartDate' AND '$EndDate' )   $CriteriaDocs";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetDocType() {
        $cSql = new SqlSrv();
        $q = "select substring(inv_num,1,2) as doc FROM V_WebApp_ArTran GROUP BY substring(inv_num,1,2)";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

}
