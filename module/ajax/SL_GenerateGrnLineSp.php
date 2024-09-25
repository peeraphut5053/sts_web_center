<?php

foreach ($_GET as $key => $value) {
    $$key = trim($value);
}

foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        // ถ้าเป็น array ให้วนลูปอีกครั้งเพื่อ trim แต่ละ element
        foreach ($value as $subKey => $subValue) {
            $$key[$subKey] = trim($subValue);
        }
    } else {
        $$key = trim($value);
    }
}

include "./initial.php";
$ArrTempWidths = null;
$ArrTempThicks = null;

$ArrTempVend = $_POST["ArrTempVend"];
$ArrTempPoNum = $_POST["ArrTempPoNum"];
$ArrTempPoLine = $_POST["ArrTempPoLine"];
$ArrTempPoDate = $_POST["ArrTempPoDate"];
$ArrTempReference = $_POST["ArrTempReference"];
$ArrTempSno = $_POST["ArrTempSno"];
$ArrTempStsno = $_POST["ArrTempStsno"];
$ArrTempGrnNum = $_POST["ArrTempGrnNum"];
$ArrTempWeight = $_POST["ArrTempWeight"];
$ArrTempWeights = $_POST["ArrTempWeights"];
$ArrLotNumber = $_POST["ArrLotNumber"];
$ArrTempTransDate = $_POST["ArrTempTransDate"];
$ArrTempCoilNo = $_POST["ArrTempCoilNo"];
$ArrTempWidths = $_POST["ArrTempWidths"];
$ArrTempThicks = $_POST["ArrTempThicks"];


$errText = "";
$ret = "";
$x = count($ArrTempVend);
for ($i = 0; $i < $x; $i++) {
    $tLot = trim($ArrLotNumber[$i]);
    $Lot = new Lot();
    $CheckLot = $Lot->CheckLotDuplicate($tLot);

    $tlot = $recVDate = strtotime($ArrTempPoDate[$i]);

    $recVDate_Conv = date('Y-m-d', $recVDate);

    $transDateRaw = strtotime($ArrTempTransDate[$i]);
    $transDateConv = date('Y-m-d', $transDateRaw);

    $ExpDateRaw = strtotime($ArrTempTransDate[$i] . ' +90 day');
    $ExpDateConv = date('Y-m-d', $ExpDateRaw);

    //========= Map GRN and Gen Lot ===========//
    $sql = "EXEC STS_GenerateGrnLineSp  ";
    $sql = $sql . "  @VendNum = '" . $ArrTempVend[$i] . "' ";
    $sql = $sql . " ,@GrnNum='" . $ArrTempGrnNum[$i] . "' ";
    $sql = $sql . " ,@PoNum ='" . $ArrTempPoNum[$i] . "' ";
    $sql = $sql . " ,@PoLine=" . $ArrTempPoLine[$i] . "  ";
    //$sql = $sql . " ,@PoRelease='" . $ArrTempReference[$i] . "' ";
    //PO Release in SP = Smallint 
    $sql = $sql . " ,@PoRelease=0 ";
    $sql = $sql . " ,@RcvdDate ='" . $recVDate_Conv . "' ";
    $sql = $sql . " ,@DateSeq =''  ";
    $sql = $sql . " ,@FromPo = 1";
    $sql = $sql . " ,@uf_lot = '$CheckLot'";
    $sql = $sql . " ,@uf_tag_status = 0";
    $sql = $sql . " ,@uf_trans = '" . $ArrTempCoilNo[$i] . "'";
    $sql = $sql . " ,@uf_weight = " . $ArrTempWeight[$i] . "";
    $sql = $sql . " ,@Uf_act_weight = " . $ArrTempWeights[$i] . "";
    $sql = $sql . " ,@Uf_act_width = " . $ArrTempWidths[$i] . "";
    $sql = $sql . " ,@uf_act_tickness = " . $ArrTempThicks[$i] . "";
    $sql = $sql . " ,@lot_trans_date = '$transDateConv'";
    $sql = $sql . " ,@lot_exp_date = '$ExpDateConv'";
    $sql = $sql . " ,@Infobar ='' ";
    //============================================//
    //\\/\/\/\\\\\/\\\///\\\ Disable Trigger \\\////\\\\////\\\\////\\\///\\\
    $DisableTriggerIns = sqlsrv_query($ConnSL, "DISABLE Trigger grn_line_mst.grn_line_mstIup ON grn_line_mst ");
    $DisableTriggerDel = sqlsrv_query($ConnSL, "DISABLE Trigger grn_line_mst.grn_line_mstDel ON grn_line_mst ");
    ////\\\\////\\\\////\\\\\/////\\\\\/\\/\/\/\/\/////\\\\////\\\////\\\///\   
    $stmt = sqlsrv_query($ConnSL, $sql);
    if ((!$DisableTriggerIns) || (!$DisableTriggerDel)) {
        $errText = $errText . "---------------------------\n > Trigger Disable Fail .. \n -Data can not upload ";
    } else {
        if (!$stmt) {
            $errText = $errText . "---------------------------\n > SyteLine Stored Procedure Error  \n";
            if (($errors = sqlsrv_errors() ) != null) {
                foreach ($errors as $error) {
                    $errText = $errText . ">SQLSTATE: " . $error['SQLSTATE'] . "\n";
                    $errText = $errText . ">Code: " . $error['code'] . " \n";
                    $errText = $errText . ">Message: " . $error['message'] . " \n";
                }
            }
        } else {
            ///\\\\////\\\\///\\\\////\\ Update PO QC ////\\\\\////\\\\////\\\
            $POQC = new PO_QC();
            $POQC->setConn($var);
            $POQC->_po_num = $ArrTempPoNum[$i];
            $POQC->_sts_no = $ArrTempStsno[$i];
            $POQC->_grn_num = $ArrTempGrnNum[$i];
            $POQC->_po_line = $ArrTempPoLine[$i];
            $POQC->_sl_lot = $CheckLot;
            $POQC->_sl_trans = $ArrTempTransDate[$i];
            $POQC->Uploaded();

            //\\/\/\/\\\\\/\\\///\\ Enable Trigger \\\////\\\\////\\\\////\\\///\\\
            $EnableTriggerIns = sqlsrv_query($ConnSL, "ENABLE Trigger grn_line_mst.grn_line_mstIup ON grn_line_mst ");
            $EnableTriggerDel = sqlsrv_query($ConnSL, "ENABLE Trigger grn_line_mst.grn_line_mstDel ON grn_line_mst ");
            ////\\\\////\\\\////\\\\\/////\\\\\/\\/\/\/\/\/////\\\\////\\\////\\\///\
            $errText = "Upload to SyteLine complete";
        }
    }
}
echo $errText;

sqlsrv_close($ConnSL);
