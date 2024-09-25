<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    $$key = trim($value);
}

include "../../initial.php";

function ConvTransType($trans_type) {
    $result = "";
    if ($trans_type == "A") {
        $result = "Adjustment";
    } else if ($trans_type == "B") {
        $result = "CucleCount";
    } else if ($trans_type == "C") {
        $result = "Split/Merge";
    } else if ($trans_type == "D") {
        $result = "Scrap";
    } else if ($trans_type == "F") {
        $result = "Finish";
    } else if ($trans_type == "G") {
        $result = "Misc.Issue";
    } else if ($trans_type == "H") {
        $result = "Misc.Receipt";
    } else if ($trans_type == "I") {
        $result = "Issue/WIPChange";
    } else if ($trans_type == "L") {
        $result = "TransferLoss";
    } else if ($trans_type == "M") {
        $result = "StockMove";
    } else if ($trans_type == "N") {
        $result = "Labor/NextOperation";
    } else if ($trans_type == "O") {
        $result = "OtherCost";
    } else if ($trans_type == "P") {
        $result = "PhysicalInventory";
    } else if ($trans_type == "R") {
        $result = "Receipt";
    } else if ($trans_type == "S") {
        $result = "Ship";
    } else if ($trans_type == "T") {
        $result = "TransferOrder";
    } else if ($trans_type == "W") {
        $result = "Withdrawal/Return";
    }
    return $result;
}

function ConvRefType($ref_type) {
    $result = "";
    if ($ref_type == "C") {
        $result = "Project";
    } else if ($ref_type == "I") {
        // not sure
        $result = "Inventory";
    } else if ($ref_type == "J") {
        $result = "Job";
    } else if ($ref_type == "K") {
        //not sure 
        $result = "JIT Production";
    } else if ($ref_type == "O") {
        $result = "Customer Order";
    } else if ($ref_type == "P") {
        $result = "Purchase Order";
    } else if ($ref_type == "R") {
        $result = "RMA";
    } else if ($ref_type == "S") {
        $result = "Production Schedule";
    } else if ($ref_type == "T") {
        $result = "Transfer";
    } else if ($ref_type == "W") {
        $result = "Work Center";
    } else if ($ref_type == "F") {
        $result = "Service Order";
    }
    return $result;
}

if ($load == "form") {
    
} else if ($load == "ajax") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $MTL = new MaterialTransaction();
    $MTL->setConn($ConnSL);
    $MTL->_StartDate = $StartDate ; 
    $MTL->_EndDate = $EndDate  ;
    $MTL->_TransType = $TransType  ;
    $MTL->_RefType = $RefType  ;
    $MTLS = $MTL->GetRowsWithCond();
    echo json_encode($MTLS);
    $MTL=null;
    $CM=null;
//    $lines = "";
//    $i = 0;
//    $trans_date = "";
//    $item = "";
//    $qty = 0;
//    $cost = 0;
//    $matl_cost = 0;
//    $trans_type = "";
//    $ref_type = "";
//    $item_cost = 0;
//    $lbr_cost = 0;
//    $fovhd_cost = 0;
//    $vovhd_cost = 0;
//    $out_cost = 0;
//    $total_posted=0;
//    $TransType = "" ;
//    $RefType ="" ;
//    
//    if (count($MTLS) > 0) {
//
//        foreach ($MTLS as $ii => $rr) {
//            $i++;
//            $qty = number_format($rr["Qty"], 2);
//            $cost = number_format($rr["cost"], 2);
//            $matl_cost = number_format($rr["matl_cost"], 2);
//            $item_cost = number_format($rr["item_cost"], 2);
//            $lbr_cost = number_format($rr["lbr_cost"], 2);
//            $vovhd_cost = number_format($rr["vovhd_cost"], 2);
//            $fovhd_cost = number_format($rr["fovhd_cost"], 2);
//            $out_cost = number_format($rr["out_cost"], 2);
//            $total_posted= number_format($rr["TotalPost"], 2);
//            $lines = $lines . "<tr>"
//                    . "<td align='center' >$i</td>"
//                    . "<td align='left' >" . $rr["Trans_Num"] . "</td>"
//                    . "<td align='center' >" . $rr["TransDate"]->format("Y-m-d") . "</td>"
//                    . "<td align='center'>" . $rr["TransTypeConv"] . "</td>"
//                    . "<td align='center'>" . $rr["RefTypeConv"] . "</td>"
//                    . "<td align='center'>" . $rr["backflush"] . "</td>"
//                    . "<td align='center'>" . $rr["whse"] . "</td>"
//                    . "<td align='center'>" . $rr["Loc"] . "</td>"
//                    . "<td align='center'>" . $rr["lot"] . "</td>"
//                    . "<td align='right'>$qty</td>"
//                    . "<td align='center'>" . $rr["ItemUM"] . "</td>"
//                    . "<td align='center'>" . $rr["wc"] . "</td>"
//                    . "<td align='left'>" . $rr["ref_num"] . "</td>"
//                    . "<td align='center'>" . $rr["ref_line_suf"] . "</td>"
//                    . "<td align='center'>" . $rr["ref_release"] . "</td>"
//                    . "<td align='center'>" . $rr["ReasonCode"] . "</td>"
//                    . "<td align='left'>" . $rr["ReasonDesc"] . "</td>"
//                    . "<td align='left'>" . $rr["Item"] . "</td>"
//                    . "<td align='left'>" . $rr["ItemDesc"] . "</td>"
//                    . "<td align='right'>$matl_cost</td>"
//                    . "<td align='right'>$lbr_cost</td>"
//                    . "<td align='right'>$fovhd_cost</td>"
//                    . "<td align='right'>$vovhd_cost</td>"
//                    . "<td align='right'>$out_cost</td>"
//                    . "<td align='right'>$item_cost</td>"
//                    . "<td align='center'>" . $rr["Type"] . "</td>"
//                    . "<td align='right'>$total_posted</td>"
//                    . "<td align='left'>" . $rr["document_num"] . "</td>"
//                    . "<td align='left'>" . $rr["CreatedBy"] . "</td>"
//                    . "<td align='left'>" . $rr["cost_code"] . "</td>"
//                    . "<td align='center'>" . $rr["site_ref"] . "</td>"
//                    . "<td align='center'>" . $rr["emp_num"] . "</td>"
//                    . "<td align='center'>" . $rr["TransDate"]->format("Y-m-d H:i:s")  . "</td>"
//                    . "</tr>";
//        }
//    } else {
//        $lines = "<tr><td colspan='13' align='center'>..no data ..</td></tr>";
//    }
//    echo $lines;
//    $MTLS = null;
//    $MTL = null;
//    $CM = null;
} 