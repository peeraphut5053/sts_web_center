<?php

class OrderHead {

    var $StrConn = "";
    public $_po_num = "";
    public $_doc_no = "";
    public $_cust_num = "";
    public $_active = "";
    public $_po_date = "";
    public $_ship_to_text = "";
    public $_status = "";
    public $_ship_to = 0;
    public $_spec = "";
    public $_terms = "";
    public $_attn = "";
    public $_shipment = "";
    public $_remark = "";
    public $_ship_by = 0;
    public $_user_type = "";
    public $_lot = "";
    public $_sl_lot = "";
    public $_start_date = null;
    public $_end_date = null;
    public $_ShipExTo = null;
    public $_ShipExFrom = null;
    public $_xls_file = "";
    public $_xls_sheet = "";
    public $_endcust1 = "";
    public $_endcust2 = "";
    public $_endcust3 = "";
    public $_endcust4 = "";
    public $_endcust5 = "";
    public $_endcust6 = "";
    public $_endcust7 = "";
    public $_endcust8 = "";
    public $_endcust9 = "";
    public $_endcust10 = "";
    public $sl_co = "";
    public $cust_num_sl = "";
    public $endcust1 = "";
    public $endcust2 = "";
    public $endcust3 = "";
    public $endcust4 = "";
    public $endcust5 = "";
    public $endcust6 = "";
    public $endcust7 = "";
    public $endcust8 = "";
    public $endcust9 = "";
    public $endcust10 = "";
    public $ship_to_text = null;
    public $ShipExFrom;
    public $ShipExTo;
    public $id = "";
    public $cust_num = "";
    public $vend_addr1 = "";
    public $vend_addr2 = "";
    public $vend_addr3 = "";
    public $vend_email = "";
    public $vend_name = "";
    public $doc_date = "";
    public $name = "";
    public $po_num = "";
    public $doc_no = "";
    public $status = "";
    public $po_date = "";
    public $ship_from = "";
    public $ship_to = "";
    public $ship_by = "";
    public $ship_from_seq = "";
    public $ship_to_seq = "";
    public $ship_by_name = "";
    public $po_status = "";
    public $shipment = "";
    public $attn = "";
    public $spec = "";
    public $term_of_po = "";
    public $remark = "";
    public $list_count = "";
    public $debug_query = "";
    public $xls_file = "";
    public $xls_sheet = "";

//    private $AllFields = "doc_no,ship_by, head.id,head.cust_num,po_num,po_date,ship_from,ship_to,po_status,shipment,attn,spec,term_of_po,remark,doc_date,status ";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function Update() {
        $doc_no = $this->_doc_no;
        $po_num = $this->_po_num;
        $po_date = $this->_po_date;
        $ship_to_text = $this->_ship_to_text;
        $ship_to = $this->_ship_to;
        $shipment = $this->_shipment;
        $attn = $this->_attn;
        $spec = $this->_spec;
        $term_of_po = $this->_terms;
        $remark = $this->_remark;
        $modified_date = date('Y-m-d G:i:s');
        $exmill_date_from = $this->_ShipExFrom;
        $exmill_date_to = $this->_ShipExTo;
        $upd = "UPDATE SO_Order_head SET  "
                . " po_num ='$po_num' "
                . ", po_date ='$po_date' "
                . ", ship_to_text ='$ship_to_text' "
                . ", ship_to =$ship_to "
                . ", shipment ='$shipment'  "
                . ", attn ='$attn'"
                . ", spec ='$spec'"
                . ", term_of_po ='$term_of_po'"
                . ", remark ='$remark'"
                . ", modified_date ='$modified_date'"
                . ", exmill_date_from = '$exmill_date_from'"
                . ", exmill_date_to ='$exmill_date_to'  "
                . "WHERE doc_no = '$doc_no' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->IsUpDel($this->StrConn, $upd);
        return $rs0;
    }

    function GetPropertiesByDocNo() {
        $doc_no = $this->_doc_no;
        $query = "SELECT doc_no,ship_by, head.id,head.cust_num,po_num,po_date,ship_to,ship_to_text,"
                . "po_status,shipment,attn,spec,term_of_po,remark,doc_date,status,"
                . " cs.addr1,cs.addr2 ,cs.addr3 ,exmill_date_to,exmill_date_from ,"
                . "end_customer1,end_customer2,end_customer3,end_customer4,end_customer5,end_customer6,end_customer7,end_customer8,end_customer9,end_customer10 ,"
                . "cs.email,cs.name ,sl_co,cs.cust_num_sl  FROM SO_Order_head head "
                . "LEFT JOIN SO_Customer cs ON (head.cust_num = cs.cust_num) "
                . "WHERE  doc_no ='$doc_no' ";

        $this->debug_query = $query;
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        $this->id = $rs0[1]["id"];
        $this->cust_num = $rs0[1]["cust_num"];
        $this->po_num = $rs0[1]["po_num"];
        $this->doc_no = $rs0[1]["doc_no"];
        $this->ship_by = $rs0[1]["ship_by"];
        $this->po_date = $rs0[1]["po_date"]->format('Y-m-d');
//        $this->ship_from = $rs0[1]["ship_from"];
        $this->ship_to = $rs0[1]["ship_to"];
//        $this->ship_from = $rs0[1]["ship_from"];
        $this->ship_to = $rs0[1]["ship_to"];
        $this->sl_co = $rs0[1]["sl_co"];
        $this->ship_to_text = $rs0[1]["ship_to_text"];
//        $this->ship_from_seq = $rs0[1]["ship_from_seq"];
//        $this->ship_to_seq = $rs0[1]["ship_to_seq"]
//        $this->ship_by_name = $rs0[1]["ship_name"];
        $this->po_status = $rs0[1]["po_status"];
        $this->cust_num_sl = $rs0[1]["cust_num_sl"];
        $this->attn = $rs0[1]["attn"];
        $this->ShipExFrom = $rs0[1]["exmill_date_from"];
        $this->ShipExTo = $rs0[1]["exmill_date_to"];
        $this->spec = $rs0[1]["spec"];
        $this->status = $rs0[1]["status"];
        $this->doc_date = $rs0[1]["doc_date"]->format('Y-m-d');
        $this->term_of_po = $rs0[1]["term_of_po"];
        $this->remark = $rs0[1]["remark"];
        $this->vend_addr1 = $rs0[1]["addr1"];
        $this->vend_addr2 = $rs0[1]["addr2"];
        $this->vend_addr3 = $rs0[1]["addr3"];
        $this->vend_email = $rs0[1]["email"];
        $this->vend_name = $rs0[1]["name"];
        $this->endcust1 = $rs0[1]["end_customer1"];
        $this->endcust2 = $rs0[1]["end_customer2"];
        $this->endcust3 = $rs0[1]["end_customer3"];
        $this->endcust4 = $rs0[1]["end_customer4"];
        $this->endcust5 = $rs0[1]["end_customer5"];
        $this->endcust6 = $rs0[1]["end_customer6"];
        $this->endcust7 = $rs0[1]["end_customer7"];
        $this->endcust8 = $rs0[1]["end_customer8"];
        $this->endcust9 = $rs0[1]["end_customer9"];
        $this->endcust10 = $rs0[1]["end_customer10"];
    }

    function GetProperties() {

        $doc_no = $this->_doc_no;
        $query = "SELECT doc_no,ship_by, id,cust_num,po_num,po_date,ship_from,ship_to,"
                . "po_status,shipment,attn,spec,term_of_po,remark,doc_date,status ,"
                . "end_customer1,end_customer2,end_customer3,end_customer4,end_customer5,end_customer6,end_customer7,end_customer8,end_customer9,end_customer10 "
                . "FROM SO_Order_head "
                . "WHERE doc_no ='$doc_no' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        $this->id = $rs0[1]["id"];
        $this->cust_num = $rs0[1]["cust_num"];
        $this->po_num = $rs0[1]["po_num"];
        $this->doc_no = $rs0[1]["doc_no"];
        $this->ship_by = $rs0[1]["ship_by"];
        $this->po_date = $rs0[1]["po_date"];
        $this->ship_from = $rs0[1]["ship_from"];
        $this->ship_to = $rs0[1]["ship_to"];
        $this->po_status = $rs0[1]["po_status"];
        $this->shipment = $rs0[1]["shipment"];
        $this->attn = $rs0[1]["attn"];
        $this->doc_date = $rs0[1]["doc_date"]->format('Y-m-d');
        $this->spec = $rs0[1]["spec"];
        $this->term_of_po = $rs0[1]["term_of_po"];
        $this->remark = $rs0[1]["remark"];
        $this->status = $rs0[1]["status"];
        $this->endcust1 = $rs0[1]["end_customer1"];
        $this->endcust2 = $rs0[1]["end_customer2"];
        $this->endcust3 = $rs0[1]["end_customer3"];
        $this->endcust4 = $rs0[1]["end_customer4"];
        $this->endcust5 = $rs0[1]["end_customer5"];
        $this->endcust6 = $rs0[1]["end_customer6"];
        $this->endcust7 = $rs0[1]["end_customer7"];
        $this->endcust8 = $rs0[1]["end_customer8"];
        $this->endcust9 = $rs0[1]["end_customer9"];
        $this->endcust10 = $rs0[1]["end_customer10"];
    }

    function ListCount() {
        $query = "SELECT count(or_id) as listcount  FROM SO_Order_Detail WHERE doc_no = '" . $this->_doc_no . "'  ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        return $rs0[1]["listcount"];
    }

    function GetRows($startdate, $enddate, $status, $active, $usertype, $cust_num, $typeSearch, $Keyword) {
        $fltCustomer = "";
        $statusPart = "";
        $fltSearch = "";

        if ($status != '0') {
            $statusPart = "  status = '$status' AND ";
        }
        if ($usertype == "C") {
            $fltCustomer = " AND cs.cust_num = '$cust_num' ";
        }
        if ($typeSearch == 0) { //Search Normal 
            $fltSearch = " AND (po_date BETWEEN '$startdate' AND '$enddate')  ";
        } else {  //Search All
        }
        $query = "SELECT sl_co,CS.name as vend_name, doc_no,ship_by, OH.id,OH.cust_num,po_num,po_date,ship_from,ship_to,"
                . "po_status,shipment,attn,spec,term_of_po,remark,doc_date,status , "
                . "end_customer1,end_customer2,end_customer3,end_customer4,end_customer5,end_customer6,end_customer7,end_customer8,end_customer9,end_customer10 "
                . " FROM SO_Order_head OH LEFT JOIN SO_Customer CS ON(OH.cust_num = CS.cust_num) "
                . "WHERE $statusPart  active = $active  $fltSearch  $fltCustomer  $Keyword "
                . "ORDER BY created_date DESC ";

        $cSql = new SqlSrv();

        $rs0 = $cSql->SqlQuery($this->StrConn, $query);

        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GenNewDocNo($custnum) {
        //================Gen Head Code ===============//
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 doc_no FROM  SO_Order_head where doc_no like '$custnum%' ORDER BY doc_no DESC  ";
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        $tmpDocNo = "";
        $tmpDocNoDate = "";
        $tmpDocNoCutDate = "";
        $CurrDate = date("Ymd");
        $val = "";

        if (count($rs) <= 1) {
            return $custnum . $CurrDate . "0001";
        } else {
            $tmpDocNo = $rs[1]["doc_no"];
            $len = strlen($custnum);
            $dlen = strlen($tmpDocNo);
            $tmpDocNoCutPrefix = substr($tmpDocNo, $len, $dlen);
            $tmpDocNoDate = substr($tmpDocNoCutPrefix, 0, 8);
            $tmpDocNoCutDate = substr($tmpDocNoCutPrefix, 8, 13);
            if ($CurrDate == $tmpDocNoDate) {
                $val = str_pad(strval($tmpDocNoCutDate) + 1, 4, '0', STR_PAD_LEFT);
                return $custnum . "" . $CurrDate . "" . $val;
            } else {
                return $custnum . "" . $CurrDate . "0001";
            }
        }
//================Gen Head Code ===============//
    }

    function Insert() {

        $user_id = $_SESSION["login_user_id"];

        $date = date("Y-m-d");
        $dateTime = date("Y-m-d H:i:s");
        $cSql = new SqlSrv();
        $sql = "INSERT INTO SO_Order_head "
                . "("
                . "cust_num,"
                . "doc_no,"
                . "po_num,"
                . "po_date,"
                . "ship_to,"
                . "ship_to_text,"
                . "attn,"
                . "spec,"
                . "term_of_po,"
                . "remark,"
                . "doc_date,"
                . "created_date,"
                . "active,"
                . "created_by,"
                . "status,"
                . "exmill_date_from,"
                . "exmill_date_to,"
                . "end_customer1,"
                . "end_customer2,"
                . "end_customer3,"
                . "end_customer4,"
                . "end_customer5,"
                . "end_customer6,"
                . "end_customer7,"
                . "end_customer8,"
                . "end_customer9,"
                . "end_customer10,xls_file,xls_sheet) "
                . "VALUES "
                . "('" . $this->_cust_num . "',"
                . "'" . $this->_doc_no . "',"
                . "'" . $this->_po_num . "',"
                . "CONVERT(datetime,'" . $this->_po_date . "'),"
                . "" . $this->_ship_to . ","
                . "'" . str_replace(",", " ", $this->_ship_to_text) . "',"
                . "'" . $this->_attn . "',"
                . "'" . $this->_spec . "',"
                . "'" . $this->_terms . "',"
                . "'" . $this->_remark . "',"
                . "CONVERT(datetime,'$date'),"
                . "CONVERT(smalldatetime,'$dateTime'),"
                . "1,"
                . "$user_id,"
                . "'P',"
                . "'" . $this->_ShipExFrom . "',"
                . "'" . $this->_ShipExTo . "',"
                . "'" . $this->_endcust1 . "',"
                . "'" . $this->_endcust2 . "',"
                . "'" . $this->_endcust3 . "',"
                . "'" . $this->_endcust4 . "',"
                . "'" . $this->_endcust5 . "',"
                . "'" . $this->_endcust6 . "',"
                . "'" . $this->_endcust7 . "',"
                . "'" . $this->_endcust8 . "',"
                . "'" . $this->_endcust9 . "',"
                . "'" . $this->_endcust10 . "',"
                . "'" . $this->_xls_file . "',"
                . "'" . $this->_xls_sheet . "') ";
        $result = $cSql->IsUpDel($this->StrConn, $sql);


        return $result;
    }

    function ChangeStatus() {
        $date = date("Y-m-d");
        $dateTime = date("Y-m-d H:i:s");
        $cSql = new SqlSrv();
        $sql = "UPDATE SO_Order_head SET status =  '" . $this->_status . "' , modified_date = '$dateTime' WHERE doc_no = '" . $this->_doc_no . "'  ";
        $result = $cSql->IsUpDel($this->StrConn, $sql);

        return $result;
    }

}
