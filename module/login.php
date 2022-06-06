
<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
  // include "./initial.php";
$temp->setReplace("{crumb}", "");
$temp->setReplace("{content}", $temp->getTemplate("./template/login.html"));

?>
