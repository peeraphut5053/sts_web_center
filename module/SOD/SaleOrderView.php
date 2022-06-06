<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

if (isset($_POST["ShowTableOnl"]) && ($ShowTableOnly == "Y")) {
    /// Replace when show in dialog 
    include "./initial.php";
    session_start();
    $temp = new ReplaceHtml("../template/SaleOrderView.html");
} else {

    /// Replace when show normal
    $temp->setReplace("{content}", $temp->getTemplate("./template/SaleOrderView.html"));
}

$u_name = $_SESSION["login_username"];
$User = new User();
$User->setConn($ConnWebApp);
$User->GetProperties(" WHERE username = '$u_name' ");

$temp->setReplace("{doc_date}", date('Y-m-d'));
$query = "SELECT * FROM v_order_head "
        . "WHERE  doc_no ='$doc_no' ";

$debug_query = $query;
$cSql = new SqlSrv();
$rs0 = $cSql->SqlQuery($ConnWebApp, $query);
$id = $rs0[1]["id"];
$cust_num = $rs0[1]["cust_num"];
$po_num = $rs0[1]["po_num"];
$doc_no = $rs0[1]["doc_no"];
$ship_by = $rs0[1]["ship_by"];
$po_date = $rs0[1]["po_date"]->format('Y-m-d');
$ship_to = $rs0[1]["ship_to"];
$ship_to = $rs0[1]["ship_to"];
$sl_co = $rs0[1]["sl_co"];
$ship_to_text = $rs0[1]["ship_to_text"];
$po_status = $rs0[1]["po_status"];
$cust_num_sl = $rs0[1]["cust_num_sl2"];
$attn = $rs0[1]["attn"];
$ShipExFrom = $rs0[1]["exmill_date_from"];
$ShipExTo = $rs0[1]["exmill_date_to"];
$spec = $rs0[1]["spec"];
$status = $rs0[1]["status"];
$doc_date = $rs0[1]["doc_date"]->format('Y-m-d');
$term_of_po = $rs0[1]["term_of_po"];
$remark = $rs0[1]["remark"];
$vend_addr1 = $rs0[1]["addr1"];
$vend_addr2 = $rs0[1]["addr2"];
$vend_addr3 = $rs0[1]["addr3"];
$vend_email = $rs0[1]["email"];
$vend_name = $rs0[1]["cust_name"];
$endcust1 = $rs0[1]["end_customer1"];
$endcust2 = $rs0[1]["end_customer2"];
$endcust3 = $rs0[1]["end_customer3"];
$endcust4 = $rs0[1]["end_customer4"];
$endcust5 = $rs0[1]["end_customer5"];
$endcust6 = $rs0[1]["end_customer6"];
$endcust7 = $rs0[1]["end_customer7"];
$endcust8 = $rs0[1]["end_customer8"];
$endcust9 = $rs0[1]["end_customer9"];
$endcust10 = $rs0[1]["end_customer10"];



$sl_co = $sl_co;
if ($sl_co) {
    $temp->setReplace("{doc_uploaded}", $sl_co);
} else {
    $temp->setReplace("{doc_uploaded}", "No");
}
$temp->setReplace("{xls_file}", $rs0[1]["xls_file"]);
$temp->setReplace("{xls_sheet}", $rs0[1]["xls_sheet"]);
$temp->setReplace("{upload_by}", $rs0[1]["u2_name"] ? $rs0[1]["u2_name"] : $rs0[1]["u1_name"]);
//$temp->setReplace("{upload_datetime}", $rs0[1]["last_upload_date"]->format('d/m/Y G:i:s')  ? $rs0[1]["last_upload_date"]->format('d/m/Y G:i:s') : $rs0[1]["upload_date"]->format('d/m/Y G:i:s'));

$temp->setReplace("{upload_by}", "Admin");
$temp->setReplace("{head_po_date}", $po_date);
$temp->setReplace("{head_doc_no}", $doc_no);
$temp->setReplace("{head_po_num}", $po_num);
$temp->setReplace("{cust_num}", $cust_num);
$temp->setReplace("{cust_num_sl}", $cust_num_sl);
$temp->setReplace("{head_doc_date}", $doc_date);
$temp->setReplace("{head_po_date}", $po_date);
//$temp->setReplace("{head_ship_from}", $ship_from);
$temp->setReplace("{head_ship_to}", $ship_to_text);
//$temp->setReplace("{head_ship_by}", $ship_by_name);
$temp->setReplace("{head_spec}", $spec);
$temp->setReplace("{head_terms}", $term_of_po);
$temp->setReplace("{head_attn}", $attn);
$temp->setReplace("{head_shipment}", $ShipExFrom . " - " . $ShipExTo);
$temp->setReplace("{head_remark}", $remark);
$temp->setReplace("{ship_to_id}", $ship_to);
$temp->setReplace("{end_customer1}", $endcust1);
$temp->setReplace("{end_customer2}", $endcust2);
$temp->setReplace("{end_customer3}", $endcust3);
$temp->setReplace("{end_customer4}", $endcust4);
$temp->setReplace("{end_customer5}", $endcust5);
$temp->setReplace("{end_customer6}", $endcust6);
$temp->setReplace("{end_customer7}", $endcust7);
$temp->setReplace("{end_customer8}", $endcust8);
$temp->setReplace("{end_customer9}", $endcust9);
$temp->setReplace("{end_customer10}", $endcust10);
$temp->setReplace("{head_remark}", $remark);
//=========Build SL duedate 

$ddate = substr($ShipExTo, 3, 4) . "-" . substr($ShipExTo, 0, 2) . "-01";
$due_date = date("Y-m-t", strtotime($ddate));
$temp->setReplace("{due_date}", $due_date);

//==========detail


$AllFields = "  or_id,doc_no, line, item, item_desc, category, size, od, "
        . "theo_weight, length, length_total, sched, pcs_per_bundle, bundles, pieces, "
        . "net_tons, m_tons, cfr_lo_mt, cfr_lo_ft, ext_price ,lot,unit_weight , sl_item ";
$query = "SELECT $AllFields  FROM SO_Order_detail WHERE doc_no =  '$doc_no' ";
$cSql = new SqlSrv();
$Details = $cSql->SqlQuery($ConnWebApp, $query);
array_splice($Details, count($Details) - 1, 1);


$tblLines = "";
$CountGridRow = 0;
$tItem = "";
$tItemDesc = "";

$lotOut = "";
$s_pieces = 0;
$s_length_total = 0;
$s_net_tons = 0;
$s_m_tons = 0;
$s_cfr_lo_mt = 0;
$s_cfr_lo_ft = 0;
$s_ext_price = 0;
$s_bndl = 0;
$sl_item = "";
foreach ($Details as $i => $r) {

    $CountGridRow++;
    $tItem = $r["item"];
    $tItemDesc = $r["item_desc"];
    $size = "";
    $tLot = $r["lot"];
    $sl_item = $r["sl_item"];
    $lotShow = "";
    if (strpos($r["size"], '.500') !== false) {
        $size = str_replace(".500", " 1/2", $r["size"]);
    } else if (strpos($r["size"], '.50') !== false) {
        $size = str_replace(".50", " 1/2", $r["size"]);
    } else if (strpos($r["size"], '.5') !== false) {
        $size = str_replace(".5", " 1/2", $r["size"]);
    } else {
        $size = $r["size"];
    }
    if ($tLot != $lotOut) {
        $lotShow = "<tr><td class='text-left' colspan='15'><i><u><b>Lot : $tLot</i></u></b></td></tr>";
    }
    $lotOut = $tLot;
    $ShowMatchItem = "";
    $showItem = "";
    if ($sl_item) {
        $ShowMatchItem = "<i class='fa fa-check-circle' style='color:green;'></i>&nbsp;$sl_item";
    } else {
                    $ShowMatchItem = "<div class='input-group' style='width:100%;'>"
                    . "<div class='input-group-addon' >"
                    . "<a OnClick='MatchItem(this.id);' id='btnMatchItem_$CountGridRow' style='color:#FFFFFF;cursor:pointer;'><i class='fa fa-search'></i></a>"
                    . "</div>"
                    . "<input type='text' id='selSelectItem_$CountGridRow' class='form-control' readonly>"
                    . "</div>";
        //=========find item===========
//        $ItemSL = new ItemSyteLine();
//        $ItemSL->setConn($ConnSL);
//        $ItemSL->_item_category = $r["category"];
//        $ItemSL->_Uf_grade = $r["category"];
//        $ItemSL->_Uf_sched = $r["sched"];
//        $ItemSL->_Uf_Nps = $r["size"];
//        $ItemSL->_Uf_length = $r["length"];
//        $Items = $ItemSL->MatchItem();
//        
//        if (count($Items) == 1) {
//            $ShowMatchItem = "<div class='input-group' style='width:100%;'>"
//                    . "<div class='input-group-addon' >"
//                    . "<a  id='btnMatchItem_$CountGridRow' style='color:#FFFFFF;cursor:pointer;'><i class='fa fa-search'></i></a>"
//                    . "</div>"
//                    . "<select id='selSelectItem_$CountGridRow' class='form-control'><option value='".$Items[0]["item"]."'>".$Items[0]["item"]."</option></select>"
//                    . "</div>";
//        } else {
//            $ShowMatchItem = "<div class='input-group' style='width:100%;'>"
//                    . "<div class='input-group-addon' >"
//                    . "<a OnClick='MatchItem(this.id);' id='btnMatchItem_$CountGridRow' style='color:#FFFFFF;cursor:pointer;'><i class='fa fa-search'></i></a>"
//                    . "</div>"
//                    . "<select id='selSelectItem_$CountGridRow' class='form-control'><option value=''>..No item found..</option></select>"
//                    . "</div>";
//        }
        //==============================//
    }
    $tblLines = $tblLines . "$lotShow<tr><td rowspan='2' style='vertical-align:middle;text-align:center;' id='rowLine_$CountGridRow'>$CountGridRow</td>"
            . "<td  class='text-left' colspan='15'>"
            . "<div id='divKeepItemArray_$CountGridRow' data-stuff='' ></div>"
            . "<div class='row'>"
            . "<div class='col-md-4'><h6 id='rowItem_$CountGridRow'>$tItem</h6><h6 id='rowDesc_$CountGridRow'>$tItemDesc</h6></div>"
            . "<div class='col-md-4'><h6 id='rowResult_$CountGridRow'></h6></div>"
            . "<div class='col-md-4' style='padding-right:30px;' id='divInputGroup_$CountGridRow'>"
            . "$ShowMatchItem"
//                    . "<div class='input-group'><input id='txtSelectItem_$CountGridRow' class='form-control'>"
//                    . "<div id='divButtonSelect_$CountGridRow' class='input-group-addon'><a style='color:white;' Onclick='SelectItem(this.id);' id='btnSelectItem_$CountGridRow' href='#'>SELECT</a>"
            . "</div>"
            . "</div>"
            . "</div>"
            . "</div>"
            . "</td>"
            . "</tr>";
    $tblLines = $tblLines . "<tr>";
    $tblLines = $tblLines . "<td id = 'rowSIZE_$CountGridRow' align = 'right'>$size</td>";
    $tblLines = $tblLines . "<td id = 'rowTW_$CountGridRow'align = 'right'>" . $r["theo_weight"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowUL_$CountGridRow'align = 'right'>" . (int) ($r["length"]) . "</td>";
    $tblLines = $tblLines . "<td id = 'rowCATE_$CountGridRow'align = 'center'>" . $r["category"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowSCHED_$CountGridRow'align = 'right'>" . (int) $r["sched"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowPCSPERBNDL_$CountGridRow'align = 'right'>" . $r["pcs_per_bundle"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowBNDL_$CountGridRow'align = 'right'>" . (int) $r["bundles"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowPCS_$CountGridRow'align = 'right'>" . $r["pieces"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowTTLEN_$CountGridRow' align = 'right'>" . $r["length_total"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowMTONS_$CountGridRow'align = 'right'>" . $r["m_tons"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowCFRMT_$CountGridRow'align = 'right'>" . $r["cfr_lo_mt"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowCFRFT_$CountGridRow' align = 'right'>" . $r["cfr_lo_ft"] . "</td>";
    $tblLines = $tblLines . "<td id = 'rowEXTPRICE_$CountGridRow' align = 'right'>" . $r["ext_price"] . "</td>";
    $tblLines = $tblLines . "</tr>";
    $s_bndl = $s_bndl + (float) str_replace(",", "", $r["bundles"]);
    $s_pieces = $s_pieces + (float) str_replace(",", "", $r["pieces"]);
    $s_length_total = $s_length_total + (float) str_replace(",", "", $r["length_total"]);
//            $s_net_tons = $s_net_tons + $r["net_tons"];
    $s_m_tons = $s_m_tons + (float) str_replace(",", "", $r["m_tons"]);
    $s_cfr_lo_mt = $s_cfr_lo_mt + (float) str_replace(",", "", $r["cfr_lo_mt"]);
    $s_cfr_lo_ft = $s_cfr_lo_ft + (float) str_replace(",", "", $r["cfr_lo_ft"]);
    $s_ext_price = $s_ext_price + (float) str_replace(",", "", $r["ext_price"]);
}

$tblLines = $tblLines . "<tr>";
$tblLines = $tblLines . "<td align = 'right' colspan='7'><b>Total : </b></td>";
$tblLines = $tblLines . "<td align = 'right'><b>" . $s_bndl . "</b></td>";
$tblLines = $tblLines . "<td align = 'right'><b>" . $s_pieces . "</b></td>";
$tblLines = $tblLines . "<td align = 'right'><b>" . $s_length_total . "</b></td>";
//        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_net_tons, 2) . "</b></td>";
$tblLines = $tblLines . "<td align = 'right'><b>" . $s_m_tons . "</b></td>";
$tblLines = $tblLines . "<td align = 'right'><b>" . $s_cfr_lo_mt . "</b></td>";
$tblLines = $tblLines . "<td align = 'right'><b>" . $s_cfr_lo_ft . "</b></td>";
$tblLines = $tblLines . "<td align = 'right'><b>" . $s_ext_price . "</b></td>";
$tblLines = $tblLines . "</tr>";

//$temp->setReplace("{cust_measure}", $_SESSION["login_ms_desc"]);
$temp->setReplace("{flag_duty}", $_SESSION["login_user_duty"]);
$temp->setReplace("{table_list}", $tblLines);
//=============
//
//==============CUST Detail========
$Q1 = "SELECT cs.id,cust_num,name,cust_num_sl,addr1,addr2,addr3,tel1,tel2,"
        . " fax1,fax2,cs.measure as measure,ms.measure as measure2 ,measure_desc_en,measure_desc_th ,country,import_form "
        . " FROM SO_Customer cs "
        . " LEFT JOIN STS_MT_Measure ms ON ( cs.measure = ms.id ) WHERE cust_num = '$cust_num'  ";

$cSql = new SqlSrv();
$CustDetail = $cSql->SqlQuery($ConnWebApp, $Q1);
array_splice($CustDetail, count($CustDetail) - 1, 1);

$measure = "";
//print_r($CustDetail);
$temp->setReplace("{cust_measure}", $CustDetail[0]["measure_desc_en"]);
$temp->setReplace("{cust_measure2}", $CustDetail[0]["measure2"]);
$temp->setReplace("{head_cust_name}", $CustDetail[0]["name"]);
$temp->setReplace("{head_cust_addr}", $CustDetail[0]["addr1"] . ' ' . $CustDetail[0]["addr2"] . ' ' . $CustDetail[0]["addr3"]);
$temp->setReplace("{head_cust_tel}", $CustDetail[0]["tel1"] . ' ' . $CustDetail[0]["tel2"]);
$temp->setReplace("{head_cust_fax}", $CustDetail[0]["fax1"] . ' ' . $CustDetail[0]["fax2"]);
$approve_button = "";

if (isset($_POST["fltStartDate"])) {
    $temp->setReplace("{fltStartDate}", $fltStartDate);
    $temp->setReplace("{fltEndDate}", $fltEndDate);
    $temp->setReplace("{fltStatus}", $fltStatus);
}

