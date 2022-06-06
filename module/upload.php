<?php

    if (0 < $_FILES['file']['error']) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    } else {
        $FullFilePath ='../uploads/' . $_FILES['file']['name'] ;
        if (file_exists($FullFilePath)){
            unlink($FullFilePath);
        }
        move_uploaded_file($_FILES['file']['tmp_name'],$FullFilePath );
    }

