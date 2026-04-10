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
        $query = "UPDATE STS_rework_hdr SET RequireDate = '$due_rework', UserReq = '$user_req', Dept = '$dep_req', Remark = '$remark', UpdateDate = GETDATE() WHERE DocNo = '$doc_rework'";
        $rs = $cSql->SqlQuery($this->StrConn, $query);

        $query2 = "UPDATE STS_rework_line SET Item = '$item_rework', NewItem = '$new_item_rework', Reason = '$reason_rework', Detail = '$detail_rework', Qty = '$qty_rework', wcS = '$wcS', wcPn = '$wcPn', wcG = '$wcG', wcM = '$wcM', wcT = '$wcT', wcPk = '$wcPk', UpdateDate = GETDATE() Output INSERTED.* WHERE DocNo = '$doc_rework' AND seq = '$seq'";
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

    function UpdateReworkHdr($doc_rework, $due_rework, $user_req, $dep_req, $remark) {
        $cSql = new SqlSrv();
        $query = "UPDATE STS_rework_hdr SET RequireDate = '$due_rework', UserReq = '$user_req', Dept = '$dep_req', Remark = '$remark', UpdateDate = GETDATE() WHERE DocNo = '$doc_rework'";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    function GetJobReport($job, $wc,$item,$status) {
        $cSql = new SqlSrv();
        $query = "EXEC [dbo].[STS_ProductionRpt]
    @job = N'$job',
    @wc = N'$wc',
    @item = N'$item',
    @status = N'$status'";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    function GetWC() {
        $cSql = new SqlSrv();
        $query = "select wc from wc_mst where wc like 'p2%' or wc like 'w2%' order by wc";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    function GetItemPlanning() {
        $cSql = new SqlSrv();
        $query = "select item from item_mst where (item like 'F%N' or item like 'W%N') and item not like 'WS%' order by item";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    function GetItemInfoPlanning($item) {
        $cSql = new SqlSrv();
        $query = "
            select top 1
                uf_TypeEnd as [type],
                uf_NPS as size,
                uf_class as spec,
                Uf_Grade as grade,
                Uf_Schedule as sch,
                Uf_thickness as thick,
                Uf_length as [length],
                Uf_pack as [pack],
                Uf_length_FT as length_m,
                unit_weight as weight_pcs,
                (select top 1 CASE WHEN jr.run_basis_mch  = 'P' 
                             and js.run_mch_hrs <> 0 
                      THEN  js.pcs_per_mch_hr
                      ELSE js.run_mch_hrs 
                      END
                 from item_mst item2 
                 inner join jobroute_mst jr on jr.job = item2.job and jr.suffix = item2.suffix
                 inner join jrt_sch_mst js on js.job = item2.job and js.suffix = item2.suffix
                 where item2.item = item_mst.item
                ) as pcs_hr
            from item_mst
            where item = '$item'
        ";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    function SavePlanning($dataArray) {
        $cSql = new SqlSrv();
        $successCount = 0;
        foreach($dataArray as $row) {
            $year = isset($row['year']) ? str_replace("'", "''", $row['year']) : '';
            $month = isset($row['month']) ? str_replace("'", "''", $row['month']) : '';
            $wc = isset($row['wc']) ? str_replace("'", "''", $row['wc']) : '';
            $item = isset($row['item']) ? str_replace("'", "''", $row['item']) : '';
            $job = isset($row['job']) ? str_replace("'", "''", $row['job']) : '';
            $qty = isset($row['qty']) ? str_replace("'", "''", $row['qty']) : '';

            $old_year = isset($row['old_year']) ? str_replace("'", "''", $row['old_year']) : '';
            $old_month = isset($row['old_month']) ? str_replace("'", "''", $row['old_month']) : '';
            $old_wc = isset($row['old_wc']) ? str_replace("'", "''", $row['old_wc']) : '';
            $old_item = isset($row['old_item']) ? str_replace("'", "''", $row['old_item']) : '';
            $old_job = isset($row['old_job']) ? str_replace("'", "''", $row['old_job']) : '';

            if ($old_item != '' && $old_wc != '') {
                // If there's an old value, we update the existing row directly (handles PK change)
                $query = "UPDATE STS_Prod_policy SET [year] = '$year', [month] = '$month', wc = '$wc', Item = '$item', job = '$job', qty = '$qty', createdate = GETDATE() WHERE [year] = '$old_year' AND [month] = '$old_month' AND wc = '$old_wc' AND Item = '$old_item' AND job = '$old_job'";
                $cSql->SqlQuery($this->StrConn, $query);
            } else {
                // Check if existing record
                $checkQuery = "SELECT * FROM STS_Prod_policy WHERE [year] = '$year' AND [month] = '$month' AND wc = '$wc' AND Item = '$item'";
                $check = $cSql->SqlQuery($this->StrConn, $checkQuery);
                if (is_array($check) && count($check) > 1) {
                    $query = "UPDATE STS_Prod_policy SET job = '$job', qty = '$qty', createdate = GETDATE() WHERE [year] = '$year' AND [month] = '$month' AND wc = '$wc' AND Item = '$item'";
                    $cSql->SqlQuery($this->StrConn, $query);
                } else {
                    $query = "INSERT INTO STS_Prod_policy ([year], [month], wc, Item, job, qty, createdate) VALUES ('$year', '$month', '$wc', '$item', '$job', '$qty', GETDATE())";
                    $cSql->SqlQuery($this->StrConn, $query);
                }
            }
            $successCount++;
        }
        return $successCount;
    }

    function GetSavedPlanning($year, $month, $wc = '') {
        $cSql = new SqlSrv();
        
        $wcFilter = "";
        if ($wc !== '') {
            $wc = str_replace("'", "''", $wc);
            $wcFilter = " AND p.wc = '$wc'";
        }

        $query = "
            select p.[year], p.[month], p.wc, p.Item as item, p.job
, p.qty, i.uf_TypeEnd as [type], i.uf_NPS as size, i.uf_class as spec
, i.Uf_Grade as grade, i.Uf_Schedule as sch, i.Uf_thickness as thick
, i.Uf_length as [length], i.Uf_pack as [pack], i.Uf_length_FT as length_m, i.unit_weight as weight_pcs
, (select top 1 CASE WHEN jr.run_basis_mch = 'P' and js.run_mch_hrs <> 0 THEN js.pcs_per_mch_hr ELSE js.run_mch_hrs END 
  from item_mst item2 inner join jobroute_mst jr on jr.job = item2.job and jr.suffix = item2.suffix 
  inner join jrt_sch_mst js on js.job = item2.job and js.suffix = item2.suffix where item2.item = p.Item ) as pcs_hr 
, job.qty_complete
, multiplier = case when substring(i.item,22,1) = 'M' then 1 when substring(i.item,22,1) = 'F' then 0.3048 end * convert(decimal(10,4),isnull(i.uf_length_FT,0))
from STS_Prod_policy p left join item_mst i on p.Item = i.item left join jobitem_mst job on job.job = p.job and job.item = p.item
where p.[year] = '$year' and p.[month] = '$month' $wcFilter order by p.wc, p.Item
        ";
      
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    function GetReport($year, $month) {
        $cSql = new SqlSrv();
        $query = "
           SELECT * FROM (
select item.uf_market, pol.wc, [weight] = isnull(pol.qty,0) * item.unit_weight / 1000 
from STS_Prod_policy pol inner join item_mst item on item.item = pol.item
where [year] = $year and [month] = $month ) AS SourceTable
PIVOT (sum([weight]) for wc in ([P2FM01],[P2FM05],[P2FM06],[P2FM08],[P2FM09],[P2FM10],[W2FM02],[W2FM04],[W2FM07],[W2FM11],[W2FMC1])) AS PivotTable;
        ";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }
    
    function GetTarget($year, $month) {
        $cSql = new SqlSrv();
        $query = "
            SELECT [year], [month], wc, time, [weight]
            FROM STS_Prod_policy_weight
            WHERE [year] = '$year' AND [month] = '$month'
            ORDER BY wc
        ";
        $response = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($response, count($response) - 1, 1);
        return $response;
    }

    function SaveTarget($data) {
        $cSql = new SqlSrv();
        
        foreach($data as $row) {
            $year = $row['year'];
            $month = $row['month'];
            $wc = $row['wc'];
            $time = $row['time'];
            $weight = $row['weight'];
            
            $year = str_replace("'", "''", $year);
            $month = str_replace("'", "''", $month);
            $wc = str_replace("'", "''", $wc);
            $time = str_replace("'", "''", $time);
            $weight = str_replace("'", "''", $weight);
            
            $checkQry = "SELECT top 1 wc FROM STS_Prod_policy_weight WHERE [year] = '$year' AND [month] = '$month' AND wc = '$wc'";
            $checkRs = $cSql->SqlQuery($this->StrConn, $checkQry);
            array_splice($checkRs, count($checkRs) - 1, 1);
            $cnt = count($checkRs);
            
            if ($cnt > 0) {
                // UPDATE
                $query = "UPDATE STS_Prod_policy_weight SET [time] = '$time', [weight] = '$weight', createdate = GETDATE() WHERE [year] = '$year' AND [month] = '$month' AND wc = '$wc'";
                $cSql->SqlQuery($this->StrConn, $query);
                echo $query;
            } else {
                // INSERT
                $query = "INSERT INTO STS_Prod_policy_weight ([year], [month], wc, [time], [weight], createdate) VALUES ('$year', '$month', '$wc', '$time', '$weight', GETDATE())";
                $cSql->SqlQuery($this->StrConn, $query);
                echo $query;
            }
        }
        
        return array('status' => 'success');
    }

}
