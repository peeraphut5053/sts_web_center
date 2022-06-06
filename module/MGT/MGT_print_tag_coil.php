<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

//echo "<pre>";
//print_r($_GET);
//print_r($_POST);
//exit();
$get_ip_original = $_SERVER['REMOTE_ADDR'];
$useData = "qc";
$grn_nums = $_POST["grn_nums"];
$thickss = $_POST["thickss"];
$po_nums = $_POST["po_nums"];
$po_lines = $_POST["po_lines"];
$lots = $_POST["lots"];
$coil_nos = $_POST["coil_nos"];
$uf_manus = $_POST["uf_manu"];
$widths = $_POST["widths"];
$sts_no = $_POST["sts_no"];
$grades = $_POST["grades"];
$snos = $_POST["snos"];
$realweights = $_POST["realweights"];
$realwidths = $_POST["realwidths"];
$realthicks = $_POST["realthicks"];
$printType = $_POST["printType"];
include "./initial.php";

$temp = new ReplaceHtml("../template/print_tag_coil.html");
$cSql = new SqlSrv();

if ($printType == 0) { //==== new print 
    if (isset($_POST["coil_ids"])) {
        $ci_n = count($_POST["coil_ids"]);
        $g = array();
        for ($t = 0; $t < $ci_n; $t++) {
            $temp->setReplace("{printlist}", $temp->getTemplate("../template/tag_coil_block.html"));

            $id = $_POST["coil_ids"][$t];
            $sql = "select *, CONVERT(VARCHAR(16), mfg_date, 120)  AS mfg_date from Mv_Bc_tag where id = '" . $id . "';";
            $rs = $cSql->SqlQuery($conn, $sql);

            $temp->setReplace("{barcode}", "" . $id . "");
            $temp->setReplace("{Uf_Grade}", "" . $rs[1]["uf_spec"] . "");
            $temp->setReplace("{qty_rec}", "" . total_format($rs[1]["qty1"]) . "");
            $temp->setReplace("{Uf_coil_no}", "" . $rs[1]["uf_coil_no"] . "");
            $temp->setReplace("{mdate}", "" . dateformat($rs[1]["mfg_date"], "d/m/Y") . "");
            $temp->setReplace("{uf_Width}", "" . $rs[1]["uf_Tickness"] . " x " . total_format($rs[1]["uf_width"], 2) . "");
            $temp->setReplace("{name}", "" . $rs[1]["uf_name"] . "");
            $temp->setReplace("{lot}", "" . $rs[1]["lot"] . "");
            $temp->setReplace("{item}", "" . $rs[1]["item"] . "");
            $temp->setReplace("{po_num}", "" . $rs[1]["po_num"] . "");
            $temp->setReplace("{sno}", "" . $snos[$t] . "");
            $temp->setReplace("{grndata}", json_encode($g));
            $temp->setReplace("{ids}", json_encode($id));
        }
    } else {

        $ip = $_SERVER['REMOTE_ADDR']; //Get user IP
        
        if ($ip == "::1") {
            $digits = 3;
            $ipc = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $idl = date("ymd") . $ipc;
        } else {
            $ip_a = explode(".", $ip);
            $ipc = sprintf("%'.03d", $ip_a[3]);
            $idl = date("ymd") . $ipc;
        }

        $sql = "select TOP 1 id from Mv_Bc_tag where id like '" . $idl . "%' order by id desc;";
        $rs = $cSql->SqlQuery($conn_sl, $sql);
        if (isset($rs[1]["id"])) {
            $id_last = substr($rs[1]["id"], -4);
            $id_next = intval('' . $id_last . '');
        } else {
            $id_next = 0;
        }


        //==
        $s_tag = count($po_nums);
        $id_a = array();
        $POI = new SLPOI();
        $POI->setConn($conn_sl);
        $tag_ids = array();
        $val = "";
        $tmpWidths = 0;
        $tmpGrnDate = "";
        for ($t = 0; $t < $s_tag; $t++) {
            $temp->setReplace("{printlist}", $temp->getTemplate("../template/tag_coil_block.html"));
            $id_next += 1;
            $id = $idl . sprintf("%'.04d", $id_next);
            $ids[$t] = $id;
            $POI->_grn_num = $grn_nums[$t];
            $POI->_po_num = $po_nums[$t];
            $POI->_po_line = $po_lines[$t];
            $POI->_lot = $lots[$t];
            $POI->GetPOData();
            $tempVendName = str_replace("'", "_", $POI->vend_name);
            $sl_grade = $POI->uf_grade;
            $sl_width = $POI->uf_width;
            $qc_grade = $grades[$t];
            $qc_width = $widths[$t];
            $tGrade = "";
            $tWidth = "";
            $tRealweights = $realweights[$t];
            $tRealwidths = $realwidths[$t];
            $tRealthicks = $realthicks[$t];
            
            if ($useData == "sl") {
                $tGrade = $sl_grade;
                $tWidth = $sl_width;
            } else {
                $tGrade = $qc_grade;
                $tWidth = $qc_width;
            }
            $POI->grn_hdr_date ? $tmpGrnDate = $POI->grn_hdr_date->format('d/m/Y') : $tmpGrnDate = "";
            $temp->setReplace("{barcode}", "" . $id . "");
            $temp->setReplace("{Uf_Grade}", "" . $tGrade . "");
            $temp->setReplace("{qty_rec}", "" . total_format($POI->qty_shipped_conv) . "");
            $temp->setReplace("{Uf_coil_no}", "" . $coil_nos[$t] . "");
            $temp->setReplace("{mdate}", $tmpGrnDate);
            $temp->setReplace("{uf_Width}", "" . (isset($thickss[$t]) ? $thickss[$t] : "") . " x " . ($tWidth ? number_format($tWidth, 2) : "0.00" ) . "");
            $temp->setReplace("{name}", "" . $tempVendName . "");
            $temp->setReplace("{lot}", "" . $lots[$t] . "");
            $temp->setReplace("{item}", "" . $POI->item . "");
            $temp->setReplace("{po_num}", "" . $po_nums[$t] . "");
            $temp->setReplace("{sno}", "" . $snos[$t] . "");

            $val = $grn_nums[$t] . "!!" . $po_nums[$t] . "!!" . $lots[$t] . "!!" . $POI->item
                    . "!!" . $POI->qty . "!!" . $POI->u_m . "!!$tGrade!!" . $POI->grn_line . "!!" . $uf_manus[$t] . "" . "!!" . $tempVendName
                    . "!!" . $coil_nos[$t] . "!!" . $tmpGrnDate . "!!" . $thickss[$t] . "!!$tWidth!!" .
                    $POI->qty_shipped_conv . "!!" . $snos[$t] . "!!" . $tRealweights . "!!" . $tRealwidths . "!!" . $tRealthicks;
            array_push($tag_ids, $val);
        }
        $temp->setReplace("{print_type}", $printType);
        $temp->setReplace("{grndata}", json_encode($tag_ids));
        $temp->setReplace("{ids}", json_encode($ids));
        $temp->setReplace("{sts_no}", json_encode($sts_no));
    }
} else {// history print 
    $tag_id = $_POST["tag_id"];
    $ModelTag = new Mv_Bc_Tag();
    $ModelTag->setConn($conn_sl);
    for ($i = 0; $i < count($tag_id); $i++) {
        $temp->setReplace("{printlist}", $temp->getTemplate("../template/tag_coil_block.html"));
        $ModelTag->GetProperties($tag_id[$i]);
        $temp->setReplace("{barcode}", $tag_id[$i]);
        $temp->setReplace("{Uf_Grade}", $ModelTag->uf_grade);
        $temp->setReplace("{qty_rec}", $ModelTag->qty1);
        $temp->setReplace("{Uf_coil_no}", $ModelTag->uf_coil_no);
        $temp->setReplace("{mdate}", $ModelTag->mfg_date);
        $temp->setReplace("{uf_Width}", number_format($ModelTag->uf_tickness, 2) . " x " . number_format($ModelTag->uf_width, 2));
        $temp->setReplace("{name}", $ModelTag->uf_name);
        $temp->setReplace("{lot}", $ModelTag->lot);
        $temp->setReplace("{item}", $ModelTag->item);
        $temp->setReplace("{po_num}", $ModelTag->po_num);
        $temp->setReplace("{sno}", "" . $ModelTag->doc_num . "");
    }

    $temp->setReplace("{print_type}", $printType);
//    $temp->setReplace("{ids}", json_encode($ids));
    $temp->setReplace("{sts_no}", json_encode($sts_no));
}

$temp->setReplace("{printlist}", "");
echo $temp->getReplace();
sqlsrv_close($conn_sl);
?>