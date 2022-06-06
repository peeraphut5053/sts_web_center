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
$u_form = $_SESSION["login_user_import_form"];
$temp = new ReplaceHtml("../template/dialog/SOD/z_dialogItemSytelineSelect.html");

$CATE = "";
$GRADE = "";
$NPS = "";
$SCHED = "";
$SPEC = "";
$LEN = "";
$row = $_POST["row"];

isset($_POST["CATE"]) ? $CATE = $_POST["CATE"] : $CATE = "";
isset($_POST["GRADE"]) ? $GRADE = $_POST["GRADE"] : $GRADE = "";
isset($_POST["NPS"]) ? $NPS = $_POST["NPS"] : $NPS = "";
isset($_POST["SCHED"]) ? $SCHED = $_POST["SCHED"] : $SCHED = "";
isset($_POST["SPEC"]) ? $SPEC = $_POST["SPEC"] : $SPEC = "";
isset($_POST["LEN"]) ? $_POST["LEN"] : $LEN = "";
if ($GRADE == "") {
    $pGrade = "";
} else {
    $pGrade = " AND Uf_Grade = '$GRADE' ";
}
if ($NPS == "") {
    $pNPS = "";
} else {
    $pNPS = " AND Uf_Nps = '$NPS' ";
}
if ($SCHED == "") {
    $pSCHED = "";
} else {
    $pSCHED = " AND Uf_Schedule = '$SCHED' ";
}
if ($CATE == "") {
    $pCATE = "";
} else {
    $pCATE = " AND Uf_TypeEnd ='$CATE' ";
}
if ($SPEC == "") {
    $pSpec = "";
} else {
    $pSpec = " AND Uf_Spec = '$SPEC' ";
}
// not include search //
if ($LEN == "") {
    $pLEN = "";
} else {
    $pLEN = " AND Uf_lenght = '$LEN' ";
}
//================//
$str_query = "select item ,description as item_desc,Uf_pack ,unit_weight,Uf_TypeEnd ,Uf_Grade,Uf_thickness,Uf_od,Uf_Schedule,Uf_spec,Uf_NPS FROM item_mst  WHERE 1=1 ";
$query = array();
$query[1] = $str_query . " $pSpec  $pCATE $pSCHED  $pNPS ";
$query[2] = $str_query . " $pSpec  $pCATE $pSCHED   ";
$query[3] = $str_query . " $pSpec  $pCATE $pNPS";
$query[4] = $str_query . " $pSpec  $pCATE ";
$query[5] = $str_query . " $pSpec  $pSCHED $pNPS ";
$query[6] = $str_query . " $pSpec  $pNPS ";
$query[7] = $str_query . " $pSpec   ";
$query[8] = $str_query . " $pCATE $pSCHED $pNPS";
$query[9] = $str_query . " $pCATE $pSCHED ";
$query[10] = $str_query . " $pCATE $pNPS ";
$query[11] = $str_query . " $pCATE ";
$query[12] = $str_query . " $pSCHED ";
$query[13] = $str_query . " $pNPS ";

$result = array();
$flagNotFound = 0;
$result = array();
$resultCountRow = array();
for ($i = 1; $i <= count($query); $i++) {
    $tmpResult = null;
    $cSql = new SqlSrv();
    $tmpResult = $cSql->SqlQuery($ConnSL, $query[$i]);
    array_splice($tmpResult, count($tmpResult) - 1, 1);
    if (count($tmpResult) > 0) {
        break;
    }
    $cSql = null;
}
$line = "";
foreach ($tmpResult as $ii => $vv) {
    $line = $line . "<tr>"
            . "<td class='colSelect' align='center'>"
            . "<a style='color:blue;' data-row='$row' data-item='" . $vv['item'] . "' class='btn btn-default btn1' id='selectSItem_$ii' OnClick='selectItemFromDialog(this.id);'><i class='fa fa-check'></i>"
            . "</a></td>"
            . "<td>" . $vv['item'] . "</td>"
            . "<td>" . $vv['item_desc'] . "</td>"
            . "<td>" . $vv['Uf_spec'] . "</td>"
            . "<td>" . $vv['Uf_Grade'] . "</td>"
            . "<td>" . $vv['Uf_NPS'] . "</td>"
            . "<td>" . $vv['Uf_Schedule'] . "</td>"
            . "<td>" . $vv['unit_weight'] . "</td>"
            . "</tr>";
}

$temp->setReplace("{row}", $row);
$temp->setReplace("{SPEC}", $SPEC);
$temp->setReplace("{CATE}", $CATE);
$temp->setReplace("{LEN}", $LEN);
$temp->setReplace("{SCHED}", $SCHED);
$temp->setReplace("{NPS}", $NPS);
$temp->setReplace("{itemCount}", count($tmpResult));
$temp->setReplace("{itemList}", $line);
//     echo json_encode($tmpResult);
echo $temp->getReplace();



sqlsrv_close($ConnWebApp);
