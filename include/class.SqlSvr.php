<?php
    set_time_limit(0);
ini_set('memory_limit', '20M');
class SqlSrv {	

	var $query;
       
	//select
	function SqlQuery($conn, $sql, $type="key") { 
		$this->query = @sqlsrv_query($conn, $sql);
		if ($this->RsQuery($sql)) {
			$row = 1;			
			while ($result = sqlsrv_fetch_array($this->query, SQLSRV_FETCH_ASSOC)) {
				$num=0;
				foreach($result as $key=>$value) {
					//$value = trim(stripslashes($value));
					if ($type=="num") $rs[$row][$num++] = $value;
					else $rs[$row][$key] = $value;	
				}  
				$row++;
			}
			$rs[0][0] = $row-1;			
			return $rs;
		}
	}
	
	//insert //update //delete
	function IsUpDel($conn, $sql) { 
		$sql = str_replace("\'", "''", $sql);
		$this->query = @sqlsrv_query($conn, $sql);
		//sqlsrv_close($conn);
		return $this->RsQuery($sql);
	}	

	//***********************Tools*************************//
	function RsQuery($sql="") {
		if($this->query) return true;
		else {
			echo "<style>";
			echo ".error {
							border: 1px solid;
							margin: 10px 0px;
							padding: 0px 0px 15px 55px;
							background-repeat: no-repeat;
							background-position: 10px 10px;
							color: #D8000C;
							background-color: #FFBABA;
							background-image: url('./template/image/database-error.png');
						}";
			echo "</style>";
			echo '<div class="error">';
			echo '<H3>Now system has some problem could not continue processing.</H3>
						Please contact support ait team.<br>
						&nbsp;<img src="./template/image/icon/email.png" border="0"> support-sl@aitcenter.com';
			echo 'Invalid query:<br>';
			echo '<textarea name="txtError" cols="60" rows="6" id="txtError">'.$sql.'</textarea>';
			echo '</div>';	
			//exit();
			return false;	
		}
	}
	//*************************************************//
}
?>