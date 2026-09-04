<?php

class CustomerComplaint {

    var $StrConn = "";
    var $StrConnSL = "";

    function setConn($c) {
        $this->StrConn = $c;
    }

    function setConnSL($c) {
        $this->StrConnSL = $c;
    }

    private function getActiveConn() {
        if ($this->StrConnSL) {
            return $this->StrConnSL;
        }
        return $this->StrConn;
    }

    function GetIssues() {
        $conn = $this->getActiveConn();
        if (!$conn) return array();

        $query = "SELECT id, issue FROM STS_custComplain_issue ORDER BY id ASC";
        $stmt = @sqlsrv_query($conn, $query);
        $list = array();
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $list[] = $row;
            }
        }
        return $list;
    }

    function GenerateDocNo() {
        $conn = $this->getActiveConn();
        $prefix = "CC" . date("ym"); // e.g. CC2609
        if (!$conn) return $prefix . "001";

        $query = "SELECT TOP 1 doc_no FROM STS_custComplain_hdr WHERE doc_no LIKE '$prefix%' ORDER BY doc_no DESC";
        $stmt = @sqlsrv_query($conn, $query);
        if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $lastNo = trim($row["doc_no"]);
            $seqStr = substr($lastNo, strlen($prefix));
            $seq = intval($seqStr) + 1;
            return $prefix . str_pad($seq, 3, "0", STR_PAD_LEFT);
        }
        return $prefix . "001";
    }

    function GetComplaints($fromDate = "", $toDate = "", $docNo = "", $invoice = "", $customer = "", $issue = "") {
        $conn = $this->getActiveConn();
        if (!$conn) return array();

        $query = "
            SELECT 
                h.doc_no,
                CONVERT(varchar(10), h.receivedDate, 120) as receivedDate,
                h.customer,
                h.end_user,
                h.product,
                h.invoice,
                h.PO,
                h.cost,
                h.paid,
                h.issue as issue_id,
                i.issue as issue_name,
                h.detail,
                CONVERT(varchar(19), h.createdate, 120) as createdate,
                CONVERT(varchar(19), h.updatedate, 120) as updatedate,
                h.createdby,
                h.updatedby,
                h.qc,
                h.prod,
                h.EX,
                h.remark_QC,
                h.remark_prod,
                (SELECT COUNT(1) FROM STS_custComplain_pic p WHERE p.DocNo = h.doc_no) as pic_count
            FROM STS_custComplain_hdr h
            LEFT JOIN STS_custComplain_issue i ON h.issue = i.id
            WHERE 1=1
        ";

        if (!empty($fromDate) && !empty($toDate)) {
            $safeFrom = str_replace("'", "''", $fromDate);
            $safeTo = str_replace("'", "''", $toDate);
            $query .= " AND h.receivedDate BETWEEN '$safeFrom' AND '$safeTo' ";
        }
        if (!empty($docNo)) {
            $safeDoc = str_replace("'", "''", $docNo);
            $query .= " AND h.doc_no LIKE '%$safeDoc%' ";
        }
        if (!empty($invoice)) {
            $safeInv = str_replace("'", "''", $invoice);
            $query .= " AND h.invoice LIKE '%$safeInv%' ";
        }
        if (!empty($customer)) {
            $safeCust = str_replace("'", "''", $customer);
            $query .= " AND h.customer LIKE '%$safeCust%' ";
        }
        if (!empty($issue)) {
            $safeIssue = intval($issue);
            $query .= " AND h.issue = $safeIssue ";
        }

        $query .= " ORDER BY h.receivedDate DESC, h.createdate DESC, h.doc_no DESC ";

        $stmt = @sqlsrv_query($conn, $query);
        $list = array();
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $list[] = $row;
            }
        }
        return $list;
    }

    function GetComplaintDetail($docNo) {
        $conn = $this->getActiveConn();
        if (!$conn || empty($docNo)) return null;

        $safeDoc = str_replace("'", "''", $docNo);
        $query = "
            SELECT 
                h.doc_no,
                CONVERT(varchar(10), h.receivedDate, 120) as receivedDate,
                h.customer,
                h.end_user,
                h.product,
                h.invoice,
                h.PO,
                h.cost,
                h.paid,
                h.issue as issue_id,
                i.issue as issue_name,
                h.detail,
                CONVERT(varchar(19), h.createdate, 120) as createdate,
                CONVERT(varchar(19), h.updatedate, 120) as updatedate,
                h.createdby,
                h.updatedby,
                h.qc,
                h.prod,
                h.EX,
                h.remark_QC,
                h.remark_prod
            FROM STS_custComplain_hdr h
            LEFT JOIN STS_custComplain_issue i ON h.issue = i.id
            WHERE h.doc_no = '$safeDoc'
        ";

        $stmt = @sqlsrv_query($conn, $query);
        if (!$stmt) return null;

        $hdr = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!$hdr) return null;

        // Fetch pictures
        $queryPics = "SELECT DocNo, path, createdby FROM STS_custComplain_pic WHERE DocNo = '$safeDoc'";
        $stmtPics = @sqlsrv_query($conn, $queryPics);
        $pictures = array();
        if ($stmtPics) {
            while ($pic = sqlsrv_fetch_array($stmtPics, SQLSRV_FETCH_ASSOC)) {
                $pictures[] = $pic;
            }
        }
        $hdr["pictures"] = $pictures;

        return $hdr;
    }

    function SaveComplaint($data, $user = "") {
        $conn = $this->getActiveConn();
        if (!$conn) return array("success" => false, "message" => "Database connection error");

        $docNo = !empty($data["doc_no"]) ? trim($data["doc_no"]) : "";
        $receivedDate = !empty($data["receivedDate"]) ? trim($data["receivedDate"]) : date("Y-m-d");
        $customer = !empty($data["customer"]) ? str_replace("'", "''", trim($data["customer"])) : "";
        $endUser = !empty($data["end_user"]) ? str_replace("'", "''", trim($data["end_user"])) : null;
        $product = !empty($data["product"]) ? str_replace("'", "''", trim($data["product"])) : "";
        $invoice = !empty($data["invoice"]) ? str_replace("'", "''", trim($data["invoice"])) : "";
        $po = !empty($data["PO"]) ? str_replace("'", "''", trim($data["PO"])) : "";
        $cost = isset($data["cost"]) && $data["cost"] !== "" ? floatval($data["cost"]) : "NULL";
        $paid = !empty($data["paid"]) ? str_replace("'", "''", trim($data["paid"])) : null;
        $issue = isset($data["issue"]) && $data["issue"] !== "" ? intval($data["issue"]) : "NULL";
        $detail = !empty($data["detail"]) ? str_replace("'", "''", trim($data["detail"])) : "";
        $safeUser = mb_substr(str_replace("'", "''", trim(!empty($user) ? $user : "SYSTEM")), 0, 12);

        // Check if doc exists
        $isExisting = false;
        if (!empty($docNo)) {
            $checkQuery = "SELECT doc_no FROM STS_custComplain_hdr WHERE doc_no = '$docNo'";
            $checkStmt = @sqlsrv_query($conn, $checkQuery);
            if ($checkStmt && sqlsrv_has_rows($checkStmt)) {
                $isExisting = true;
            }
        }

        if ($isExisting) {
            $sql = "
                UPDATE STS_custComplain_hdr SET
                    receivedDate = '$receivedDate',
                    customer = '$customer',
                    end_user = " . ($endUser ? "'$endUser'" : "NULL") . ",
                    product = '$product',
                    invoice = '$invoice',
                    PO = '$po',
                    cost = $cost,
                    paid = " . ($paid ? "'$paid'" : "NULL") . ",
                    issue = $issue,
                    detail = '$detail',
                    updatedate = GETDATE(),
                    updatedby = '$safeUser'
                WHERE doc_no = '$docNo'
            ";
            $res = @sqlsrv_query($conn, $sql);
            if ($res === false) {
                return array("success" => false, "message" => "Update failed");
            }
            return array("success" => true, "doc_no" => $docNo, "action" => "update");
        } else {
            if (empty($docNo)) {
                $docNo = $this->GenerateDocNo();
            }
            $sql = "
                INSERT INTO STS_custComplain_hdr (
                    doc_no, receivedDate, customer, end_user, product, invoice, PO,
                    cost, paid, issue, detail, createdate, updatedate, createdby, updatedby
                ) VALUES (
                    '$docNo', '$receivedDate', '$customer', " . ($endUser ? "'$endUser'" : "NULL") . ", '$product', '$invoice', '$po',
                    $cost, " . ($paid ? "'$paid'" : "NULL") . ", $issue, '$detail', GETDATE(), GETDATE(), '$safeUser', '$safeUser'
                )
            ";
            $res = @sqlsrv_query($conn, $sql);
            if ($res === false) {
                return array("success" => false, "message" => "Insert failed");
            }
            return array("success" => true, "doc_no" => $docNo, "action" => "insert");
        }
    }

    function UpdateReview($docNo, $qc, $prod, $EX, $remarkQC, $remarkProd, $user = "") {
        $conn = $this->getActiveConn();
        if (!$conn || empty($docNo)) return array("success" => false, "message" => "Invalid parameters");

        $safeDoc = str_replace("'", "''", $docNo);
        $safeQC = !empty($qc) ? "'" . str_replace("'", "''", $qc) . "'" : "NULL";
        $safeProd = !empty($prod) ? "'" . str_replace("'", "''", $prod) . "'" : "NULL";
        $safeEX = !empty($EX) ? "'" . str_replace("'", "''", $EX) . "'" : "NULL";
        $safeRemarkQC = !empty($remarkQC) ? "'" . str_replace("'", "''", $remarkQC) . "'" : "NULL";
        $safeRemarkProd = !empty($remarkProd) ? "'" . str_replace("'", "''", $remarkProd) . "'" : "NULL";
        $safeUser = mb_substr(str_replace("'", "''", trim(!empty($user) ? $user : "SYSTEM")), 0, 12);

        $sql = "
            UPDATE STS_custComplain_hdr SET
                qc = $safeQC,
                prod = $safeProd,
                EX = $safeEX,
                remark_QC = $safeRemarkQC,
                remark_prod = $safeRemarkProd,
                updatedate = GETDATE(),
                updatedby = '$safeUser'
            WHERE doc_no = '$safeDoc'
        ";

        $res = @sqlsrv_query($conn, $sql);
        if ($res === false) {
            return array("success" => false, "message" => "Update review failed");
        }
        return array("success" => true, "doc_no" => $docNo);
    }

    function AddPicture($docNo, $path, $user = "") {
        $conn = $this->getActiveConn();
        if (!$conn || empty($docNo) || empty($path)) return array("success" => false);

        $cleanPath = ltrim(trim($path), "/\\");
        $safeDoc = mb_substr(str_replace("'", "''", trim($docNo)), 0, 10);
        $safePath = mb_substr(str_replace("'", "''", $cleanPath), 0, 200);
        $safeUser = mb_substr(str_replace("'", "''", trim(!empty($user) ? $user : "SYSTEM")), 0, 12);

        $sql = "
            INSERT INTO STS_custComplain_pic (DocNo, path, createdby)
            VALUES ('$safeDoc', '$safePath', '$safeUser')
        ";

        $res = @sqlsrv_query($conn, $sql);
        return array("success" => ($res !== false));
    }

    function DeletePicture($docNo, $path) {
        $conn = $this->getActiveConn();
        if (!$conn || empty($docNo) || empty($path)) return array("success" => false);

        $safeDoc = str_replace("'", "''", $docNo);
        $safePath = str_replace("'", "''", $path);

        $sql = "DELETE FROM STS_custComplain_pic WHERE DocNo = '$safeDoc' AND path = '$safePath'";
        $res = @sqlsrv_query($conn, $sql);
        return array("success" => ($res !== false));
    }

    function DeleteComplaint($docNo) {
        $conn = $this->getActiveConn();
        if (!$conn || empty($docNo)) return array("success" => false, "message" => "DocNo required");

        $safeDoc = str_replace("'", "''", $docNo);

        $sqlPics = "DELETE FROM STS_custComplain_pic WHERE DocNo = '$safeDoc'";
        @sqlsrv_query($conn, $sqlPics);

        $sqlHdr = "DELETE FROM STS_custComplain_hdr WHERE doc_no = '$safeDoc'";
        $res = @sqlsrv_query($conn, $sqlHdr);

        return array("success" => ($res !== false), "doc_no" => $docNo);
    }

    function SearchInvoiceSL($invNum) {
        $conn = $this->getActiveConn();
        if (!$conn || empty($invNum)) return array();
        $safeInv = str_replace("'", "''", $invNum);

        $query = "
            SELECT TOP 10
                h.inv_num,
                CONVERT(varchar(10), h.inv_date, 120) as inv_date,
                h.cust_num,
                c.name as cust_name,
                h.cust_po as po_num,
                (
                    SELECT TOP 1 i.description 
                    FROM inv_item i 
                    WHERE i.inv_num = h.inv_num 
                    ORDER BY i.inv_line ASC
                ) as product_desc
            FROM inv_hdr h
            LEFT JOIN custaddr c ON h.cust_num = c.cust_num AND c.cust_seq = 0
            WHERE h.inv_num LIKE '%$safeInv%'
            ORDER BY h.inv_date DESC
        ";

        $stmt = @sqlsrv_query($conn, $query);
        $list = array();
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $list[] = $row;
            }
        }
        return $list;
    }
}
