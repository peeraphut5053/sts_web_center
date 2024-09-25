<?php
//============= initial module =====

//================== New Ver DASHBOARD ========
include "../initial.php";
$temp = new ReplaceHtml("../../template/RPT_UserLog/index.html");
//================== New Ver DASHBOARD ========
//
//
// //=============Get project name //
//$prj_code = GetProjectCode();
//$CM = new CallModel();
//$CM->SyteLine_Models();
//$Prj = new Project();
//$Prj->setConn($ConnWebApp) ;
//$Prj->GetProperties(" prj_code = '$prj_code' ") ; 
//$prjDesc= $Prj->prj_description ;
//$Prj = null ;
//===================INITIAL DATA FOR INDEX =========================



//$temp->setReplace("{prj_desc}", $prjDesc);
//$temp->setReplace("{prj_code}", $prj_code);

//================== New Ver DASHBOARD ========
echo $temp->getReplace();
sqlsrv_close($ConnSL);
//================== New Ver DASHBOARD ========



