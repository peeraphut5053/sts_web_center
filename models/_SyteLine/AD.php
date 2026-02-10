<?php

class AD {

    var $StrConn = "";
    public $_StartDate = "";
    public $_EndDate = "";
    public $_docs = array();

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function Report_PackingCost($por, $PackingCostItem) {
        $cSql = new SqlSrv();
        $q = "select * from STS_AD_PackingCost";
        if ($por != "") {
            $q .= " WHERE POR LIKE '%$por%'";
        }
        if ($PackingCostItem != "") {
            if ($por != "") {
                $q .= " AND item LIKE '%$PackingCostItem%'";
            } else {
                $q .= " WHERE item LIKE '%$PackingCostItem%'";
            }
        }
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function Update_PackingCost($item, $field, $value) {
        $cSql = new SqlSrv();
        $q = "UPDATE STS_AD_PackingCost SET $field='$value' WHERE item='$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        return $rs;
    }

    Function DeleteADpackingCost($item) {
        $cSql = new SqlSrv();
        $q = "DELETE FROM STS_AD_PackingCost WHERE item='$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        return $rs;
    }
    Function Report_AD_itemSIZEH($item) {
        $cSql = new SqlSrv();
        $q = "select * from STS_AD_itemSIZEH";
        if ($item != "") {
            $q .= " WHERE item LIKE '%$item%'";
        }
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function SaveADitemSIZEH($item, $inch, $mm, $sizeh) {
        $cSql = new SqlSrv();
        $q = "INSERT INTO STS_AD_itemSIZEH (item,inch,mm,sizeh) VALUES ('$item','$inch','$mm','$sizeh')";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        return $rs;
    }

    Function UpdateADitemSIZEH($item, $field, $value) {
        $cSql = new SqlSrv();
        $q = "UPDATE STS_AD_itemSIZEH SET $field='$value' WHERE item='$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        return $rs;
    }

    Function DeleteADitemSIZEH($item) {
        $cSql = new SqlSrv();
        $q = "DELETE FROM STS_AD_itemSIZEH WHERE item='$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        return $rs;
    }

    Function SaveADitemGradeSch($item, $spec, $gradeh, $schh) {
        $cSql = new SqlSrv();
        $q = "INSERT INTO STS_AD_itemGradeSch (item,spec,gradeh,schh) VALUES ('$item','$spec','$gradeh','$schh')";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        return $rs;
    }
    Function Report_AD_itemGradeSch($item, $spec) {
        $cSql = new SqlSrv();
        $q = "select * from STS_AD_itemGradeSch";
        if ($item != "") {
            $q .= " WHERE item LIKE '%$item%'";
        }
        if ($spec != "") {
            if ($item != "") {
                $q .= " AND spec LIKE '%$spec%'";
            } else {
                $q .= " WHERE spec LIKE '%$spec%'";
            }
        }
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function Insert_PackingCost($por,$item,$export,$domestic) {
        $cSql = new SqlSrv();
        $q = "INSERT INTO STS_AD_PackingCost (POR,item,export,domestic) VALUES ('$por','$item','$export','$domestic')";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        return $rs;
    }

    Function UpdateADitemGradeSch($item, $field, $value) {
        $cSql = new SqlSrv();
        $q = "UPDATE STS_AD_itemGradeSch SET $field='$value' WHERE item='$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        return $rs;
    }

    Function DeleteADitemGradeSch($item) {
        $cSql = new SqlSrv();
        $q = "DELETE FROM STS_AD_itemGradeSch WHERE item='$item'";
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        return $rs;
    }

}
