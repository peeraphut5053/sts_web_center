<?php

while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
include "../initial.php";
$temp = new ReplaceHtml("../../template/app_tag_photo_gallery/index.html");
echo $temp->getReplace();
sqlsrv_close($ConnSL);





$myFile = "testFile.txt";
$myFileLink = fopen($myFile, 'w') or die("can't open file");
fclose($myFileLink);


//delete_files('sts_1911261120011.jpg');
//function delete_files($target) {
//    $target = '../../template/app_tag_photo_gallery/img_tag/'.$target;
//    if (is_dir($target)) {
//        $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
//        foreach ($files as $file) {
//            delete_files($file);
//        }
//        rmdir($target);
//    } elseif (is_file($target)) {
//        unlink($target);
//    }
//    $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
//    print_r($files);
//}

//
//$myFile = "testFile.txt";
//$myFileLink = fopen($myFile, 'w') or die("can't open file");
//fclose($myFileLink);
//$flagDelete = unlink($myFile) or die("Couldn't delete file");
//if ($flagDelete) {
//    echo 'delete';
//} else {
//    echo 'file not found';
//}