<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "./initial.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($loadPage == "SaleOrder") {
    if ($loadDiv == "Head") {
        $temp = new ReplaceHtml("../template/partial/SaleOrderHead.html");
        $DataLine = "";
        $User = new User();
        $User->setConn($ConnWebApp);
        $User->GetProperties(" WHERE user_id = " . $_SESSION["login_user_id"]);
        $LinkVendor = $User->ref_so_custnum;
        $UserType = $_SESSION["login_type"];
        $Keyword = "";
        if ($fltKeyword) {
            $Keyword = " AND concat(doc_no , po_num ) LIKE '%$fltKeyword%' ";
        }
        $List = new OrderHead();
        $List->setConn($ConnWebApp);
        $GetList = array();
        $typeSearch = $type;
        $GetList = $List->GetRows($fltStartDate, $fltEndDate, $fltStatus, 1, $UserType, $LinkVendor, $typeSearch, $Keyword);
        $data = array();
        $tmpLine = "";
        $cls = "";
        $tmpStatus = "";
        $tDocNo = "";
        $tPO = "";
        $tVendName = "";
        $tDate = "";
        $sStatus = "";
        $btnView = "";
        $list_count = 0;
        $i = 0;
        if ($fltStatus == "0") {
            $sStatus = "ALL";
        } else if ($fltStatus == "P") {
            $sStatus = "PENDING";
        } else if ($fltStatus == "A") {
            $sStatus = "APPROVED";
        }
        $hideStyle = "";
        if ($UserType == "C") {
            $hideStyle = "display:none;";
        } else {
            $hideStyle = "";
        }
        if (count($GetList) > 0) {
            foreach ($GetList as $index => $rows) {
                $i++;
                $tPO = $rows["po_num"];
                $tDocNo = $rows["doc_no"];
                $tVendName = $rows["vend_name"];
                $tDate = $rows["po_date"]->format('Y-m-d');

                $List2 = new OrderHead();
                $List2->setConn($ConnWebApp);
                $List2->_doc_no = $tDocNo;
                $List2->GetPropertiesByDocNo();
                $list_count = $List2->ListCount();
                $List2 = null;

                $index % 2 == 0 ? $cls = "row-odd" : $cls = "row-even";
                if ($rows["status"] == "P") {
                    $tmpStatus = "PENDING";
                } else if ($rows["status"] == "A") {
                    $tmpStatus = "<font color='green'>APPROVED</font>";
                }
                $btnView = "<a "
                        . "href='?SOD/SaleOrderView&doc_no=$tDocNo&ShowTableOnly=N&fltStartDate=$fltStartDate&fltEndDate=$fltEndDate&fltStatus=$fltStatus'  "
                        . "  id='viewData_$tDocNo'>"
                        . "<i class='fas fa-upload'></i>"
                        . "</a>";
                $btnEdit = "<a "
                        . "href='?SOD/SOD/SaleOrderEdit&doc_no=$tDocNo&ShowTableOnly=N&fltStartDate=$fltStartDate&fltEndDate=$fltEndDate&fltStatus=$fltStatus'  "
                        . "  id='EditData_$tDocNo'>"
                        . "<i class='fas fa-edit'></i>"
                        . "</a>";
                $btnClose = "<a href='#' OnClick='CloseDetail(this.id);' id='SOHeadClose_$tDocNo' ><i  class='fa fa-chevron-down'></i></a>";
                $tmpLine = $tmpLine . "<tr id='rowSelect_$tDocNo' data-po-num='$tPO'  data-doc-no='$tDocNo'OnClick='SelectList(this.id);' row-state='$cls'  class='$cls' >";
                $tmpLine = $tmpLine . "<td>$i</td>";
                $tmpLine = $tmpLine . "<td align='center'><a href='?SOD/SaleOrderView&doc_no=$tDocNo'>$tDocNo</a></td>";
                $tmpLine = $tmpLine . "<td >$tPO</td>";
                $tmpLine = $tmpLine . "<td align='center'>$tDate</td>";
                $tmpLine = $tmpLine . "<td style='$hideStyle' align='center'>$tVendName</td>";             
                
                $tmpLine = $tmpLine . "<td align='center'>$list_count</td>";
                $tmpLine = $tmpLine . "<td style='$hideStyle' align='center'>$tmpStatus</td>";
                $tmpLine = $tmpLine . "<td align='center'>$btnView | $btnEdit</td>";
                $tmpLine = $tmpLine . "</tr>";
                $tmpLine = $tmpLine . "<tr><td style='display:none;' colspan='8' id='rowSelectSub_$tDocNo'  ></td>";
                $tmpLine = $tmpLine . "</tr>";
            }
        } else {
            $tmpLine = "<tr  class='tr-blank'><td   style='text-align:center;padding-top:50px;padding-bottom:50px;'colspan='8'>.. No data From `$fltStartDate` to `$fltEndDate` with status `$sStatus` ...</td></tr>";
        }
        $temp->setReplace("{hiding_for_Customer}", $hideStyle);
        $temp->setReplace("{head_datas}", $tmpLine);
        echo $temp->getReplace();
    } else if ($loadDiv == "Detail") {
        $temp = new ReplaceHtml("../template/partial/SaleOrderDetail.html");
        $Detail = new OrderDetail();
        
        $Detail->setConn($ConnWebApp);
        $Detail->_doc_no = $doc_no;
        $Details = $Detail->GetRowsByDocNo();
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
        foreach ($Details as $i => $r) {
            $CountGridRow++;
            $tItem = $r["item"];
            $tItemDesc = $r["item_desc"];
            $size = "";
            $tLot = $r["lot"];
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
            $tblLines = $tblLines . "$lotShow<tr><td rowspan='2' style='vertical-align:middle;text-align:center;' id='rowLine_$CountGridRow'>$CountGridRow</td>"
                    . "<td  class='text-left' colspan='15'>"
                    . "<div id='divKeepItemArray_$CountGridRow' data-stuff='' ></div>"
                    . "<div class='row'>"
                    . "<div class='col-md-4'><h6 id='rowItem_$CountGridRow'>$tItem</h6><h6 id='rowDesc_$CountGridRow'>$tItemDesc</h6></div>"
                    . "<div class='col-md-4'><h6 id='rowResult_$CountGridRow'></h6></div>"
                    . "<div class='col-md-4' style='display:none;' id='divInputGroup_$CountGridRow'>"
                    . "<select class='form-control' id='selSelectItem_$CountGridRow' class='form-control'></select>"
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
//            $tblLines = $tblLines . "<td id = 'rowNTONS_$CountGridRow'align = 'right'>" . number_format($r["net_tons"], 2) . "</td>";
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
        if ($LoadTo == "index") {
            $temp->setReplace("{ShowDivStyle}", "style = 'height:200px;overflow-y:auto;margin-left:5px;margin-right:5px;'");
        } else {
            $temp->setReplace("{ShowDivStyle}", "");
        }
        $temp->setReplace("{cust_measure}", $_SESSION["login_ms_desc"]);
        $temp->setReplace("{flag_duty}", $_SESSION["login_user_duty"]);
        $temp->setReplace("{table_list}", $tblLines);
        echo $temp->getReplace();
    } else if ($loadDiv == "DetailEdit") {
        $temp = new ReplaceHtml("../template/partial/SOD/SaleOrderEdit.html");
        $Detail = new OrderDetail();
        $Detail->setConn($ConnWebApp);
        $Detail->_doc_no = $doc_no;
        $Details = $Detail->GetRowsByDocNo();
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
        $bgColor = "";
        foreach ($Details as $i => $r) {
            if ($i % 2 == 0) {
                $bgColor = "#eaeaea";
            } else {
                $bgColor = "#FFFFFF";
            }
            $CountGridRow++;
            $tItem = $r["item"];
            $id = $r["or_id"];
            $tItemDesc = $r["item_desc"];
            $unit_w = $r["unit_weight"];
            $size = "";
            $tLot = $r["lot"];
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


            $tblLines = $tblLines . "<tr style='background-color:$bgColor;'><td rowspan='2' style='vertical-align:middle;text-align:center;' id='rowLine_$id'>$CountGridRow</td>"
                    . "<td  class='text-left' colspan='15'>"
                    . "<div class='col-md-1 text-right' style='padding-top:5px;'>Item : </div>"
                    . "<div class='col-md-3'><input type='text' class='form-control' OnBlur='AutoSaveDetail(this.id);' id='edtItem_$id' value='$tItem' ></div>"
                    . "<div class='col-md-1 text-right' style='padding-top:5px;'>Description : </div>"
                    . "<div class='col-md-3'><input type='text' class='form-control'  OnBlur='AutoSaveDetail(this.id);'  id='edtItemDesc_$id' value='$tItemDesc' ></div>"
                    . "<div class='col-md-1 text-right' style='padding-top:5px;'>Lot : </div>"
                    . "<div class='col-md-3'><input type='text' class='form-control'  OnBlur='AutoSaveDetail(this.id);'  id='edtLot_$id' value='$tLot' ></div>"
                    . "</td>"
                    . "</tr>";
            $tblLines = $tblLines . "<tr id='rowIndex_$id'>";
            $tblLines = $tblLines . "<td id = 'rowSIZE_$id' align = 'right'>"
                    . "<input type='hidden' id='UnitWeight_$id' value='$unit_w' >"
                    . "<input type='text' class='form-control'  OnBlur='AutoSaveDetail(this.id);'  id='edtSize_$id' value='$size' >"
                    . "</td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowTW_$id'align = 'right'>" . $r["theo_weight"] . "</td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowUL_$id'align = 'right'><input type='text' onkeyup='InputValue(this.id)' OnBlur='AutoSaveDetail(this.id);'  onkeypress='return number_check(event);' class='form-control' id='edtLEN_$id' value='" . $r["length"] . "' ></td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowCATE_$id'align = 'center'><input type='text' class='form-control'  OnBlur='AutoSaveDetail(this.id);'  id='edtCate_$id' value='" . $r["category"] . "' ></td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowSCHED_$id'align = 'right'><input type='text' class='form-control'  OnBlur='AutoSaveDetail(this.id);'  id='edtSched_$id' value='" . $r["sched"] . "' ></td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowPCSPERBNDL_$id'align = 'right'><input onkeyup='InputValue(this.id)' OnBlur='AutoSaveDetail(this.id);' onkeypress='return number_check(event);' type='text' class='form-control' id='edtPcsPerBndl_$id' value='" . $r["pcs_per_bundle"] . "' ></td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowBNDL_$id'align = 'right'><input onkeyup='InputValue(this.id)' onkeypress='return number_check(event);'   OnBlur='AutoSaveDetail(this.id);'  type='text' class='form-control' id='edtBNDL_$id' value='" . $r["bundles"] . "' ></td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowPCS_$id'align = 'right'>" . $r["pieces"] . "</td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowTTLEN_$id' align = 'right'>" . $r["length_total"] . "</td>";
//            $tblLines = $tblLines . "<td id = 'rowNTONS_$id'align = 'right'>" . number_format($r["net_tons"], 2) . "</td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowMTONS_$id'align = 'right'>" . $r["m_tons"] . "</td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowCFRMT_$id'align = 'right'><input onkeyup='InputValue(this.id)' onkeypress='return number_check(event);'  OnBlur='AutoSaveDetail(this.id);'  type='text' class='form-control' id='edtCFRMT_$id' value='" . $r["cfr_lo_mt"] . "' ></td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowCFRFT_$id' align = 'right'><input onkeyup='InputValue(this.id)' onkeypress='return number_check(event);'   OnBlur='AutoSaveDetail(this.id);'  type='text' class='form-control' id='edtCFRFT_$id' value='" . $r["cfr_lo_ft"] . "' ></td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;' id = 'rowEXTPRICE_$id' align = 'right'>" . $r["ext_price"] . "</td>";
            $tblLines = $tblLines . "<td style='background-color:$bgColor;'  align = 'center'><a OnClick='removeEditRow($id);' style='color:red;padding-top:10px;text-align:center;' id='removeEditRow_$id'><i class='fa fa-times'></i></a></td>";
            $tblLines = $tblLines . "</tr>";
            $s_bndl = (float) $s_bndl + (float) str_replace(",", "", $r["bundles"]);
            $s_pieces = (float) $s_pieces + (float) str_replace(",", "", $r["pieces"]);
            $s_length_total = (float) $s_length_total + (float) str_replace(",", "", $r["length_total"]);
            $s_net_tons = (float) $s_net_tons + (float) str_replace(",", "", $r["net_tons"]);
            $s_m_tons = (float) $s_m_tons + (float) str_replace(",", "", $r["m_tons"]);
            $s_cfr_lo_mt = (float) $s_cfr_lo_mt + (float) str_replace(",", "", $r["cfr_lo_mt"]);
            $s_cfr_lo_ft = (float) $s_cfr_lo_ft + (float) str_replace(",", "", $r["cfr_lo_ft"]);
            $s_ext_price = (float) $s_ext_price + (float) str_replace(",", "", $r["ext_price"]);
        }

        $tblLines = $tblLines . "<tr id='rowMainSummary'>";
        $tblLines = $tblLines . "<td align = 'right' colspan='7'><b>Total : </b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_bndl, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_pieces, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_length_total, 2) . "</b></td>";
//        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_net_tons, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_m_tons, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_cfr_lo_mt, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_cfr_lo_ft, 2) . "</b></td>";
        $tblLines = $tblLines . "<td align = 'right'><b>" . number_format($s_ext_price, 2) . "</b></td>";
        $tblLines = $tblLines . "</tr>";
        if ($LoadTo == "index") {
            $temp->setReplace("{ShowDivStyle}", "style = 'height:200px;overflow-y:auto;margin-left:5px;margin-right:5px;'");
        } else {
            $temp->setReplace("{ShowDivStyle}", "");
        }
        $temp->setReplace("{cust_measure}", $_SESSION["login_ms_desc"]);
        $temp->setReplace("{flag_duty}", $_SESSION["login_user_duty"]);
        $temp->setReplace("{table_list}", $tblLines);
        echo $temp->getReplace();
    } 
}


sqlsrv_close($ConnWebApp);
sqlsrv_close($ConnSL);
