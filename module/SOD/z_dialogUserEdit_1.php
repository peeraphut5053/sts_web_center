<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
session_start();
if (empty($_SESSION["login_username"])) {
    header("location: ?login");
}
//============== Render Page Normal ================//
include "./initial.php";

$u_name = $_SESSION["login_username"];

if ($edit_type == "N") {
    $temp = new ReplaceHtml("../template/dialog/SOD/z_dialogUserEdit.html");

    $User = new User();
    $User->setConn($ConnWebApp);
    $User->GetProperties(" WHERE  user_id = $user_id ");



    for ($j = 0; $j <= 5; $j++) {
        $SLV[$j] = "";
        $User->sys_level == $j ? $SLV[$j] = "selected" : $SLV[$j] = "";
    }
    $UT = array();
    $UT[0] = "";
    $UT["A"] = "";
    $UT["U"] = "";
    $UT["C"] = "";

    $User->type == "A" ? $UT["A"] = "selected" : $UT["A"] = "";
    $User->type == "U" ? $UT["U"] = "selected" : $UT["U"] = "";
    $User->type == "U" ? $UT["C"] = "selected" : $UT["C"] = "";
    !$User->type ? $UT[0] = "selected" : $UT[0] = "";

    $vlink = "";
    $User->vend_id ? $vlink = "<option selected value='" . $User->vend_id . "'>" . $User->vend_name . "</option>" : $vlink = "";
    $options_dep = "<option value='0'>..Select..</option>";
    $options_deppos = "<option value='0'>..Select..</option>";


    $Dep = new Department();
    $Dep->setConn($ConnWebApp);
    $Deps = $Dep->GetRows("");
    $thisDep = "";
    foreach ($Deps as $iDep => $rDep) {
        $User->dep == $rDep["dep_id"] ? $thisDep = "selected" : $thisDep = "";
        $options_dep = $options_dep . "<option $thisDep value='" . $rDep["dep_id"] . "'>" . $rDep["dep_name"] . "</option>";
    }

    $temp->setReplace("{user_id}", $user_id);
    $temp->setReplace("{old_username}", $User->username);
    $temp->setReplace("{UserName}", $User->username);
    $temp->setReplace("{FullName}", $User->fullname);
    $temp->setReplace("{Department}", $User->dep);
    $temp->setReplace("{DepartmentPosition}", $User->dep_position);
    $temp->setReplace("{SysLevel}", $User->sys_level);
    $temp->setReplace("{Address1}", $User->addr1);
    $temp->setReplace("{Address2}", $User->addr2);
    $temp->setReplace("{Tel}", $User->tel);
    $temp->setReplace("{Mobile}", $User->mobile);
    $temp->setReplace("{Email}", $User->email);
    $temp->setReplace("{Remark}", $User->remark);
    $temp->setReplace("{vendor_link}", $vlink);

    $temp->setReplace("{SLV0}", $SLV[0]);
    $temp->setReplace("{SLV1}", $SLV[1]);
    $temp->setReplace("{SLV2}", $SLV[2]);
    $temp->setReplace("{SLV3}", $SLV[3]);
    $temp->setReplace("{SLV4}", $SLV[4]);
    $temp->setReplace("{SLV5}", $SLV[5]);
    $temp->setReplace("{UT0}", $UT[0]);
    $temp->setReplace("{UTA}", $UT["A"]);
    $temp->setReplace("{UTU}", $UT["U"]);
    $temp->setReplace("{UTC}", $UT["C"]);
    $temp->setReplace("{options_dep}", $options_dep);
    $temp->setReplace("{options_deppos}", $options_deppos);
    echo $temp->getReplace();
} else {
    $Criteria = " WHERE  user_id = $user_id " ;
    $temp = new ReplaceHtml("../template/dialog/SOD/z_dialogUserEditAuth.html");
    $User = new User();
    $User->setConn($ConnWebApp);
    $User->GetProperties($Criteria);    
    $AuthList = $User->GetAuthorizeList($Criteria);
    $lines = "" ;
    $prjName = "" ;
    $selBox = "" ;
    $prjId = "" ;
    $optionYes = "" ;
    $optionNo = "" ;
    $prjIcon = "" ;
    foreach($AuthList as $ii => $rr){
        $prjIcon = $rr["prj_icon_fa"];
        $prjId=$rr["prj_id"] ;
        $prjName = $rr["prj_name"]." (".$rr["prj_description"].") <i class='fa $prjIcon'></i>" ;
        $rr["action"] == 0 ? $optionNo ="selected" : $optionNo = "" ;
        $rr["action"] == 1 ? $optionYes ="selected" : $optionYes = "" ;       
        
        $selBox ="<select  class='form-control' ref_id='$prjId' id='sel_$prjId'><option $optionYes value='1'>Yes</option><option $optionNo value='0'>No</option></select>" ;
        $lines=$lines."<div class='row'>"
                . "<div class='col-md-8 col-label text-right'>Project $prjName : </div>"
                . "<div class='col-md-4 col-label'>$selBox</div>"
                . "</div>" ;
    }
    $temp->setReplace("{authorize_zone}", $lines);
    $temp->setReplace("{user_id}", $user_id);
    $temp->setReplace("{UserName}", $User->username);

    echo $temp->getReplace();
}

//echo $temp->getReplace();



sqlsrv_close($ConnWebApp);
