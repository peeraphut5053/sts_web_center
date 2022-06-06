<?php

class User {

    var $StrConn = "";
    public $_id = "";
    public $_username = "";
    public $_fullname = "";
    public $_password = "";
    public $_saleorder = "";
    public $_saleorder_approve = "";
    public $_boatnote = "";
    public $_boatplan = "";
    public $_weightlist = "";
    public $_dep = "";
    public $_dep_pos = "";
    public $_type = "";
    public $_custsomer_link = "";
    public $_addr1 = "";
    public $_addr2 = "";
    public $_tel = "";
    public $_mobile = "";
    public $_email = "";
    public $_remark = "";
    public $_sys_level = "";
    public $_prj_id = "";
    public $_state = "";
    public $_action = "";
    public $user_id = 0;
    public $id = 0;
    public $username = "";
    public $password = "";
    public $dep_position = "";
    public $dep_position_name = "";
    public $sys_level = "";
    public $ref_so_custnum = "";
    public $dep = "";
    public $dep_name = "";
    public $flag_cfr = "";
    public $state = "";
    public $last_login = "";
    public $pic = "";
    public $auth_weightlist = "0";
    public $auth_saleorder = "0";
    public $auth_saleorder_approve = "0";
    public $auth_boatnote = "0";
    public $auth_boatplan = "0";
    public $measure_desc = "";
    public $measure = "";
    public $type = "";
    public $fullname = "";
    public $addr1 = "";
    public $addr2 = "";
    public $tel = "";
    public $mobile = "";
    public $email = "";
    public $remark = "";
    public $debug = "";
    public $query = "";
    public $cust_id = "";
    public $online = "";
    public $time_active = "";
    public $cust_name = "";
    public $cust_num;
    public $cust_num_sl;
    public $measure_id;
    public $measure_name;
    public $measure_name2;
    public $_log_action;
    public $_menu;
    public $_chk_follow;
    public $follow_department;
    public $_quick_id;

    function setConn($c) {
        $this->StrConn = $c;
    }

    function AllUserActiveClear() {
        $gap = 10; // Gap value can be changed, this is in minutes. 
// let us find out the time before 10 minutes of present time. //
        $tm = date("Y-m-d H:i:s", mktime(date("H"), date("i") - $gap, date("s"), date("m"), date("d"), date("Y")));
        $query = "update STS_User set online='OFF' where time_active < '$tm'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $query);
        return $rs0;
    }

    function SetActiveCurentUser() {
        $tm = date("Y-m-d H:i:s");
//        $uid = 0 ; 

        $query = "update STS_User set online='ON',time_active='$tm' where id=" . $this->_id;
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $query);
        return $rs0;
    }

    function GetAuthorizeList($sWhere) {

        $query = "SELECT * FROM STS_User_auth UA "
                . "LEFT JOIN STS_User U  ON (UA.user_id = U.id )    "
                . "LEFT JOIN STS_Project PJ ON (UA.prj_id = PJ.prj_id) "
                . "$sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetRows($sWhere) {
        $query = "SELECT U.id as user_id ,ref_SO_CustNum ,username,fullname ,online,time_active ,DEP.dep_name  , DEPPOS.dep_pos_name ,Convert(nvarchar,last_login,120) as last_login FROM STS_User U "
                . "LEFT JOIN STS_Department DEP ON (U.dep = DEP.dep_id) "
                . "LEFT JOIN STS_Department_Position DEPPOS ON (U.dep_position = DEPPOS.dep_pos_id)  $sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetUserAll() {
        $query = "SELECT id , username , fullname , sys_level , isnull(state,0) as state  , dep ,b.dep_name, dep_position , c.dep_pos_name ,   last_login , CAST(last_login as varchar ) as last_login_conv  "
                . "FROM STS_User a "
                . "LEFT JOIN STS_Department b ON (a.dep = b.dep_id) "
                . "LEFT JOIN STS_Department_Position c ON (a.dep_position = c.dep_pos_id) "
                . "WHERE state=1";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }
    
    function GetAllProject() {
        $query = "SELECT * FROM STS_Project";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetLogUser() {
        $query = "SELECT Log_all.id,Log_all.date_time,STS_Project.prj_name,STS_Project.prj_description,Log_all.user_id,STS_user.username,"
                . "STS_user.fullname,Log_all.log_action "
                . "FROM Log_all LEFT JOIN STS_user ON Log_all.user_id = STS_user.id "
                . "LEFT JOIN STS_Project ON Log_all.prj_code = STS_Project.prj_id order by Log_all.id desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetItemToDropdown($sWhere) {
        $tmpKey = "";
        $tempValue = "";
        $returnArray = array();
        $query = "SELECT * ,Convert(nvarchar,last_login,120) as last_login FROM STS_User U LEFT JOIN STS_User_auth UA ON (u.id = UA.user_id)  $sWhere";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["id"];
            $tempValue = $rows["username"] . " - " . $rows["fullname"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function GetPropertiesUser($sWhere) {

        $query = "SELECT * FROM STS_User U "
                . "LEFT JOIN STS_Department DEP ON (U.dep = DEP.dep_id) "
                . "LEFT JOIN STS_Department_Position DEPPOS ON (U.dep_position = DEPPOS.dep_pos_id) "
                . "where u.id= $sWhere ";
        $cSql = new SqlSrv();
        $this->query = $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            $this->id = $rs0[1]["id"];
            $this->username = $rs0[1]["username"];
            $this->password = $rs0[1]["password"];
            $this->sys_level = $rs0[1]["sys_level"];
            $this->ref_so_custnum = $rs0[1]["ref_SO_CustNum"];
            $this->state = $rs0[1]["state"];
            $this->dep = $rs0[1]["dep"];
            $this->dep_name = $rs0[1]["dep_name"];
            $this->dep_position = $rs0[1]["dep_position"];
            $this->dep_position_name = $rs0[1]["dep_pos_name"];
            $this->pic = $rs0[1]["pic"];
            $this->last_login = $rs0[1]["last_login"];
            $this->type = $rs0[1]["type"];
            $this->fullname = $rs0[1]["fullname"];
            $this->addr2 = $rs0[1]["addr2"];
            $this->addr1 = $rs0[1]["addr1"];
            $this->tel = $rs0[1]["tel"];
            $this->mobile = $rs0[1]["mobile"];
            $this->email = $rs0[1]["email"];
            $this->remark = $rs0[1]["remark"];
            $this->follow_department = $rs0[1]["follow_department"];
        }
    }

    function GetUserData($id) {
        $query = "SELECT * FROM STS_User U "
                . "LEFT JOIN STS_Department DEP ON (U.dep = DEP.dep_id) "
                . "LEFT JOIN STS_Department_Position DEPPOS ON (U.dep_position = DEPPOS.dep_pos_id) "
                . "where u.id= $id ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) -1, 1);
        return $rs0;
    }

    function GetProperties($sWhere) {
        $query = "SELECT * ,CS.id as cust_id ,CS.name as cust_name FROM STS_User U "
                . "LEFT JOIN STS_User_auth UA ON (u.id = UA.user_id) "
                . "LEFT JOIN SO_Customer CS  ON (U.ref_SO_CustNum  = CS.cust_num)  "
                . "LEFT JOIN STS_MT_Measure MS ON (CS.measure = MS.id)   "
                . "LEFT JOIN STS_Department DEP ON (U.dep = DEP.dep_id) "
                . "LEFT JOIN STS_Department_Position DEPPOS ON (U.dep_position = DEPPOS.dep_pos_id) "
                . " $sWhere ";
        $cSql = new SqlSrv();
        $this->query = $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            $this->id = $rs0[1]["id"];
            $this->username = $rs0[1]["username"];
            $this->password = $rs0[1]["password"];
            $this->sys_level = $rs0[1]["sys_level"];
            $this->ref_so_custnum = $rs0[1]["ref_SO_CustNum"];
            $this->state = $rs0[1]["state"];
            $this->dep = $rs0[1]["dep"];
            $this->dep_name = $rs0[1]["dep_name"];
            $this->dep_position = $rs0[1]["dep_position"];
            $this->dep_position_name = $rs0[1]["dep_pos_name"];
            $this->cust_id = $rs0[1]["cust_id"];
            $this->cust_name = $rs0[1]["cust_name"];
            $this->pic = $rs0[1]["pic"];
            $this->last_login = $rs0[1]["last_login"];
            $this->measure = $rs0[1]["measure"];
            $this->measure_desc = $rs0[1]["measure_desc_en"];
            $this->type = $rs0[1]["type"];
            $this->fullname = $rs0[1]["fullname"];
            $this->addr2 = $rs0[1]["addr2"];
            $this->addr1 = $rs0[1]["addr1"];
            $this->tel = $rs0[1]["tel"];
            $this->mobile = $rs0[1]["mobile"];
            $this->email = $rs0[1]["email"];
            $this->remark = $rs0[1]["remark"];
        }
    }

    function GetlatestId() {
        $query = "SELECT TOP 1  id  FROM STS_User ORDER BY id DESC";
        $cSql = new SqlSrv();
        $this->query = $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            return $rs0[1]["id"];
        } else {
            return "";
        }
    }

    function GetCountProject() {
        $query = "select count(prj_id) as project_count FROM STS_Project";
        $cSql = new SqlSrv();
        $this->query = $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        if (count($rs0) > 1) {
            return $rs0[1]["project_count"];
        } else {
            return "";
        }
    }

    function Login() {
        $query = "";
        if (($this->_username == "guest") && ($this->_password == "")) {
            $query = "SELECT * FROM v_user_info  WHERE username = '" . $this->_username . "' AND ( password = ''  or password is null ) ";
        } else {
            $query = "SELECT * FROM v_user_info  WHERE username = '" . $this->_username . "' AND password = '" . md5($this->_password) . "'  ";
        }


        $cSql = new SqlSrv();
        $this->query = $query;
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        $result = 0;
        if (count($rs0) > 1) {
            $result = 1;
            $this->id = $rs0[1]["user_id"];
            $this->username = $rs0[1]["username"];
            $this->password = $rs0[1]["password"];
            $this->sys_level = $rs0[1]["sys_level"];
            $this->ref_so_custnum = $rs0[1]["ref_SO_CustNum"];
            $this->state = $rs0[1]["state"];
            $this->dep = $rs0[1]["dep"];
            $this->dep_position = $rs0[1]["dep_position"];
            $this->pic = $rs0[1]["pic"];
            $this->last_login = $rs0[1]["last_login"];
            $this->type = $rs0[1]["type"];
            $this->fullname = $rs0[1]["fullname"];
            $this->addr2 = $rs0[1]["addr2"];
            $this->addr1 = $rs0[1]["addr1"];
            $this->tel = $rs0[1]["tel"];
            $this->mobile = $rs0[1]["mobile"];
            $this->email = $rs0[1]["email"];
            $this->remark = $rs0[1]["remark"];
            $this->cust_num = $rs0[1]["cust_num"];
            $this->cust_num_sl = $rs0[1]["cust_num_sl"];
            $this->measure_id = $rs0[1]["measure"];
            $this->measure_name = $rs0[1]["ms_name"];
            $this->measure_desc = $rs0[1]["ms_desc"];
            $this->flag_duty = $rs0[1]["flag_duty"];
            $this->import_form = $rs0[1]["import_form"];
            $this->UpdateLastLogin();
            $this->LogUserLogin();
        }
        return $result;
    }

    function LogUserLogin() {
        $LogTime = date("Y-m-d H:i:s");
        $query = "INSERT INTO Log_All(date_time, prj_code, doc_no, user_id,log_action, remark,type) "
                . "VALUES('$LogTime','','','$this->id','Login','','') ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $query);
        return $rs0;
    }

    function LogUserLogout() {
        $LogTime = date("Y-m-d H:i:s");
        $query = "INSERT INTO Log_All(date_time, prj_code, doc_no, user_id,log_action, remark,type) "
                . "VALUES('$LogTime','','','$this->id','Logout','','') ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $query);
        return $rs0;
    }

    function LogUserMenu() {
        $LogTime = date("Y-m-d H:i:s");

        $query = "INSERT INTO Log_All(date_time, prj_code, doc_no, user_id,log_action, remark,type) "
                . "VALUES('$LogTime','$this->_menu','','$this->id','$this->_menu','','') ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $query);
        return $rs0;
    }

    function UpdateLastLogin() {
        $lastLog = date("Y-m-d H:i:s");
        $query = "UPDATE STS_User SET last_login = '" . $lastLog . "'  WHERE username = '" . $this->_username . "' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $query);

        return $rs0;
    }

    function UpdateAuthorize() {
        $prj_id = $this->_prj_id;
        $uid = $this->_id;
        $action = $this->_action;
        $query = "UPDATE STS_User_auth  SET action = $action WHERE prj_id = $prj_id AND user_id = $uid ";
        $cSql = new SqlSrv();
        return $cSql->IsUpDel($this->StrConn, $query);
    }

    function CheckAuthorize() {
        $id = $this->_id;
        $prj_id = $this->_prj_id;
        $query = "SELECT prj_id , user_id , action FROM STS_User_auth WHERE user_id = $id AND prj_id = $prj_id";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $return = 0;

        if ((!empty($rs0[0]) ) && ($rs0[0]["action"] == 1)) {
            $return = 1;
        }
        return $return;
    }

    function CheckQuickMenu() {
        $id = $this->_id;
        $prj_id = $this->_prj_id;
        $quick_id = $this->_quick_id;
        $query = "SELECT prj_id , user_id , quick_id, action FROM STS_Quick_Menu WHERE user_id = $id  AND prj_id = $prj_id AND quick_id = $quick_id ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $return = 0;

        if ((!empty($rs0[0]) ) && ($rs0[0]["action"] == 1)) {
            $return = $rs0[0]["action"];
        }
        return $return;
    }

    function ValidateAuthorizeById() {
        $uid = $this->_id;
        //==Count User Auth====//
        $query = "SELECT count(id) as CountRecs FROM STS_User_auth WHERE user_id =$uid ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        //===Count Auth ===//
        $query2 = "SELECT count(prj_id) as CountRecs FROM STS_Project ";
        $cSql2 = new SqlSrv();
        $rs2 = $cSql2->SqlQuery($this->StrConn, $query2);
        //==================//
        $numPrj = $rs2[1]["CountRecs"];
        $numAuth = $rs0[1]["CountRecs"];
        $result = 0;
        $numPrj == $numAuth ? $result = 1 : $result = 0;
        return $result;
    }

    function GetProjectListById2() {
        $id = $this->_id;
        $query = "SELECT * FROM V_Authorize WHERE user_id = $id order by prj_id asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        return $rs0;
    }

    function GetProjectList($user_id) {
        $id = $this->_id;
        $query = "SELECT a.prj_id , prj_name , b.dep_name,prj_type,prj_status,c.user_id,c.action FROM STS_Project a LEFT JOIN STS_Department b ON (a.dep_id = b.dep_id ) LEFT JOIN STS_User_auth c ON c.prj_id = a.prj_id WHERE a.prj_status = 1 and c.user_id ='$user_id' order by a.prj_id asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        return $rs0;
    }

    function ToggleAction() {
        $result = "";
        $tResult = 0;
        $UserId = $this->_id;
        $PrjId = $this->_prj_id;
        $CurrAction = $this->_action;
        $action = 0;
        $CurrAction == 1 ? $action = 0 : $action = 1;

        $query = "SELECT * FROM STS_User_Auth WHERE user_id = $UserId AND prj_id = $PrjId ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $TmpRec = 0;
        if (!empty($rs0[0])) {
            $TmpRec = 1;
        }
//        return  $TmpRec ;

        if ($TmpRec == 1) {
            $query = "UPDATE STS_User_Auth SET action = $action WHERE user_id = $UserId AND prj_id = $PrjId ";
            $cSql = new SqlSrv();
            $tResult = $cSql->IsUpDel($this->StrConn, $query);
            $tResult == 1 ? $result = "Update Successful " : $result = "Update Fail";
            $cSql = null;
        } else {
            $query = "INSERT INTO STS_User_Auth (user_id , prj_id , action )VALUES ($UserId,$PrjId,$action) ";
            $cSql = new SqlSrv();
            $tResult = $cSql->IsUpDel($this->StrConn, $query);
            $tResult == 1 ? $result = "Insert Successful " : $result = "Insert Fail";
            $cSql = null;
        }
        return $result;
    }

    function SetQuickMenuAction() {
        $result = "";
        $tResult = 0;
        $UserId = $this->_id;
        $PrjId = $this->_prj_id;
        $CurrAction = $this->_action;
        $quick_id = $this->_quick_id;
        $action = 0;
        $CurrAction == 1 ? $action = 0 : $action = 1;

        $queryGetPrjLink = "select prj_link,prj_description FROM STS_Project where prj_id = $PrjId";
        $cSql = new SqlSrv();
        $rsPrjLink = $cSql->SqlQuery($this->StrConn, $queryGetPrjLink);
        if (count($rsPrjLink) > 1) {
            $prj_link = $rsPrjLink[1]["prj_link"];
            $prj_description = $rsPrjLink[1]["prj_description"];
        } else {
            return "";
        }

        $query = "SELECT * FROM STS_Quick_Menu WHERE user_id = $UserId AND prj_id = $PrjId AND quick_id = $quick_id ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $TmpRec = 0;
        if (!empty($rs0[0])) {
            $TmpRec = 1;
        }
//        return  $TmpRec ;

        if ($TmpRec == 1) {

            $query = "UPDATE STS_Quick_Menu SET action = 0 WHERE action = 1 AND user_id = $UserId AND quick_id = $quick_id";
            $cSql = new SqlSrv();
            $tResult = $cSql->IsUpDel($this->StrConn, $query);

            $query = "UPDATE STS_Quick_Menu SET action = 1 WHERE user_id = $UserId AND prj_id = $PrjId AND quick_id = $quick_id";
            $cSql = new SqlSrv();
            $tResult = $cSql->IsUpDel($this->StrConn, $query);
            $tResult == 1 ? $result = "Update Successful1 $PrjId " : $result = "Update Fail";
            $cSql = null;
        } else {
            $query = "UPDATE STS_Quick_Menu SET action = 0 WHERE action = 1 AND user_id = $UserId AND quick_id = $quick_id";
            $cSql = new SqlSrv();
            $tResult = $cSql->IsUpDel($this->StrConn, $query);

            $query = "INSERT INTO STS_Quick_Menu (user_id , prj_id , quick_id ,action )VALUES ($UserId,$PrjId,$quick_id,1) ";
            $cSql = new SqlSrv();
            $tResult = $cSql->IsUpDel($this->StrConn, $query);
            $tResult == 1 ? $result = "Insert Successful " : $result = "Insert Fail";
            $cSql = null;
        }
        return $quick_id . "-" . $PrjId . "-" . $prj_link . "-" . $prj_description;
    }

    function GetProjectListById() {
        $uid = $this->_id;
        $query = "SELECT U.id as user_id , U.prj_id , prj_name , prj_description , prj_icon_fa , prj_link "
                . "FROM STS_User_auth U "
                . "LEFT JOIN STS_Project P  ON (U.prj_id = P.prj_id) "
                . "WHERE U.user_id = $uid  AND action = 1 ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $result = "";
        $tmpName = "";
        $tmpDesc = "";
        $tmpIcon = "";
        $tmpLink = "";
        foreach ($rs0 as $ii => $rr) {
            $tmpName = $rr["prj_name"];
            $tmpDesc = $rr["prj_description"];
            $tmpIcon = $rr["prj_icon_fa"];
            $tmpLink = $rr["prj_link"];
            $result = $result . "<li><a  href='$tmpLink' class='animate'><span class='pull-left $tmpIcon'></span>$tmpName</a></li>";
        }
        return $result;
    }

    function GetProjectListHomeById($type) {
        $uid = $this->_id;
        $query = "SELECT U.id as user_id , U.prj_id , prj_name ,prj_color, prj_description , prj_icon_fa , prj_link,prj_status "
                . "FROM STS_User_auth U "
                . "LEFT JOIN STS_Project P  ON (U.prj_id = P.prj_id) "
                . "WHERE U.user_id = $uid  AND action = 1 AND prj_status = 1 AND prj_type= '$type' ";

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $result = "";
        $tmpName = "";
        $tmpDesc = "";
        $tmpIcon = "";
        $tmpLink = "";
        foreach ($rs0 as $ii => $rr) {
            $tmpName = $rr["prj_name"];
            $tmpDesc = $rr["prj_description"];
            $tmpIcon = $rr["prj_icon_fa"];
            $tmpLink = $rr["prj_link"];
            $result = $result . " <div style='color:#FFFFFF;background-color:" . $rr["prj_color"] . ";' class='col-12 col-xs-12 col-sm-4 col-md-4 col-lg-4'>"
                    . "<a href='$tmpLink' class='btn btn-round-pro '>"
                    . "<i class='$tmpIcon fa-2x'></i>"
                    . "<h5 class='pro-name'>$tmpName</h5> "
                    . "<h6 class='pro-desc'>$tmpDesc</h6>"
                    . "</a>"
                    . "</div>";
//            $result = $result . "<div class='row row-pro-list'><a href='$tmpLink'>"
//                    . "<div class='col-4 col-xs-4 col-sm-4 col-md-4 col-lg-4'><i class='$tmpIcon fa-3x'></i></div> "
//                    . "<div class='col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8 text-right'>"
//                    . "<h4 class='pro-name'>$tmpName</h4>"
//                    . "<h5 class='pro-desc'>$tmpDesc</h5>"
//                    . "</div>"
//                    . "</a></div>";
        }
        return $result;
    }

    function RebuildAuthorizeById($val) {
        $result = "";
        $uid = $this->_id;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            $uid = $_SESSION["login_user_id"];
        }

        $cSql = new SqlSrv();
        $result_arr = array();
        $query2 = "SELECT * FROM STS_Project ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs0, count($rs0) - 1, 1);

        $q = "INSERT INTO STS_User_auth (user_id,prj_id,action) VALUES (?,?,?) ";
        foreach ($rs0 as $ii => $rr) {
            $prj_id = $rr["prj_id"];
            $query2 = "SELECT count(id) as CountResc FROM STS_User_auth WHERE user_id = $uid AND prj_id = $prj_id ";
            $cSql2 = new SqlSrv();
            $resultTmp = $cSql2->SqlQuery($this->StrConn, $query2);
            $hasAuth = $resultTmp[1]["CountResc"];
            $cSql2 = null;
            if ($hasAuth != 1) {
                $params = array($uid, $rr["prj_id"], $val);
                $stmt = sqlsrv_prepare($this->StrConn, $q, $params);
                sqlsrv_execute($stmt);
                $stmt = null;
                unset($params);
            }
            $cSql = null;
        }
        return $result;
    }

    function Insert() {
        $result = "";
        $result2 = "";
        $query = "INSERT INTO STS_User("
                . "type, username, password, fullname,"
                . "sys_level, ref_SO_CustNum,state, dep, dep_position,"
                . "addr1, addr2, tel,mobile, "
                . "email, remark"
                . ")VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $params = array(
            $this->_type,
            $this->_username,
            md5($this->_password),
            $this->_fullname,
            $this->_sys_level,
            $this->_custsomer_link,
            $this->_state,
            $this->_dep,
            $this->_dep_pos,
            $this->_addr1,
            $this->_addr2,
            $this->_tel,
            $this->_mobile,
            $this->_email,
            $this->_remark);

        $stmt = sqlsrv_prepare($this->StrConn, $query, $params);
        if (sqlsrv_execute($stmt) == false) {
            $result = sqlsrv_errors();
//            die(print_r(sqlsrv_errors(), true));
        } else {
            $result = "1";
        }
        $countProject = $this->GetCountProject();
        $latestId = $this->GetlatestId();

        if ($latestId) {
            for ($i = 1; $i < $countProject + 1; $i++) {
                $query2 = "INSERT INTO  STS_User_auth  (user_id,prj_id,action) VALUES (?,?,?)  ";
                $params2 = array(
                    $latestId,
                    $i,
                    1,
                );
                $stmt2 = sqlsrv_prepare($this->StrConn, $query2, $params2);
                if (sqlsrv_execute($stmt2) == false) {
                    $result2 = sqlsrv_errors() . $query2;
                } else {
                    $result2 = "1";
                }
            }
        }
        $arrResult = array($result, $result2);
        return $arrResult;
    }

    function Update() {
        $result = "";
        $pass = md5($this->_password);

        if ($this->_password == "") {
            $query = "UPDATE  STS_User  SET "
                    . "type ='$this->_type', "
                    . "username ='$this->_username', "
                    . "fullname ='$this->_fullname', "
                    . "sys_level ='$this->_sys_level', "
                    . "ref_SO_CustNum ='$this->_custsomer_link', "
                    . "state ='$this->_state',"
                    . "dep ='$this->_dep', "
                    . "dep_position ='$this->_dep_pos', "
                    . "addr1 ='$this->_addr1', "
                    . "addr2 ='$this->_addr2', "
                    . "tel ='$this->_tel', "
                    . "mobile ='$this->_mobile', "
                    . "email ='$this->_email', "
                    . "remark ='$this->_remark',  "
                    . "follow_department ='$this->_chk_follow'  "
                    . "WHERE id='$this->_id'  ";
        } else {
            $query = "UPDATE  STS_User  SET "
                    . "type ='$this->_type', "
                    . "username ='$this->_username', "
                    . "password ='$pass', "
                    . "fullname ='$this->_fullname', "
                    . "sys_level ='$this->_sys_level', "
                    . "ref_SO_CustNum ='$this->_custsomer_link', "
                    . "state ='$this->_state',"
                    . "dep ='$this->_dep', "
                    . "dep_position ='$this->_dep_pos', "
                    . "addr1 ='$this->_addr1', "
                    . "addr2 ='$this->_addr2', "
                    . "tel ='$this->_tel', "
                    . "mobile ='$this->_mobile', "
                    . "email ='$this->_email', "
                    . "remark ='$this->_remark',  "
                    . "follow_department ='$this->_chk_follow'  "
                    . "WHERE id='$this->_id'  ";
        }


        $cSql = new SqlSrv();
        $tResult = $cSql->IsUpDel($this->StrConn, $query);
        $tResult == 1 ? $result = "Update Successful " : $result = "Update Fail";
        $cSql = null;


        return $query;
    }

    function GetQuickMenu($id) {
        $query = "EXEC [SP_Qucik_menu] '$id'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);

        $query = "  select user_id,STS_Quick_Menu_tmp.prj_id,quick_id,action,prj_description,prj_link FROM  STS_Quick_Menu_tmp left join STS_Project on STS_Project.prj_id = STS_Quick_Menu_tmp.prj_id  where user_id='$id' order by quick_id";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        return $rs0;
    }

    function GetOptionToDropdownDep() {
        $sWhere = "";
        $query = "SELECT dep.sec_id as sec_id , u.id as user_id , * FROM STS_Department dep "
                . "LEFT JOIN STS_User u ON (dep.head_id = u.id) "
                . "LEFT JOIN STS_Section sec ON (dep.sec_id = sec.sec_id)  "
                . "$sWhere ORDER BY dep_id asc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

}
