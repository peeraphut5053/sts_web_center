<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
$toDay = date('Y-m-d');
$Last30Days = date('Y-m-d', strtotime('-30 days', strtotime($toDay)));


$temp->setReplace("{crumb}", "");
$temp->setReplace("{pagename}", "Lists");
$temp->setReplace("{content}", $temp->getTemplate("./template/MTG_load_wl_to_grn.html"));
$temp->setReplace("{StartDate}", $Last30Days);
$temp->setReplace("{EndDate}", $toDay);

// $temp->setReplace("{ConSlVal}", $ConnSL);
