<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}

$temp->setReplace("{content}", $temp->getTemplate("./template/WL/transfer.html"));


// $temp->setReplace("{ConSlVal}", $ConnWebAppSL);
