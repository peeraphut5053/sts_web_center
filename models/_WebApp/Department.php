<?php

class Department {

    var $StrConn = "";
    public $_dep_id = "";
    public $_dep_name = "";
    public $_head_id = 0;
    public $_sec_id = 0;
    public $_dep_tel = "";
    public $_dep_fax = "";
    public $_dep_email = "";
    public $_dep_icon = "";
    public $dep_id = "";
    public $dep_name = "";
    public $head_id = "";
    public $head_name = "";
    public $dep_tel = "";
    public $dep_fax = "";
    public $dep_email = "";
    public $sec_name = "";
    public $dep_icon = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetRows($sWhere) {
        $query = "SELECT dep.sec_id as sec_id , u.id as user_id , * FROM STS_Department dep "
                . "LEFT JOIN STS_User u ON (dep.head_id = u.id) "
                . "LEFT JOIN STS_Section sec ON (dep.sec_id = sec.sec_id)  "
                . "$sWhere ORDER BY dep_id asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function DropdownSelectAllDepartment($sWhere) {
        $query = "SELECT dep_id,dep_name FROM STS_Department";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRowsFollowDepartment($sWhere) {
        $query = "SELECT dep.sec_id as sec_id , u.id as user_id , * FROM STS_Department dep "
                . "LEFT JOIN STS_User u ON (dep.head_id = u.id) "
                . "LEFT JOIN STS_Section sec ON (dep.sec_id = sec.sec_id)  "
                . "$sWhere ORDER BY dep_id asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemToDropdown($sWhere) {
        $tmpKey = "";
        $tempValue = "";
        $returnArray = array();
        $query = "SELECT * FROM STS_Department $sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        foreach ($rs0 as $index => $rows) {

            $tmpKey = $rows["dep_id"];
            $tempValue = $rows["dep_name"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function Insert() {
        session_start();
        $created_date = date("Y-m-d H:i:s");
        $created_by = $_SESSION["login_user_id"];
        $query = "INSERT INTO STS_Department ("
                . "dep_name,head_id,sec_id,dep_tel,"
                . "dep_fax,dep_email,created_date,created_by) VALUES("
                . "'" . $this->_dep_name . "'," . $this->_head_id . "," . $this->_sec_id . ",'" . $this->_dep_tel . "',"
                . "'" . $this->_dep_email . "','" . $this->_dep_fax . "','$created_date',$created_by)";
        $cSql = new SqlSrv();
        return $rs0 = $cSql->IsUpDel($this->StrConn, $query);
    }

    function GetProperties($sWhere) {
        $query = "SELECT * FROM STS_Department dp "
                . "LEFT JOIN STS_Section st ON (dp.sec_id = st.sec_id)  "
                . "LEFT JOIN STS_User us ON (dp.head_id = us.id)  $sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            $this->dep_id = $rs0[1]["dep_id"];
            $this->dep_name = $rs0[1]["dep_name"];
            $this->sec_name = $rs0[1]["sec_name"];
            $this->head_name = $rs0[1]["fullname"];
            $this->head_id = $rs0[1]["head_id"];
            $this->dep_tel = $rs0[1]["dep_tel"];
            $this->dep_fax = $rs0[1]["dep_fax"];
            $this->dep_email = $rs0[1]["dep_email"];
        }
    }

}
