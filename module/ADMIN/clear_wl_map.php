<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

//$CM = new CallModel();
//$CM->SyteLine_Models();
//$Location = new SlLocation();
//$Location->setConn($ConnSL);
//$Locations = $Location->GetRowsAll();
//
//$result = "";
//foreach ($Locations as $key => $value) {
//    $result = $result . "<option value='" . $value["loc"] . "'>" . $value["description"] . "</option>";
//}
//$CM = null;
//$Locations = null;
//$Location = null;
$temp->setReplace("{content}", $temp->getTemplate("./template/ADMIN/clear_wl_map.html"));
//$temp->setReplace("{options_warehouse}", $result);

// $temp->setReplace("{ConSlVal}", $ConnWebAppSL);
