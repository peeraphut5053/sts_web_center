<?php

//===================================================//
//===================================================//
//===================================================//
//=============== ItemSyteline.php      =============//
//===================================================//
//===================================================//
//===================================================//

class MaterialTransaction {

    var $StrConn = "";
    public $_site_ref = "";
    public $_StartDate = "";
    public $_EndDate = "";
    public $_Item = "";
    public $_TransType = "";
    public $_RefType = "";
    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetRowsWithCond() {     

     $CriteriaTransType ="" ;
     $CriteriaRefType ="" ;
     if($this->_TransType !=""){
         $CriteriaTransType =" AND (TransType ='".$this->_TransType."' ) " ;
     }
       if($this->_RefType !=""){
         $CriteriaRefType =" AND (RefType ='".$this->_RefType."' ) " ;
     }
        $query = "SELECT CONVERT(VARCHAR,TransDate,20) as TransDateConv , * "
                . "FROM V_WebApp_Matl "            
                . "Where item LIKE '%".$this->_Item."%'  "
                . "AND TransDate BETWEEN '".$this->_StartDate." 00:00:00' AND '".$this->_EndDate." 23:59:59'  "
                . " $CriteriaTransType   $CriteriaRefType "
                . " ORDER BY Trans_Num ASC";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
