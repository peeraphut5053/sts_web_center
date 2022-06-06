<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../../initial.php";
set_time_limit(0);
ini_set('memory_limit', '200M');

if ($load == "ajax_account_by_reason") {

    $ReasonCode = $_POST["ReasonCode"];
    $SwitchClass = $_POST["SwitchClass"];
    $filter = " WHERE reason_class ='$SwitchClass' AND  reason_code ='$ReasonCode'  ";
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Reason = new Misc();
    $Reason->setConn($ConnSL);

    $Reasons = $Reason->GetReason($filter);
    $CM = null;


    echo json_encode($Reasons);
}
if ($load == "ajax_account") {

    $Acct = $_POST["Acct"];

    $filter = " WHERE acct = '$Acct'  ";
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Acct = new Misc();
    $Acct->setConn($ConnSL);

    $Accts = $Acct->GetChart($filter);
    $CM = null;


    echo json_encode($Accts);
}
if ($load == "ajax_get_tag") {

    $tag_id = $_POST["tag_id"];

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Tag = new Misc();
    $Tag->setConn($ConnSL);
    $Tags = $Tag->GetTagInfo($tag_id);
    $CM = null;
    echo json_encode($Tags);
}
if ($load == "ajax_loc_lot_by_item") {

    $item = $_POST["item"];
    $SwitchSearch = $_POST["SwitchSearch"];
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Arr = new Misc();
    $Arr->setConn($ConnSL);
    $Arrs = array();
    if ($SwitchSearch == "loc") {
        $Arrs = $Arr->GetlocByItem($item);
    } else {
        $Arrs = $Arr->GetlotByItem($item);
    }

    $CM = null;


    echo json_encode($Arrs);
}
if ($load == "ajaxLocLotByItem") {

    $item = $_POST["item"];
    $SwitchSearch = $_POST["SwitchSearch"];
    $DefVal = $_POST["DefVal"];

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Arr = new Misc();
    $Arr->setConn($ConnSL);
    $Arrs = array();
    $options = "<option value=''></option>";
    $selected = "";

    if ($SwitchSearch == "loc") {
        $Arrs = $Arr->GetlocByItem($item);
    } else {
        $Arrs = $Arr->GetlotByItem($item);
    }
    for ($i = 0; $i <= count($Arrs) - 1; $i++) {
        $selected = "";
        if (!$DefVal) {
            if ($i == 0) {
                $selected = "selected";
            }
        } else {
            $DefVal == $Arrs[$i][$SwitchSearch] ? $selected = "selected" : $selected = "";
        }

        $options .= "<option $selected value='" . $Arrs[$i][$SwitchSearch] . "'>" . $Arrs[$i][$SwitchSearch] . " : " . $Arrs[$i]["qty"] . "</option>";
    }
    $CM = null;


    echo $options;
}

if ($load == "ajaxCheckLotQtyMove") {
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Arr = new Misc();
    $Arr->setConn($ConnSL);
    $Result = false;
    $Item = $_POST["Item"];
    $QtyMove = (float) $_POST["QtyMove"];
    $Lot = $_POST["Lot"];
    $Arrs = array();

    $Arrs = $Arr->GetlotByItem($Item);
    $QtyLot = (float) $Arrs[0]["qty"];
    if ($QtyLot >= $QtyMove) {
        $Result = true;
    } else {
        $Result = false;
    }
    $CM = null;


    echo $Result;
}
if ($load == "ajaxGetLotByLocItem") {

    $item = $_POST["item"];
    $loc = $_POST["loc"];
    $DefVal = $_POST["DefVal"];
    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Arr = new Misc();
    $Arr->setConn($ConnSL);

    $Arrs = $Arr->GetlotByItemAndLoc($item, $loc);
    for ($i = 0; $i <= count($Arrs) - 1; $i++) {
        if (!$DefVal) {
            if ($i == 0) {
                $selected = "selected";
            }
        } else {
            $DefVal == $Arrs[$i][$SwitchSearch] ? $selected = "selected" : $selected = "";
        }

        $options .= "<option $selected value='" . $Arrs[$i][$SwitchSearch] . "'>" . $Arrs[$i][$SwitchSearch] . " : " . $Arrs[$i]["qty"] . "</option>";
    }

    $CM = null;


    echo json_encode($Arrs);
}

if ($load == "ajax_get_lot_by_loc_item") {

    $item = $_POST["item"];
    $loc = $_POST["loc"];

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Arr = new Misc();
    $Arr->setConn($ConnSL);
    $Arrs = $Arr->GetlotByItemAndLoc($item, $loc);
    $CM = null;


    echo json_encode($Arrs);
}

if ($load == "ajax_gen_new_lot") {

    $item = $_POST["item"];


    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Arr = new Misc();
    $Arr->setConn($ConnSL);
    $Arr->spItem = $item;
    $lot = $Arr->GenNewLot();
    $CM = null;


    echo $lot;
}



if ($load == "ajax_process_misc_issue") {
    $spTransDate = $_POST["spTransDate"];
    $spItem = $_POST["spItem"];
    $spItem_UM = $_POST["spItem_UM"];
    $spWhse = $_POST["spWhse"];
    $spLoc = $_POST["spLoc"];
    $spLot = $_POST["spLot"];
    $spDocNum = $_POST["spDocNum"];
    $spQtyIssuse = $_POST["spQtyIssuse"];
    $spReasonCode = $_POST["spReasonCode"];
    $spAcct = $_POST["spAcct"];


    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Arr = new Misc();
    $Arr->setConn($ConnSL);
    $Arr->spTransDate = $spTransDate;
    $Arr->spItem = $spItem;
    $Arr->spItem_UM = $spItem_UM;
    $Arr->spWhse = $spWhse;
    $Arr->spLoc = $spLoc;
    $Arr->spLot = $spLot;
    $Arr->spDocNum = $spDocNum;
    $Arr->spQtyIssuse = $spQtyIssuse;
    $Arr->spReasonCode = $spReasonCode;
    $Arr->spAcct = $spAcct;
    $result = $Arr->STS_MiscIssueWeb_Sp();


    $CM = null;


    echo $result;
}

if ($load == "ajax_process_misc_receipt") {
    $spTransDate = $_POST["spTransDate"];
    $spItem = $_POST["spItem"];
    $spItem_UM = $_POST["spItem_UM"];
    $spWhse = $_POST["spWhse"];
    $spLoc = $_POST["spLoc"];
    $spLot = $_POST["spLot"];
    $spDocNum = $_POST["spDocNum"];
    $spQtyIssuse = $_POST["spQtyIssuse"];
    $spReasonCode = $_POST["spReasonCode"];
    $spAcct = $_POST["spAcct"];
    $vGenLot = $_POST["vGenLot"];

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Arr = new Misc();
    $Arr->setConn($ConnSL);
    $Arr->spTransDate = $spTransDate;
    $Arr->spItem = $spItem;
    $Arr->spItem_UM = $spItem_UM;
    $Arr->spWhse = $spWhse;
    $Arr->spLoc = $spLoc;
    $Arr->spLot = $spLot;
    $Arr->spDocNum = $spDocNum;
    $Arr->spQtyIssuse = $spQtyIssuse;
    $Arr->spReasonCode = $spReasonCode;
    $Arr->spAcct = $spAcct;
    $Arr->spIsNewLot = $vGenLot;
    $result = $Arr->STS_MiscReceiptWeb_Sp();


    $CM = null;


    echo $result;
}

if ($load == "ajaxProcessQtyMove") {

    $spTransDate = $_POST["spTransDate"];
    $spItem = $_POST["spItem"];
    $spWhse = $_POST["spWhse"];
    $spLocFrom = $_POST["spLocFrom"];
    $spLocTo = $_POST["spLocTo"];
    $spLotFrom = $_POST["spLotFrom"];
    $spLotTo = $_POST["spLotTo"];
    $spDocNum = $_POST["spDocNum"];
    $spTagId = $_POST["spTagId"];
    $spQtyMove = $_POST["spQtyMove"];

    $CM = new CallModel();
    $CM->SyteLine_Models();
    $Arr = new Misc();
    $Arr->setConn($ConnSL);
    $Arr->spTransDate = $spTransDate;
    $Arr->spWhse = $spWhse;
    $Arr->spItem = $spItem;
    $Arr->spLocFrom = $spLocFrom;
    $Arr->spLocTo = $spLocTo;
    $Arr->spLotFrom = $spLotFrom;
    $Arr->spLotTo = $spLotTo;
    $Arr->spQtyMove = $spQtyMove;
    $Arr->spTagId = $spTagId;
    $Arr->spDocNum = $spDocNum;
    $result = $Arr->STS_MvPostSp_WEB();


    $CM = null;


    echo $result;
} 




