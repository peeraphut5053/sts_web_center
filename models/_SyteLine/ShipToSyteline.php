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
        $query = "select distinct doc_num from sts_ex_booking where status = 'ดำเนินการ' order by doc_num desc";
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
  and isnull(addr.addr##2,addr.name) like '%[$customer]%'
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

     function GetReportContainerBookingConfirm($doc_num, $co_num, $cust_po, $cust_name, $city, $sts_po, $size, $remain, $ready) {
        $wh = '';
        if ($co_num !== "") {
            $wh = $wh . "and co_num = '$co_num' ";
        }
        if ($cust_po !== "") {
            $wh = $wh . "and cust_po = '$cust_po' ";
        }
        if ($cust_name !== "") {
            $wh = $wh . "and cust_name = '$cust_name' ";
        }
        if ($city !== "") {
            $wh = $wh . "and city = '$city' ";
        }
        if ($sts_po !== "") {
            $wh = $wh . "and sts_po = '$sts_po' ";
            # code...
        }
        if ($size !== "") {
            $wh = $wh . "and size like '$size%' ";
            # code...
        }

        if ($remain !== "") {
            $wh = $wh . "and qty_remain > 0 ";
            # code...
        }
        if ($ready !== "") {
            $wh = $wh . "and qty_ready > 0 ";
            # code...
        }

        $query = "select * 
from V_STS_EX_booking_line_cont 
where doc_num = '$doc_num' $wh";
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
        $query = "select distinct book.* , cont.end_cust , cont.city , do.do_num
from STS_EX_booking book
left join STS_EX_booking_line_cont cont on (cont.doc_num = book.doc_num and cont.co_num = book.co_num)
left join do_hdr_mst do on (do.uf_bookingNo = book.booking_num40 or do.uf_bookingNo = book.booking_num45) where book.doc_num  = '$doc_num'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function CreateContainerLine($doc_num, $co_num, $item, $end_cust, $city, $container_no, $bundle) {
        $query = "INSERT INTO STS_EX_booking_line_cont (doc_num, co_num, item, end_cust, city, cont_no, bundle) VALUES ('$doc_num', '$co_num', '$item', '$end_cust', '$city', '$container_no', '$bundle')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function UpdateContainerLine($doc_num, $co_num, $item, $container_no, $bundle) {
        $query = "UPDATE STS_EX_booking_line_cont SET bundle = '$bundle', updatedate = GETDATE() WHERE doc_num = '$doc_num' and co_num = '$co_num' and item = '$item' and cont_no = '$container_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function UpdateContainerPcs($doc_num, $co_num, $item, $container_no, $bundle) {
        $q = "UPDATE STS_EX_booking_line_cont SET bundle_pcs = null WHERE doc_num = '$doc_num' and co_num = '$co_num' and item = '$item'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $q);
        $query = "UPDATE STS_EX_booking_line_cont SET bundle_pcs = '$bundle', updatedate = GETDATE() WHERE doc_num = '$doc_num' and co_num = '$co_num' and item = '$item' and cont_no = '$container_no'";
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
    ,[total_weight] = convert(decimal(10,0),sum((coalesce(conv.conv_factor,item.uf_pack,1) * line.bundle * item.unit_weight * 1.003)  +
        isnull(((line.bundle_pcs - coalesce(conv.conv_factor,item.uf_pack,1)) * item.unit_weight * 1.003)  ,0)))
 , area = convert(decimal(10,2),sum(isnull(line.bundle,0) * isnull(item.pp_area,0) / 100))
from STS_EX_booking_line_cont line 
 inner join item_mst item on line.item = item.item
 left join co_mst co on line.co_num = co.co_num
 left join u_m_conv_mst conv on co.cust_num = conv.vend_num and line.item = conv.item
where line.cont_no is not null and line.bundle is not null and line.doc_num = '$doc_num'
group by line.doc_num ,line.cont_no
";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function GetReportBookingLine($doc_num, $co_num, $cust_po, $cust_name, $city, $sts_po) {
        $query = "EXEC [dbo].[sts_EX_booking_line]
  @co_num = '$co_num',
  @cust_po = '$cust_po',
  @cust_name = '$cust_name',
  @city = '$city',
  @sts_po = '$sts_po',
  @doc_num = '$doc_num'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

     function DeleteByDocNum($doc_num, $co_num) {
        $query = "DELETE FROM STS_EX_booking WHERE doc_num = '$doc_num' and co_num = '$co_num'";
        $query2 = "DELETE FROM STS_EX_booking_line_cont WHERE doc_num = '$doc_num' and co_num = '$co_num'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        $rs = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetReportLoadLift($booknum, $port, $cust) { 
         $query = "EXEC [dbo].[sts_EX_booking_summary]
  @booknum = '$booknum',
  @port = '$port',
  @cust = '$cust'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    function SavePickDate($doc_no, $val, $type) {
        $query = "UPDATE STS_EX_booking SET $type = '$val', updatedate = GETDATE() WHERE doc_num = '$doc_no'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
    
}
