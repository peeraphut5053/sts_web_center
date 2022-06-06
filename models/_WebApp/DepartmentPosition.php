<?php

class DepartmentPosition {
    var $StrConn = "";
    public $_dep_id = "";
    public $_dep_pos_id = "";
    public $_dep_pos_name = "";
    public $_dep_pos_level = 0;
    public $dep_pos_id = 0;
    public $dep_pos_name = "";
    public $dep_pos_level = "";
    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetRows($sWhere) {
        $query = "SELECT deppos.dep_id,dep_pos_id,dep_pos_name , dep_pos_level , dep_name  FROM STS_Department_Position deppos "
                . "LEFT JOIN STS_Department dep  ON (deppos.dep_id = dep.dep_id) "
                . "$sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemToDropdown($sWhere) {
        $tmpKey = "";
        $tempValue = "";
        $returnArray = array();
        $query = "SELECT * FROM STS_Department_Position deppos "
                . "LEFT JOIN STS_Department dep  ON (deppos.dep_id = dep.dep_id) "
                . "$sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["dep_pos_id"];
            $tempValue = $rows["dep_pos_name"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function Insert() {
        session_start();
        $created_date = date("Y-m-d H:i:s");
        $created_by = $_SESSION["login_user_id"];
        $query = "INSERT INTO STS_Department_Position (dep_id,dep_pos_name,dep_pos_level,created_date,created_by) VALUES("
                . "" . $this->_dep_id . ",'" . $this->_dep_pos_name . "'," . $this->_dep_pos_level . ",'$created_date',$created_by ) ";
        $cSql = new SqlSrv();
        return $rs0 = $cSql->IsUpDel($this->StrConn, $query);
    }

    function GetProperties($sWhere) {
        $query = "SELECT * FROM STS_Department_Position deppos "
                . "LEFT JOIN STS_Department dep  ON (deppos.dep_id = dep.dep_id) "
                . "$sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            $this->dep_id = $rs0[1]["dep_id"];
            $this->dep_pos_name = $rs0[1]["dep_pos_name"];
            $this->dep_pos_level = $rs0[1]["dep_pos_level"];
        }
    }

}
