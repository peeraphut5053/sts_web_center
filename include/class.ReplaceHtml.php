<?php
class ReplaceHtml {
    
    private $mainDetail;

    // เปลี่ยนจาก function ReplaceHtml($filePath) เป็น __construct($filePath)
    public function __construct($filePath) {
        $this->mainDetail = $this->getTemplate($filePath);
    }

    public function setReplace($sign, $data) {
        $this->mainDetail = str_replace($sign, $data, $this->mainDetail);
    }    

    public function getReplace() {
        return $this->mainDetail;
    }

    public function getTemplate($filePath = "") {
        return implode('', file($filePath));                    
    } 
}
?>