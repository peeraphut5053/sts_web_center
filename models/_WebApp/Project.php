<?php

class Project {

    var $StrConn = "";
    public $_prj_id = "";
    public $_dep_id = "";
    public $_prj_name = "";
    public $_prj_code = "";
    public $_prj_type = "";
    public $_prj_description = "";
    public $_prj_icon_fa = "";
    public $_prj_link = "";
    public $_prj_status = "";
    public $_prj_color = "";
    public $prj_id = "";
    public $prj_name = "";
    public $prj_code = "";
    public $prj_description = "";
    public $prj_icon_fa = "";
    public $dep_id = "";
    public $prj_link = "";
    public $prj_status = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GetRowsAll() {
        $query = "SELECT * FROM STS_Project ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetAPP_All() {
        $query = "SELECT DISTINCT * FROM STS_Project WHERE prj_status=1  AND dep_id=" . $this->_dep_id . " ORDER BY prj_id";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

//    function GetAPP() {
//        $query = "SELECT * FROM STS_Project WHERE prj_status=1 AND  prj_type='" . $this->_prj_type . "'  AND dep_id=" . $this->_dep_id . " ORDER BY prj_id";
//        $cSql = new SqlSrv();
//        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
//        array_splice($rs0, count($rs0) - 1, 1);
//        return $rs0;
//    }

    function GetAPP() {
        $query = "SELECT DISTINCT * FROM STS_Project JOIN STS_User_auth  ON STS_Project.prj_id = STS_User_auth.prj_id  WHERE STS_Project.prj_status=1  AND STS_Project.dep_id=" . $this->_dep_id . " AND STS_User_auth.user_id = " . $_SESSION["login_user_id"] . " AND action = 1  ORDER BY STS_Project.prj_id";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        return $rs0;
    }

    function GetAPPFollowDepartment() {
        $query = "SELECT * FROM STS_Project LEFT JOIN STS_User ON STS_Project.dep_id = STS_User.dep WHERE prj_status=1 and  STS_User.dep=" . $_SESSION["dep"] . " and STS_USER.id = " . $_SESSION["login_user_id"] . " ORDER BY STS_Project.prj_id";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetProperties($sWhere) {
        $query = "SELECT DISTINCT * FROM STS_Project a "
                . "LEFT JOIN STS_Department b ON (a.dep_id=b.dep_id) "
                . "WHERE 1=1 AND  $sWhere ";
        $cSql = new SqlSrv();
        $this->query = $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            $this->prj_id = $rs0[1]["prj_id"];
            $this->prj_name = $rs0[1]["prj_name"];
            $this->prj_code = $rs0[1]["prj_code"];
            $this->prj_link = $rs0[1]["prj_link"];
            $this->prj_status = $rs0[1]["prj_status"];
            $this->prj_description = $rs0[1]["prj_description"];
            $this->prj_icon_fa = $rs0[1]["prj_icon_fa"];
            $this->dep_id = $rs0[1]["dep_id"];
        }
    }

    function SaveMenu() {
        $latestId = $this->GetlatestId();
        $result = "";
        $result2 = "";
        $query = "INSERT INTO STS_Project("
                . "prj_id, prj_code, prj_name, prj_description,"
                . "prj_icon_fa, prj_link,prj_status, prj_type, prj_color,"
                . "dep_id"
                . ")VALUES(?,?,?,?,?,?,?,?,?,?) ";
        $params = array(
            $latestId + 1,
            $this->_prj_code,
            $this->_prj_name,
            $this->_prj_description,
            $this->_prj_icon_fa,
            $this->_prj_link,
            $this->_prj_status,
            $this->_prj_type,
            $this->_prj_color,
            $this->_dep_id
        );
        $stmt = sqlsrv_prepare($this->StrConn, $query, $params);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
//            die(print_r(sqlsrv_errors(), true));
        } else {
            $result = "1";
        }
        $countProject = $this->GetCountUser();
        $user_id = $this->GetAllUserId();
        if ($latestId) {
            $i = 1;

            do {
                $query2 = "INSERT INTO  STS_User_auth  (user_id,prj_id,action) VALUES (?,?,?)  ";
                $params2 = array(
                    $user_id[$i]["id"],
                    $latestId + 1,
                    1,
                );
                $stmt2 = sqlsrv_prepare($this->StrConn, $query2, $params2);
                if (sqlsrv_execute($stmt2) == false) {
                    $result2 = sqlsrv_errors() . $query2;
                } else {
                    $result2 = "1";
                }
                $i++;
            }while ($i < $countProject+1);
        }
        $arrResult = array($result, $result2);
        return $user_id[1]["id"];
    }

    function GetlatestId() {
        $query = "SELECT TOP 1  prj_id  FROM STS_Project ORDER BY prj_id DESC";
        $cSql = new SqlSrv();
        $this->query = $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            return $rs0[1]["prj_id"];
        } else {
            return "";
        }
    }

    function GetCountUser() {
        $query = "select count(id) as user_count FROM STS_User";
        $cSql = new SqlSrv();
        $this->query = $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            return $rs0[1]["user_count"];
        } else {
            return "";
        }
    }

    function GetAllUserId() {
        $query = "select id FROM STS_User";
        $cSql = new SqlSrv();
        $this->query = $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        return $rs0;
    }

}

?>
