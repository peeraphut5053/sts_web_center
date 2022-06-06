<?php

        $keyword = $this->_keyword;
        $query = "SELECT top(100) * FROM V_WebApp_InvItem_EX where inv_num like '%$keyword%'";
        $cSql = new SqlSrv();
        $rs0 = $cSql->SqlQuery($this->StrConn, $query);
        array_splice($rs0, count($rs0) - 1, 1);
        return $rs0;
        
        ?>