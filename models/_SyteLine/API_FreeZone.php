<?php

class API_FreeZone {

    var $StrConn = "";
    public $_StartDate = "";
    public $_EndDate = "";
    public $_docs = array();

    function setConn($c) {
        $this->StrConn = $c;
    }

    function GenDocNumber($doc_type) {
        $docCode = "";
        ($doc_type == "import") ? $docCode = "IMP" : $docCode = "EXP";
        $query = "select doc_hdr FROM STS_freezone_hdr order by id desc";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);

        if (isset($rs0[0]["doc_hdr"])) {
            $lastNumber = substr($rs0[0]["doc_hdr"], 7);
            $docGen = $docCode . date("ym") . sprintf("%'.04d", ($lastNumber + 1));
        } else {
            $docGen = $docCode . date("ym") . "0001";
        }
        return $docGen;
    }

    function DocumentHeader() {
        $query = "select id,doc_hdr,name,doc_type,address,phone,convert(varchar, create_date, 103) as create_date FROM STS_freezone_hdr";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function DocumentLine($doc_hdr) {
        $queryHdr = "select * FROM STS_freezone_hdr where doc_hdr =  '$doc_hdr' ";
        $cSqlHdr = new SqlSrv();
        $rsHdr = $cSqlHdr->SqlQuery($this->StrConn, $queryHdr);
        if ($rsHdr[1]["doc_type"] == "import") {
            $query = "select ROW_NUMBER() OVER(ORDER BY (SELECT 1)) AS MyIndex,* FROM STS_freezone_line where doc_hdr =  '$doc_hdr' ";
        } else {
            $query = "select ROW_NUMBER() OVER(ORDER BY (SELECT 1)) AS MyIndex,sts.doc_hdr,STS_freezone_line.item_description, sts.item,item_mst.description, abs((sum(sts.item_qty))) as item_qty ,STS_freezone_line.item_weight,STS_freezone_line.item_price,po_num = stuff( ( select ', ' + sub.po_num + '( จำนวน'+CONVERT(varchar, abs(sub.item_qty))+')' from STS_freezone_log_history as sub    where sub.doc_hdr = sts.doc_hdr and sub.item = sts.item order by sub.po_num  for XML PATH('')),1,1,'') ,imp_ref = stuff( ( select ', ' + sub.imp_ref + '(รายการที่ '+CONVERT(varchar, abs(sub.doc_ref_line))+')'  from STS_freezone_log_history as sub   where sub.doc_hdr = sts.doc_hdr and sub.item = sts.item order by sub.po_num  for XML PATH('')),1,1,'')  FROM STS_freezone_log_history sts  LEFT join item_mst ON item_mst.item = sts.item LEFT join STS_freezone_line ON STS_freezone_line.doc_hdr = sts.doc_hdr and  STS_freezone_line.line_num = sts.line_num where sts.doc_hdr =  '$doc_hdr' group by sts.doc_hdr, sts.item ,item_mst.description ,STS_freezone_line.item_description,STS_freezone_line.item_weight,STS_freezone_line.item_price ";
        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function get_string_between($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }


        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        if (strpos($string, "group") == true) {
            return substr($string, $ini, $len);
        } else {
            return substr($string, $ini);
        }

        //return $end;
//return substr($string, $ini, $len);
    }

    function SQLQueryReport($report_name, $PostValues) {
        if ($report_name != "undefined") {
            $query = "select * FROM STS_freezone_report where report_name = '$report_name' ";
            $cSql = new SqlSrv();
            $rs0 = $cSql->SqlQuery($this->StrConn, $query);
            array_splice($rs0, count($rs0) - 1, 1);
            $reprot_query = $rs0[0]['reprot_query'];

            // เรียกใช้ query where จาก rows ของ values ที่ ส่งมา
            $where_query = "1=1 ";
            if ($PostValues != "") {
                for ($i = 0; $i < count(array_keys($PostValues)); $i++) {
                    $where_query = $where_query . "and " . array_keys($PostValues)[$i] . " like '%" . $PostValues[array_keys($PostValues)[$i]] . "%' ";
                }
            }


            $query2 = str_replace("condition_string", $where_query, $reprot_query);
            $cSql2 = new SqlSrv();
            $rs2 = $cSql2->SqlQuery($this->StrConn, $query2);
            array_splice($rs2, count($rs2) - 1, 1);

            $newArr = [];
            array_push($newArr, ["data" => $rs2]);
            array_push($newArr, ["fieldSearch" => $PostValues]);
            return $newArr;
        } else {
            return "";
        }
    }

    function SearchInputReport($report_name) {
        $query = "select * FROM STS_freezone_report_input_search where report_name = '$report_name' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_freezone_report($department_name) {

        $query = "select * FROM STS_freezone_report where 1=1 ";
        if ($department_name != "") {
            $query = $query . "and department_name = '$department_name' ";
        }
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function STS_freezone_department() {
        $query = "select department_name as report_description FROM STS_freezone_report group by department_name";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function exportItemSelect() {
        $query = "select item,sum(item_qty) as item_qty FROM STS_freezone_itemwearhouse group by item";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function exportPoItemSelect($item) {
        $query = " select * FROM STS_freezone_itemwearhouse  where item = '$item' and item_qty > 0 ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function PostCreateDocument($PostValues) {
        if (isset($PostValues["docLine"][0])) {
            (isset($PostValues['doc_type'])) ? $doc_type = $PostValues['doc_type'] : $doc_type = "";
            $cSql = new SqlSrv();
            $newDocNumber = $this->GenDocNumber($doc_type);
            $query = "INSERT INTO STS_freezone_hdr (doc_hdr,name,address,phone,tax,doc_type,doc_po,tax_ex,inv_num,doc_ref,create_date) "
                    . "VALUES ('" . $newDocNumber . "',"
                    . "'" . $PostValues['name'] . "'" . ","
                    . "'" . $PostValues['address'] . "'" . ","
                    . "'" . $PostValues['phone'] . "'" . ","
                    . "'" . $PostValues['tax'] . "'" . ","
                    . "'" . $PostValues['doc_type'] . "'" . ","
                    . "'" . $PostValues['doc_po'] . "'" . ","
                    . "'" . $PostValues['tax_ex'] . "'" . ","
                    . "'" . $PostValues['inv_num'] . "'" . ","
                    . "'" . $PostValues['doc_ref'] . "'" . ","
                    . "GETDATE()) ";
            $cSql->SqlQuery($this->StrConn, $query);


            for ($i = 0; $i < count($PostValues["docLine"]); $i++) {
                $line_num = $i + 1;
                $item = $PostValues['docLine'][$i]['item'];
                $item_qty = ($doc_type == "export") ? $item_qty = (($PostValues['docLine'][$i]['item_qty']) * (-1)) : (($PostValues['docLine'][$i]['item_qty']) * (1));
                (isset($PostValues['docLine'][$i]['ref_num'])) ? $ref_num = $PostValues['docLine'][$i]['ref_num'] : $ref_num = "";
                $po_num = $PostValues['docLine'][$i]['po_num'];
                $doc_ref_line = 0;
                ($PostValues['doc_type'] == 'import') ? $doc_ref_line = $line_num : $doc_ref_line = $line_num;

                if (isset($PostValues['docLine'][$i]['doc_ref'])) {
                    $ref_num = $PostValues['docLine'][$i]['doc_ref'];
                } else {
                    $ref_num = $PostValues['doc_ref'];
                }

                $query2 = "INSERT INTO STS_freezone_line "
                        . "(doc_hdr,doc_ref_line,line_num,item,bundle_qty,"
                        . "item_unit,item_description,item_price,"
                        . "po_num,item_weight,item_qty,doc_type"
                        . ",doc_ref) "
                        . "VALUES ('" . $newDocNumber . "','" . $doc_ref_line . "',$line_num," . "'" . $item . "'" . ","
                        . "'" . $PostValues['docLine'][$i]['item_qty'] . "'" . ","
                        . "'" . $PostValues['docLine'][$i]['item_unit'] . "'" . ","
                        . "'" . $PostValues['docLine'][$i]['item_description'] . "'" . ","
                        . "'" . $PostValues['docLine'][$i]['item_price'] . "'" . ","
                        . "'" . $po_num . "'" . ","
                        . "'" . $PostValues['docLine'][$i]['item_weight'] . "'" . ","
                        . "'" . $PostValues['docLine'][$i]['item_qty'] . "'" . ","
                        . "'" . $PostValues['doc_type'] . "'" . ","
                        . "'" . $ref_num . "') ";
                $cSql->SqlQuery($this->StrConn, $query2);

                $query3 = "INSERT INTO STS_freezone_log_history (doc_hdr,doc_ref_line,line_num,item,item_qty,ref_num,po_num,doc_type,create_date) "
                        . "VALUES ("
                        . "'" . $newDocNumber . "',"
                        . "'" . $doc_ref_line . "',"
                        . "$line_num,"
                        . "'" . $item . "'" . ","
                        . "" . $item_qty . ","
                        . "'" . $ref_num . "'" . ","
                        . "'" . $po_num . "'" . ","
                        . "'" . $PostValues['doc_type'] . "'" . ","
                        . "GETDATE()"
                        . ") ";
                $cSql->SqlQuery($this->StrConn, $query3);

                $query4 = "select * FROM STS_freezone_itemwearhouse "
                        . "where po_num = '$po_num'  and item = '$item' ";
                $rs4 = $cSql->SqlQuery($this->StrConn, $query4);
                array_splice($rs4, count($rs4) - 1, 1);



                if ($PostValues['doc_type'] == 'export') {
                    $itemwearhouse_qty = $rs4[0]['item_qty'] + $item_qty;
                    $queryUpdateInsertWearhouse = "UPDATE STS_freezone_itemwearhouse SET item_qty = $itemwearhouse_qty "
                            . "where po_num = '$po_num' and doc_ref_line = '$doc_ref_line' and item = '$item' ";
                } else {
                    $queryUpdateInsertWearhouse = "INSERT INTO STS_freezone_itemwearhouse (doc_hdr,ref_num,po_num,doc_ref_line,item,item_qty) "
                            . "VALUES ('$newDocNumber','$ref_num','$po_num','$doc_ref_line','$item',$item_qty)";
                }
                $cSql->SqlQuery($this->StrConn, $queryUpdateInsertWearhouse);
            }
        } else {
            echo '0';
        }
    }

    function GetDoList() {
        $query = "select do_num from do_hdr_mst
where stat <> 'A' and (do_num like 'DO%' or do_num like 'DX%') and convert(date,createdate) > '2025-06-30'
order by do_num desc";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetReportCountPipe($do_num, $start_date, $end_date) {
        $wh = '';
        if ($do_num !== '') {
            $wh = $wh . "and do_num = '$do_num' ";
        }
        
        if ($start_date !== '' && $end_date !== '') {
            $wh = $wh . "and convert(date, createdate) between '$start_date' and '$end_date' ";
        }

        $query = "SELECT * FROM STS_count_pipe WHERE 1=1 $wh";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function CreateCountPipe($do_num, $qty_system, $qty_human, $user, $file_type) {
        $s = "SELECT do_num FROM STS_count_pipe WHERE do_num = '$do_num' ORDER BY do_num DESC";
        $cSql = new SqlSrv();
        $do = $cSql->SqlQuery($this->StrConn, $s);
        $do_count = count($do);
        if ($do_count > 1) {
            $do_count = $do_count + 0;
        } else {
            $do_count = 1;
        }
        $pathName = $do_num . "_" . $do_count . "_" . $user . "." . $file_type;
        $query = "INSERT INTO STS_count_pipe (do_num,qty_system_count,qty_human_count,path,[user]) OUTPUT INSERTED.* VALUES('$do_num','$qty_system','$qty_human','$pathName','$user')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

}
