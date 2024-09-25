<?php

//===========initial file requirement=========//
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        // ถ้าเป็น array ให้วนลูปอีกครั้งเพื่อ trim แต่ละ element
        foreach ($value as $subKey => $subValue) {
            $$key[$subKey] = trim($subValue);
        }
    } else {
        $$key = trim($value);
    }
}
require_once '../../initial.php';



if ($form == "SetQuickMenu") {
    $tabledata = "";
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);

    $PrjList = $UserModel->GetProjectList($_SESSION["login_user_id"]);
    $tmpAction = 0;
    $action = "";
    $actionColor = "";
    $rowColor = "";
    $pType = "";

    foreach ($PrjList as $ii => $rr) {
        $UserModel->_prj_id = $rr["prj_id"];
        $UserModel->_id = $id;
        if ($method == "quickMenu") {
            $UserModel->_quick_id = $quick_id;
            $tmpAction = $UserModel->CheckQuickMenu();
            if ($tmpAction == 1) {
                $actionColor = "success";
            } else {
                $actionColor = "default";
            }
            $action = "<a id='Act!!$id!!" . $rr["prj_id"] . "!!" . $quick_id . "!!$tmpAction' "
                    . "class='btn btn-$actionColor'  "
                    . "OnClick='SetQuickMenuAction(this.id);'>"
                    . "<i  class='fa fa-check-circle'></i>"
                    . "</a>";
        }
        $rr["prj_type"] == "R" ? $pType = "Report" : $pType = "APP";
        $tabledata .= "<tr style='color:$rowColor;'> "
                . "<td align='left'>" . $rr["prj_id"] . "</td>"
                . "<td align='left'>" . $rr["prj_name"] . "</td>"
                . "<td align='left'>" . $rr["dep_name"] . "</td>"
                . "<td align='left'>$pType</td>"
                . "<td align='center'>$action</td>"
                . "</tr>";
    }
    $UserModel = null;
    $CallModel = null;
    $temp = new ReplaceHtml("../../template/DASHBOARD/SetQuickMenu.html");
    $temp->setReplace("{id}", $id);
    $temp->setReplace("{method}", $method);
    $temp->setReplace("{tabledata}", $tabledata);
    echo $temp->getReplace();
} else if ($form == "Update" || $form == "UserDetail") {
    $temp = new ReplaceHtml("../../template/USER_MNG/_From.html");
    $CallModel = new CallModel();
    $CallModel->WebApp_Models();
    $UserModel = new User();
    $UserModel->setConn($ConnWebApp);
    $UserModel->GetPropertiesUser($id);
    $temp->setReplace("{method}", $method);
    $temp->setReplace("{id}", $id);
    $temp->setReplace("{username}", $UserModel->username);
    $temp->setReplace("{password}", $UserModel->password);
    $temp->setReplace("{password2}", $UserModel->password);
    $temp->setReplace("{full_name}", $UserModel->fullname);
    $temp->setReplace("{addr1}", $UserModel->addr1);
    $temp->setReplace("{addr2}", $UserModel->addr2);
    $temp->setReplace("{tel}", $UserModel->tel);
    $temp->setReplace("{mobile}", $UserModel->mobile);
    $temp->setReplace("{email}", $UserModel->email);
    $temp->setReplace("{remark}", $UserModel->remark);
    $temp->setReplace("{dep}", $UserModel->dep);
    $check_state = trim($UserModel->follow_department);
    if ($check_state == 'true') {
        $temp->setReplace("{check_state}", "checked");
    } else {
        $temp->setReplace("{check_state}", "");
    }

    $UserModel = null;
    $CallModel = null;
    echo $temp->getReplace();
} else {
    $temp = new ReplaceHtml("../../template/USER_MNG/_From.html");
    echo $temp->getReplace();
}








