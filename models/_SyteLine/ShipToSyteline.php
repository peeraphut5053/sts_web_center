<?php

class CustomerAddrSyteline {


    var $StrConn = "";
    public $_item = "";
    public $_CustNum = "" ;
    function setConn($c) {
        $this->StrConn = $c;
    }
    function GetItemToDropdownByCustNum() {
        session_start();
        $CustNum = $_SESSION["login_link_cust_num"];
        $query = "SELECT * FROM custaddr_mst  where cust_num ='$CustNum' ";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        $returnArray = array();
        $tmpKey = "" ;
        $tempValue = "" ;
        $returnArray[""] = "" ;
        foreach ($rs0 as $index => $rows) {
            $tmpKey = $rows["cust_seq"];
            $tempValue = $rows["addr##1"]. " " .$rows["addr##2"]. " " .$rows["addr##3"]. " " .$rows["addr##4"];
            $returnArray[$tmpKey] = $tempValue;
        }
        return $returnArray;
    }

    function GetDocBookingList() {
        $query = "select distinct doc_num from sts_ex_booking";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GenerateDocNumber() {
        $year = date('y');
        $month = date('m');
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 doc_num FROM STS_EX_booking WHERE doc_num LIKE 'B$year$month%' ORDER BY doc_num DESC";
        $cSql = new SqlSrv();
        $result = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($result, count($result) - 1, 1);

        if (count($result) > 0) {
            $row = $result[0];
            $lastDoc = $row['doc_num'];
            // ดึงเลขลำดับจากเลขเอกสารล่าสุด (T2407001 -> 001)
            $lastNumber = intval(substr($lastDoc, -3));
            $newNumber = $lastNumber + 1;
        } else {
            // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
            $newNumber = 1;
        }
        // สร้างเลขเอกสารใหม่
        $docNumber = sprintf("B%s%02d%03d", $year, $month, $newNumber);
        return $docNumber;
    }

    function GetCityBooking() {
        $query = "select distinct addr.city
from co_mst co inner join custaddr_mst addr on co.cust_num = addr.cust_num and co.cust_seq = addr.cust_seq
where co.stat = 'O' and co.co_num like 'ex%'
order by addr.city";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetCustomerByCity($city) {
        $query = "select distinct name = isnull(addr.addr##2,addr.name)
from co_mst co inner join custaddr_mst addr on co.cust_num = addr.cust_num and co.cust_seq = addr.cust_seq
where co.stat = 'O' and co.co_num like 'ex%' and city = '$city'
order by isnull(addr.addr##2,addr.name)";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetCoNum($city,$customer) {
        $query = "select distinct co.co_num
from co_mst co inner join custaddr_mst addr on co.cust_num = addr.cust_num and co.cust_seq = addr.cust_seq
where co.stat = 'O' and co.co_num like 'ex%' and city = '$city'
  and isnull(addr.addr##2,addr.name) = '$customer'
order by co.co_num";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetReportContainerBooking($co_num,$cust_po,$cust_name,$city,$sts_po) {
        $query = "EXEC [dbo].[sts_EX_booking_hdr]
  @conum = '$co_num',
  @cust_po = '$cust_po',
  @cust_name = '$cust_name',
  @city = '$city',
  @sts_po = '$sts_po'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function CreateContainerBooking($doc_num,$co_num,$container_40,$container_45,$booking_number40,$booking_number45,$booking_date,$status_booking) {
        $query = "INSERT INTO STS_EX_booking (doc_num, co_num, no_cont40, no_cont45, booking_num40, booking_num45, status) OUTPUT INSERTED.* VALUES ('$doc_num', '$co_num', '$container_40', '$container_45', '$booking_number40', '$booking_number45', '$status_booking')";

        if ($booking_date !== "") {
            $query = "INSERT INTO STS_EX_booking (doc_num, co_num, no_cont40, no_cont45, booking_num40, booking_num45, booking_date, status) OUTPUT INSERTED.* VALUES ('$doc_num', '$co_num', '$container_40', '$container_45', '$booking_number40', '$booking_number45', '$booking_date', '$status_booking')";
        }

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function GetReportContainerBookingConfirm($doc_num, $co_num, $cust_po, $cust_name, $city, $sts_po) {
        $query = "select bun.* 
    , [1-pcs] = pcs.[1], [2-pcs] = pcs.[2], [3-pcs] = pcs.[3], [4-pcs] = pcs.[4], [5-pcs] = pcs.[5], [6-pcs] = pcs.[6]
    , [7-pcs] = pcs.[7], [8-pcs] = pcs.[8], [9-pcs] = pcs.[9], [10-pcs] = pcs.[10], [11-pcs] = pcs.[11], [12-pcs] = pcs.[12]
    , [13-pcs] = pcs.[13], [14-pcs] = pcs.[14], [15-pcs] = pcs.[15], [16-pcs] = pcs.[16], [17-pcs] = pcs.[17], [18-pcs] = pcs.[18]
    , [19-pcs] = pcs.[19], [20-pcs] = pcs.[20], [21-pcs] = pcs.[21], [22-pcs] = pcs.[22], [23-pcs] = pcs.[23], [24-pcs] = pcs.[24]
    , [25-pcs] = pcs.[25], [26-pcs] = pcs.[26], [27-pcs] = pcs.[27], [28-pcs] = pcs.[28], [29-pcs] = pcs.[29], [30-pcs] = pcs.[30]
from V_STS_EX_booking_line_cont bun
  inner join V_STS_EX_booking_line_cont_pcs pcs on bun.co_num = pcs.co_num and bun.co_line = pcs.co_line and bun.doc_num = pcs.doc_num
where bun.doc_num = '$doc_num'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function UpdateContainerBooking($doc_num,$co_num,$container_40,$container_45,$booking_number40,$booking_number45,$booking_date,$status_booking, $old_co_num) {
        $query = "UPDATE STS_EX_booking SET co_num = '$co_num', no_cont40 = '$container_40', no_cont45 = '$container_45', booking_num40 = '$booking_number40', booking_num45 = '$booking_number45', booking_date = '$booking_date', status = '$status_booking', updatedate = GETDATE() OUTPUT INSERTED.* WHERE doc_num = '$doc_num' and co_num = '$old_co_num'";

        if ($booking_date == "") {
            $query = "UPDATE STS_EX_booking SET co_num = '$co_num', no_cont40 = '$container_40', no_cont45 = '$container_45', booking_num40 = '$booking_number40', booking_num45 = '$booking_number45', status = '$status_booking', updatedate = GETDATE() OUTPUT INSERTED.* WHERE doc_num = '$doc_num' and co_num = '$old_co_num'";
        }

        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        $sql = "SELECT * FROM STS_EX_booking WHERE doc_num = '$doc_num'";
        $rs2 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs2, count($rs2) - 1, 1);
        return $rs2;
    }

     function GetDataBookingByDocNum($doc_num) {
        $query = "select * from STS_EX_booking where doc_num = '$doc_num'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function CreateContainerLine($doc_num, $co_num, $co_line, $end_cust, $city, $container_no, $bundle) {
        $query = "INSERT INTO STS_EX_booking_line_cont (doc_num, co_num, co_line, end_cust, city, cont_no, bundle) VALUES ('$doc_num', '$co_num', '$co_line', '$end_cust', '$city', '$container_no', '$bundle')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function UpdateContainerLine($doc_num, $co_num, $co_line, $container_no, $bundle) {
        $query = "UPDATE STS_EX_booking_line_cont SET bundle = '$bundle' WHERE doc_num = '$doc_num' and co_num = '$co_num' and co_line = '$co_line' and cont_no = '$container_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function UpdateContainerPcs($doc_num, $co_num, $co_line, $container_no, $bundle) {
        $query = "UPDATE STS_EX_booking_line_cont SET bundle_pcs = '$bundle' WHERE doc_num = '$doc_num' and co_num = '$co_num' and co_line = '$co_line' and cont_no = '$container_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function GetContainerLine($doc_num, $co_num, $co_line) {
        $query = "select * from STS_EX_booking_line_cont where doc_num = '$doc_num' and co_num = '$co_num' and co_line = '$co_line'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function GetTotalWeightContainer($doc_num) {
        $query = "select  line.doc_num ,line.cont_no
 , [total_bundle] = sum(line.bundle)
    ,[total_weight] = convert(decimal(10,0),sum((item.uf_pack * line.bundle * item.unit_weight * 1.003)  +
        isnull(((line.bundle_pcs - item.uf_pack) * item.unit_weight * 1.003)  ,0)))
from STS_EX_booking_line_cont line 
 inner join coitem_mst coi on coi.co_num = line.co_num and coi.co_line = line.co_line
 inner join item_mst item on coi.item = item.item
where line.cont_no is not null and line.bundle is not null and line.doc_num = '$doc_num'
group by line.doc_num ,line.cont_no";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }


}
