<?php

$zip = new ZipArchive;
if ($zip->open('test_new.zip', ZipArchive::CREATE) === TRUE) {
    $zip->addFile('test.txt');
    $zip->addFile('test2.txt');
    $zip->addFile('test3.txt');
    $zip->addFile('test4.txt');
    $zip->close();
}





$zipname = "test_new.zip";
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipname);
header('Content-Length: ' . filesize($zipname));
readfile($zipname);

unlink($zipname);