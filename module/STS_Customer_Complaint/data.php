<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include "../../initial.php";
require_once "../../models/_WebApp/CustomerComplaint.php";

$model = new CustomerComplaint();
$model->setConn($ConnSL);
$model->setConnSL($ConnSL);

$load = isset($_REQUEST['load']) ? trim($_REQUEST['load']) : '';

// Retrieve logged-in username with priority: Request parameter -> Session login_username -> Session user -> Session fullname -> SYSTEM
$currentUser = !empty($_POST['user']) 
    ? trim($_POST['user']) 
    : (!empty($_GET['user']) 
        ? trim($_GET['user']) 
        : (!empty($_SESSION['login_username']) 
            ? trim($_SESSION['login_username']) 
            : (!empty($_SESSION['user']) 
                ? trim($_SESSION['user']) 
                : (!empty($_SESSION['login_user_fullname']) 
                    ? trim($_SESSION['login_user_fullname']) 
                    : 'SYSTEM'))));

switch ($load) {
    case "get_issues":
        $issues = $model->GetIssues();
        echo json_encode(array("success" => true, "data" => $issues));
        break;

    case "load_complaints":
        $fromDate = isset($_REQUEST['fromDate']) ? trim($_REQUEST['fromDate']) : "";
        $toDate = isset($_REQUEST['toDate']) ? trim($_REQUEST['toDate']) : "";
        $docNo = isset($_REQUEST['docNo']) ? trim($_REQUEST['docNo']) : "";
        $invoice = isset($_REQUEST['invoice']) ? trim($_REQUEST['invoice']) : "";
        $customer = isset($_REQUEST['customer']) ? trim($_REQUEST['customer']) : "";
        $issue = isset($_REQUEST['issue']) ? trim($_REQUEST['issue']) : "";

        $data = $model->GetComplaints($fromDate, $toDate, $docNo, $invoice, $customer, $issue);
        echo json_encode(array("success" => true, "data" => $data));
        break;

    case "get_detail":
        $docNo = isset($_REQUEST['docNo']) ? trim($_REQUEST['docNo']) : "";
        if (empty($docNo)) {
            echo json_encode(array("success" => false, "message" => "DocNo is required"));
            break;
        }
        $detail = $model->GetComplaintDetail($docNo);
        if ($detail) {
            echo json_encode(array("success" => true, "data" => $detail));
        } else {
            echo json_encode(array("success" => false, "message" => "Not found"));
        }
        break;

    case "save_complaint":
        $docNo = isset($_POST["doc_no"]) ? trim($_POST["doc_no"]) : "";
        $receivedDate = isset($_POST["receivedDate"]) ? trim($_POST["receivedDate"]) : date("Y-m-d");
        $customer = isset($_POST["customer"]) ? trim($_POST["customer"]) : "";
        $endUser = isset($_POST["end_user"]) ? trim($_POST["end_user"]) : "";
        $product = isset($_POST["product"]) ? trim($_POST["product"]) : "";
        $invoice = isset($_POST["invoice"]) ? trim($_POST["invoice"]) : "";
        $po = isset($_POST["PO"]) ? trim($_POST["PO"]) : "";
        $cost = isset($_POST["cost"]) ? trim($_POST["cost"]) : "";
        $paid = isset($_POST["paid"]) ? trim($_POST["paid"]) : "";
        $issue = isset($_POST["issue"]) ? trim($_POST["issue"]) : "";
        $detail = isset($_POST["detail"]) ? trim($_POST["detail"]) : "";

        if (empty($customer) || empty($product) || empty($invoice) || empty($po)) {
            echo json_encode(array("success" => false, "message" => "กรุณากรอกข้อมูลที่จำเป็น (Customer, Product, Invoice, PO)"));
            break;
        }

        $res = $model->SaveComplaint(array(
            "doc_no" => $docNo,
            "receivedDate" => $receivedDate,
            "customer" => $customer,
            "end_user" => $endUser,
            "product" => $product,
            "invoice" => $invoice,
            "PO" => $po,
            "cost" => $cost,
            "paid" => $paid,
            "issue" => $issue,
            "detail" => $detail
        ), $currentUser);

        echo json_encode($res);
        break;

    case "update_review":
        $docNo = isset($_POST["doc_no"]) ? trim($_POST["doc_no"]) : "";
        $qc = isset($_POST["qc"]) ? trim($_POST["qc"]) : "";
        $prod = isset($_POST["prod"]) ? trim($_POST["prod"]) : "";
        $EX = isset($_POST["EX"]) ? trim($_POST["EX"]) : "";
        $remarkQC = isset($_POST["remark_QC"]) ? trim($_POST["remark_QC"]) : "";
        $remarkProd = isset($_POST["remark_prod"]) ? trim($_POST["remark_prod"]) : "";

        if (empty($docNo)) {
            echo json_encode(array("success" => false, "message" => "DocNo is required"));
            break;
        }

        $res = $model->UpdateReview($docNo, $qc, $prod, $EX, $remarkQC, $remarkProd, $currentUser);
        echo json_encode($res);
        break;

    case "upload_images":
        $docNo = isset($_POST["doc_no"]) ? trim($_POST["doc_no"]) : "";
        if (empty($docNo)) {
            echo json_encode(array("success" => false, "message" => "DocNo is required"));
            break;
        }

        $uploadDir = dirname(__DIR__, 2) . "/uploads/complaints/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedCount = 0;
        $fileErrors = array();

        if (isset($_FILES['files'])) {
            $files = $_FILES['files'];
            if (is_array($files['name'])) {
                foreach ($files['name'] as $key => $name) {
                    if (empty($name)) continue;
                    $err = $files['error'][$key];
                    if ($err === UPLOAD_ERR_OK) {
                        $tmpName = $files['tmp_name'][$key];
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (in_array($ext, array("jpg", "jpeg", "png", "gif", "webp", "pdf"))) {
                            $newFileName = $docNo . "_" . time() . "_" . $key . "." . $ext;
                            $targetPath = $uploadDir . $newFileName;
                            if (move_uploaded_file($tmpName, $targetPath)) {
                                $dbPath = "uploads/complaints/" . $newFileName;
                                $model->AddPicture($docNo, $dbPath, $currentUser);
                                $uploadedCount++;
                            } else {
                                $fileErrors[] = "Failed to move uploaded file: " . $name;
                            }
                        } else {
                            $fileErrors[] = "Invalid extension: " . $ext;
                        }
                    } else {
                        $fileErrors[] = "Upload error code: " . $err;
                    }
                }
            } else if (!empty($files['name'])) {
                $err = $files['error'];
                if ($err === UPLOAD_ERR_OK) {
                    $tmpName = $files['tmp_name'];
                    $name = $files['name'];
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (in_array($ext, array("jpg", "jpeg", "png", "gif", "webp", "pdf"))) {
                        $newFileName = $docNo . "_" . time() . "_0." . $ext;
                        $targetPath = $uploadDir . $newFileName;
                        if (move_uploaded_file($tmpName, $targetPath)) {
                            $dbPath = "uploads/complaints/" . $newFileName;
                            $model->AddPicture($docNo, $dbPath, $currentUser);
                            $uploadedCount++;
                        } else {
                            $fileErrors[] = "Failed to move uploaded file: " . $name;
                        }
                    } else {
                        $fileErrors[] = "Invalid extension: " . $ext;
                    }
                } else {
                    $fileErrors[] = "Upload error code: " . $err;
                }
            }
        }

        echo json_encode(array(
            "success" => ($uploadedCount > 0 || empty($fileErrors)),
            "uploaded" => $uploadedCount,
            "errors" => $fileErrors
        ));
        break;

    case "delete_picture":
        $docNo = isset($_POST["doc_no"]) ? trim($_POST["doc_no"]) : "";
        $path = isset($_POST["path"]) ? trim($_POST["path"]) : "";
        if (empty($docNo) || empty($path)) {
            echo json_encode(array("success" => false, "message" => "DocNo and path required"));
            break;
        }

        // Unlink physical file if exists
        $cleanPath = ltrim($path, '/\\');
        $fullPath = dirname(__DIR__, 2) . "/" . $cleanPath;
        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }

        $res = $model->DeletePicture($docNo, $path);
        echo json_encode($res);
        break;

    case "delete_complaint":
        $docNo = isset($_POST["doc_no"]) ? trim($_POST["doc_no"]) : "";
        if (empty($docNo)) {
            echo json_encode(array("success" => false, "message" => "DocNo is required"));
            break;
        }

        $rootDir = dirname(__DIR__, 2);
        $uploadDir = $rootDir . "/uploads/complaints/";

        // 1. Delete all physical picture files from DB records
        $detail = $model->GetComplaintDetail($docNo);
        if ($detail && !empty($detail['pictures'])) {
            foreach ($detail['pictures'] as $pic) {
                if (!empty($pic['path'])) {
                    $cleanPath = ltrim($pic['path'], '/\\');
                    $fullPath = $rootDir . "/" . $cleanPath;
                    if (file_exists($fullPath) && is_file($fullPath)) {
                        @unlink($fullPath);
                    }
                }
            }
        }

        // 2. Fallback: Clean up any files on disk matching $docNo
        if (!empty($docNo) && is_dir($uploadDir)) {
            $matchingFiles = glob($uploadDir . $docNo . "_*");
            if ($matchingFiles) {
                foreach ($matchingFiles as $f) {
                    if (file_exists($f) && is_file($f)) {
                        @unlink($f);
                    }
                }
            }
        }

        // 3. Delete database records
        $res = $model->DeleteComplaint($docNo);
        echo json_encode($res);
        break;

    case "search_invoice":
        $query = isset($_POST["query"]) ? trim($_POST["query"]) : (isset($_GET["query"]) ? trim($_GET["query"]) : "");
        $invoices = $model->SearchInvoiceSL($query);
        echo json_encode(array("success" => true, "data" => $invoices));
        break;

    default:
        echo json_encode(array("success" => false, "message" => "Invalid load action"));
        break;
}
