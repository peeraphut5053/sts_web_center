<?php

class Production {

    var $StrConn = "";
    // public $_StartDate = "";
    // public $_EndDate = "";
    // public $_Acct = array();

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function GetDeptRework() {
        $query = "SELECT * FROM STS_rework_dept";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetItemRework() {
        $query = "select item, [description]
from item_mst
where [description] not like '%ยกเลิก%' and
    (item like 'wc%' or item like 'wr%' or item like 'fc%' 
  or item like 'fr%' or item like 'fl%')";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetReworkReason() {
        $query = "SELECT * FROM STS_rework_reason";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetDocRework() {
        $query = "SELECT DocNo FROM STS_rework_hdr";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function GetReworkByDoc($DocNo) {
        $query = "SELECT *, h.CreateDate as CreateH, h.Username as UserH, l.CreateDate as CreateL, l.Username as UserL FROM STS_rework_hdr h 
        LEFT JOIN STS_rework_line l ON h.DocNo = l.DocNo
        WHERE h.DocNo = '$DocNo'";
        $cSql = new SqlSrv();
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    Function CreateRework($due_rewrok, $user_req, $dep_req, $item_rework, $new_item_rework, $reason_rework, $detail_rework, $qty_rework, $username, $file_type, $remark, $wcS, $wcPn, $wcG, $wcM, $wcT, $wcPk) {
        $year = date('y');
        $month = date('m');
        $day = date('d');
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 DocNo FROM STS_rework_hdr WHERE DocNo LIKE 'RF$year$month$day%' ORDER BY DocNo DESC";
        $result = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($result, count($result) - 1, 1);

        if (count($result) > 0) {
            $row = $result[0];
            $lastDoc = $row['DocNo'];
            // ดึงเลขลำดับจากเลขเอกสารล่าสุด (W2407001 -> 001)
            $lastNumber = intval(substr($lastDoc, -2));
            $newNumber = $lastNumber + 1;
        } else {
            // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
            $newNumber = 1;
        }
        // สร้างเลขเอกสารใหม่ RF24082701
        $docNumber = sprintf("RF%s%02d%02d%02d", $year, $month, $day, $newNumber);
        $query = "INSERT INTO STS_rework_hdr (DocNo,RequireDate,UserReq,Username,Dept,Remark) OUTPUT INSERTED.* VALUES ('$docNumber','$due_rewrok','$user_req','$username','$dep_req','$remark')";
        $rs = $cSql->SqlQuery($this->StrConn, $query);
        $s = "SELECT TOP 1 seq FROM STS_rework_line WHERE DocNo = '$docNumber' ORDER BY DocNo DESC";
        $seq = $cSql->SqlQuery($this->StrConn, $s);
        array_splice($seq, count($seq) - 1, 1);

        if (count($seq) > 0) {
            $row = $seq[0];
            $lastSeq = $row['seq'];
            $newNumber = $lastSeq + 1;
        } else {
            // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
            $newNumber = 1;
        }
        $seq = $newNumber;
        $pic = $docNumber . '-' . sprintf('%03d', $seq) . '.' . $file_type;
        $query2 = "INSERT INTO STS_rework_line (DocNo,seq,Item,NewItem,Reason,Detail,Qty,pic,Username,wcS,wcPn,wcG,wcM,wcT,wcPk) OUTPUT INSERTED.* VALUES ('$docNumber','$seq','$item_rework','$new_item_rework','$reason_rework','$detail_rework','$qty_rework','$pic','$username' ,'$wcS','$wcPn','$wcG','$wcM','$wcT','$wcPk')";
        $response = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    Function AddNewItemRework($doc_rework, $item_rework, $new_item_rework, $reason_rework, $detail_rework, $qty_rework, $username, $file_type, $wcS, $wcPn, $wcG, $wcM, $wcT, $wcPk) {
        $s = "SELECT TOP 1 seq FROM STS_rework_line WHERE DocNo = '$doc_rework' ORDER BY seq DESC";
        $cSql = new SqlSrv();
        $seq = $cSql->SqlQuery($this->StrConn, $s);
        array_splice($seq, count($seq) - 1, 1);

        if (count($seq) > 0) {
            $row = $seq[0];
            $lastSeq = $row['seq'];
            $newNumber = $lastSeq + 1;
        } else {
            // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
            $newNumber = 1;
        }
        $seq = $newNumber;
        $pic = $doc_rework . '-' . sprintf('%03d', $seq) . '.' . $file_type;
        $query2 = "INSERT INTO STS_rework_line (DocNo,seq,Item,NewItem,Reason,Detail,Qty,pic,Username ,wcS,wcPn,wcG,wcM,wcT,wcPk) OUTPUT INSERTED.* VALUES ('$doc_rework','$seq','$item_rework','$new_item_rework','$reason_rework','$detail_rework','$qty_rework','$pic','$username' ,'$wcS','$wcPn','$wcG','$wcM','$wcT','$wcPk')";
        $response = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    Function UpdateRework($doc_rework, $seq, $due_rework, $user_req, $dep_req, $item_rework, $new_item_rework, $reason_rework, $detail_rework, $qty_rework, $remark, $wcS, $wcPn, $wcG, $wcM, $wcT, $wcPk) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_rework_hdr SET RequireDate = '$due_rework', UserReq = '$user_req', Dept = '$dep_req', Remark = '$remark' WHERE DocNo = '$doc_rework'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);

        $query2 = "UPDATE STS_rework_line SET Item = '$item_rework', NewItem = '$new_item_rework', Reason = '$reason_rework', Detail = '$detail_rework', Qty = '$qty_rework', wcS = '$wcS', wcPn = '$wcPn', wcG = '$wcG', wcM = '$wcM', wcT = '$wcT', wcPk = '$wcPk' Output INSERTED.* WHERE DocNo = '$doc_rework' AND seq = '$seq'";
        $response = $cSql->SqlQuery($this->StrConn, $query2);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    Function UpdateReworkPic($doc_rework, $seq, $pic) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_rework_line SET pic = '$pic' Output INSERTED.* WHERE DocNo = '$doc_rework' AND seq = '$seq'";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    Function GetReworkReport($doc_rework,$from_date,$to_date) {
        $wh = '';

        if ($from_date != '' && $to_date != '') {
            $wh = " and convert(date,hdr.createdate) between '$from_date' and '$to_date'";
        } 

        if ($doc_rework != '') {
            $wh = " and hdr.docno = '$doc_rework'";
        }

        $cSql = new SqlSrv();
        $query = "select hdr.docNo, dept.deptdesc, convert(date,hdr.createdate) as createdate, hdr.UserReq, hdr.requiredate
  , line.seq, line.item, line.Newitem, line.detail
  , rs.reasondesc, line.qty, line.pic
  , wc = case when line.wcS = 1 then 'ขูดตะเข็บ/ดัดตรง ' else '' end
      +  case when line.wcPn = 1 then 'พ่นสี ' else '' end
   +  case when line.wcG = 1 then 'เตาชุบ ' else '' end
   +  case when line.wcM = 1 then 'พิมพ์ตรา ' else '' end
   +  case when line.wcT = 1 then 'ต๊าปเกลียวและกรู๊ฟ ' else '' end
   +  case when line.wcPk = 1 then 'มัดท่อ' else '' end
 , line.stat
from STS_rework_hdr hdr
 inner join STS_rework_line line on hdr.docno = line.docno
 inner join STS_rework_reason rs on rs.reason = line.reason
 inner join STS_rework_dept dept on dept.dept = hdr.dept
where 1=1 $wh
order by hdr.docNo, line.seq";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    function SaveStatus($doc_no, $seq) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_rework_line SET stat = 1 Output INSERTED.* WHERE DocNo = '$doc_no' AND seq = '$seq'";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

}
