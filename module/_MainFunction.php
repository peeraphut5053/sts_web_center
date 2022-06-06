<?php
while (list($key, $data) = each($_GET) OR list($key, $data) = each($_POST)) {
    ${$key} = trim($data);
}
//include "./initial.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    //put your code here
  
if($call_function=="ConvertPiecesToBundle") {    
    //need $size and $sched
        $result = 0;
        if ($sched == "10") {
            $size == "1/2" ? $result = 169 : $result = 0;
            $size == "3/4" ? $result = 127 : $result = 0;
            $size == "1" ? $result = 91 : $result = 0;
            $size == "1 1/4" ? $result = 61 : $result = 0;
            $size == "1 1/2" ? $result = 44 : $result = 0;
            $size == "2" ? $result = 37 : $result = 0;
            $size == "2 1/2" ? $result = 29 : $result = 0;
            $size == "3" ? $result = 24 : $result = 0;
            $size == "3 1/2" ? $result = 19 : $result = 0;
            $size == "4" ? $result = 19 : $result = 0;
            $size == "5" ? $result = 10 : $result = 0;
            $size == "6" ? $result = 10 : $result = 0;
            $size == "8" ? $result = 5 : $result = 0;
        } else if (($sched == "40") || ($sched == "80")) {
            $size == "1/2" ? $result = 120 : $result = 0;
            $size == "3/4" ? $result = 84 : $result = 0;
            $size == "1" ? $result = 60 : $result = 0;
            $size == "1 1/4" ? $result = 42 : $result = 0;
            $size == "1 1/2" ? $result = 36 : $result = 0;
            $size == "2" ? $result = 26 : $result = 0;
            $size == "2 1/2" ? $result = 18 : $result = 0;
            $size == "3" ? $result = 14 : $result = 0;
            $size == "3 1/2" ? $result = 12 : $result = 0;
            $size == "4" ? $result = 10 : $result = 0;
            $size == "5" ? $result = 7 : $result = 0;
            $size == "6" ? $result = 7 : $result = 0;
            $size == "8" ? $result = 5 : $result = 0;
        }
        echo $result ;
    }


