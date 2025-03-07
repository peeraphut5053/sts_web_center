<?php

class Factory {

    var $StrConn = "";
    public $_StartDate = "";
    public $_EndDate = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    Function RPT_STS_factory_fix_report($TransactionDateStarting,$TransactionDateEnding,$unit1) {
        $cSql = new SqlSrv();

        $query = "exec STS_factory_fix_report @TransactionDateStarting = '$TransactionDateStarting' , @TransactionDateEnding= '$TransactionDateEnding' ,@unit1= '$unit1' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function RPT_STS_factory_fix_report_sub($ref,$acct_unit1) {
        $cSql = new SqlSrv();

        $query = "exec STS_factory_fix_report_sub @ref = '$ref', @unit1 = '$acct_unit1' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function Getunit1List() {
        $cSql = new SqlSrv();

        $query = "select distinct led.acct_unit1, unit1.[description] as unit1_description from ledger_mst led left join unitcd1_mst unit1 on led.acct_unit1=unit1.unit1 where led.ref like 'AP%' and led.acct like '5315%' ";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    Function RPT_General_ledger_report() {
        $StartDate = $this->_StartDate." 00:00:00" ;
        $EndDate = $this->_EndDate." 23:59:59" ;
        $cSql = new SqlSrv();
        $q = "SELECT 
        led.acct,
        chart.[description] AS acct_name,
        CONVERT(DATE, led.trans_date) AS trans_date,
        led.dom_amount,
        led.ref,
        led.vend_num,
        led.voucher
    FROM 
        ledger_mst led
    INNER JOIN 
        chart_mst chart ON chart.acct = led.acct
    WHERE 
        (led.ref LIKE '%IN%' OR led.ref LIKE '%CN%' OR led.ref LIKE '%DN%')
        AND CONVERT(DATE, led.trans_date) BETWEEN '$StartDate' AND '$EndDate'";
        $rs0 = $cSql->SqlQuery($this->StrConn, $q);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function InsertReportRepair($r_department, $r_name, $r_item, $remark, $detail_issue, $r_site,$issue_name) {
        $year = date('y');
        $cSql = new SqlSrv();
        $sql = "SELECT TOP 1 DocNo FROM STS_repair WHERE DocNo LIKE 'R$year%' ORDER BY DocNo DESC";
        $cSql = new SqlSrv();
        $result = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($result, count($result) - 1, 1);
 
        if (count($result) > 0) {
            $row = $result[0];
            $lastDoc = $row['DocNo'];
            // ดึงเลขลำดับจากเลขเอกสารล่าสุด (A2400001 -> 00001)
            $lastNumber = intval(substr($lastDoc, -5));
            $newNumber = $lastNumber + 1;
        } else {
            // ถ้าไม่มีเลขของปีนี้ เริ่มที่ 1
            $newNumber = 1;
        }
        // สร้างเลขเอกสารใหม่
        $docNumber = sprintf("R%s%05d", $year, $newNumber);
        $query = "INSERT INTO STS_repair (DocNo,approve,DateIssue,Dept,Remark1,Item,Username,DetailIssue, Site,IssueName) OUTPUT inserted.* VALUES ('$docNumber', 0, GETDATE(),'$r_department','$remark','$r_item','$r_name','$detail_issue','$r_site','$issue_name')";
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportRepair($StartDate, $EndDate, $doc_no, $types, $items) {
        $cSql = new SqlSrv();
        $query = "";

        if ($doc_no != "") {
            $query .= " AND DocNo = '$doc_no'";
        }
        if ($types != "") {
            if ($types == "open") {
                $query = $query . " and approve = 0";
            } else if ($types == "close") {
                $query = $query . " and approve = 1";
            } else {

            }
        }
        if ($items != "") {
            $query = $query . " AND Type = '$items'";
        }

        if ($StartDate != "" && $EndDate != "") {
            $query = $query . " AND FORMAT(DateIssue, 'yyyy-MM-dd') BETWEEN '$StartDate' AND '$EndDate'";
        }
        $sql = "select re.* , iss.issue, department = wc.[description]
from STS_repair re inner join STS_repair_issue iss
    on iss.issuenum = re.detailissue
    left join wc_mst wc on wc.wc = re.Dept where 1=1 $query";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function StartRepair($doc_no, $item, $status, $detail_repair, $repair_name, $remark2, $due_date) {
        $cSql = new SqlSrv();
        $sql = "UPDATE STS_repair SET Status = '$status', Type = '$item', DetailRepair = '$detail_repair', Repairname = '$repair_name', Remark2 = '$remark2', DueDate = '$due_date' WHERE DocNo = '$doc_no'";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function EndRepair($doc_no, $status, $detail_repair, $repair_name, $remark2) {
        $cSql = new SqlSrv();
        $sql = "UPDATE STS_repair SET Status = '$status', DateRepairEnd = GETDATE(), DetailRepair = '$detail_repair', Repairname = '$repair_name', Remark2 = '$remark2', approve = 1 WHERE DocNo = '$doc_no'";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDataDocNo($user) {
        $cSql = new SqlSrv();
        $sql = "select distinct DocNo
from STS_repair
where DocNo like 'R%' and Username = '$user'
order by DocNo desc";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function SearchDocNo($doc_no) {
        $cSql = new SqlSrv();
        $sql = "SELECT * FROM STS_repair WHERE DocNo = '$doc_no'";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function UpdateReportRepair($doc_no,$r_department, $r_item, $remark, $detail_issue,$time, $r_site,$issue_name) {

        $sql = "UPDATE STS_repair SET Dept = '$r_department', Item = '$r_item', Remark1 = '$remark', DetailIssue = '$detail_issue', Site = '$r_site', IssueName = '$issue_name' WHERE DocNo = '$doc_no'";

        if ($time == 'start_repair') {
            $sql = "UPDATE STS_repair SET Dept = '$r_department', Item = '$r_item', Remark1 = '$remark', DetailIssue = '$detail_issue', DateRepairStart = GETDATE(), Site = '$r_site', IssueName = '$issue_name' WHERE DocNo = '$doc_no'";
        }

        if ($time == 'end_repair') {
            $sql = "UPDATE STS_repair SET Dept = '$r_department', Item = '$r_item', Remark1 = '$remark', DetailIssue = '$detail_issue', DateRepairEnd = GETDATE(), Site = '$r_site', IssueName = '$issue_name', approve = 1 WHERE DocNo = '$doc_no'";
        }

        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetReportRepairV2($StartDate, $EndDate, $doc_no, $types, $items, $status, $dept) {
        $cSql = new SqlSrv();
        $query = "";

        if ($doc_no != "") {
            $query .= " AND DocNo = '$doc_no'";
        }
        if ($types != "") {
            if ($types == "open") {
                $query = $query . " and approve = 0";
            } else if ($types == "close") {
                $query = $query . " and approve = 1";
            } else {

            }
        }
        if ($items != "") {
            $query = $query . " AND Type = '$items'";
        }

        if ($status != "") {
            $query = $query . " AND Status = '$status'";
        }
        if ($dept != "") {
            $query = $query . " AND re.Dept = '$dept'";
        }

        if ($StartDate != "" && $EndDate != "") {
            $query = $query . " AND FORMAT(DateIssue, 'yyyy-MM-dd') BETWEEN '$StartDate' AND '$EndDate'";
        }

        $sql = "select re.* , iss.issue, department = wc.[description]
from STS_repair re inner join STS_repair_issue iss
    on iss.issuenum = re.detailissue
    left join wc_mst wc on wc.wc = re.Dept where 1=1 $query";
        $rs0 = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
    }

    function GetDepartment($site) {
        $cSql = new SqlSrv();
        $sql = "select wc,[description] 
from wc_mst
where [description] not like '%กลุ่ม%' and [description] <> 'ลบ'
  and [description] not like '%ยกเลิก%'
  and wc like 'P%'
union
select wc=wc, [description]=wc
from STS_repair_wc
where wc not like '%วังน้อย%'";

       if ($site == 'วังน้อย') {
           $sql = "select wc,[description] 
from wc_mst
where [description] not like '%กลุ่ม%' and [description] <> 'ลบ'
  and [description] not like '%ยกเลิก%'
  and wc like 'W%'
union
select wc=wc, [description]=wc
from STS_repair_wc
where wc not like '%ปู่เจ้า%'";
       }

        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function GetIssue() {
        $cSql = new SqlSrv();
        $sql = "select wc.wc, iss.* 
from STS_repair_issue iss 
 inner join STS_repair_issue_wc wc
 on iss.issuenum = wc.issuenum
order by wc, issuenum";
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function UploadImagesReportRepair($doc_no, $upload_path) {
        $cSql = new SqlSrv();
        $sql = "INSERT INTO STS_repair_pic (DocNo, path) VALUES ('$doc_no', '$upload_path')";
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }

    function SelectReportRepairImage($doc_no) {
        $cSql = new SqlSrv();
        $sql = "SELECT * FROM STS_repair_pic WHERE DocNo = '$doc_no'";
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    } 

    function DeleteReportRepairByDocNo($doc_no) {
        $cSql = new SqlSrv();
        $sql = "DELETE FROM STS_repair WHERE DocNo = '$doc_no'";
        $rs = $cSql->SqlQuery($this->StrConn, $sql);
        array_splice($rs, count($rs) - 1, 1);
        return $rs;
    }
}


