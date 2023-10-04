<?php
use Shuchkin\SimpleXLSX;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
require_once __DIR__.'/../src/SimpleXLSX.php';
if (isset($_FILES['file'])) {
    if ($xlsx = SimpleXLSX::parse($_FILES['file']['tmp_name'])) {
		$dim = $xlsx->dimension();
		$num_cols = $dim[0];
		$num_rows = $dim[1];
		$sheet_array = $xlsx->sheetNames();
		$all_net_sheet = array();
		for($k=0;$k<count($sheet_array);$k++){
			if(strpos($sheet_array[$k],"eport") != false){
				$st =0;
				foreach ($xlsx->rows($k) as $r) {
					if($st>3){
						$all_net_sheet[] = $r[0];
					}
					$st++;
				} 	
			}
		}
		$arr_all = array();
		for($k=0;$k<count($sheet_array);$k++){
			$str = $sheet_array[$k];
			$dim = $xlsx->dimension($k);
			$num_cols = $dim[0];
			$num_rows = $dim[1];
			if(!strpos($str,"eport")){
				$tmp = array();
				foreach ($xlsx->rows($k) as $r) {
					for ($i = 0; $i < $num_cols; $i ++) {
						if(count($r)>$i){
							$element = $r[$i];
							if( strlen($element)>1 && in_array( $element ,$all_net_sheet ) )
							{
								$tmp[] = $element;
							}
							
						}
					}	
				} 
				$arr_all[] = $tmp;			
			}
		}
		$contain_list = array();
		$contai_i = array();
		$stt = 0;
		while($stt < count($arr_all)){
			$stt2 =0;
			while($stt2 < count($arr_all)){
				if($stt != $stt2){
					$tmp_i = array();
					$tmp_i[] = $stt;
					$tmp_i[] = $stt2;
					if( !in_array($tmp_i, $contai_i)){
						if( !in_array(array_reverse($tmp_i), $contai_i) ){
							foreach($arr_all[$stt] as $value){
								if(in_array($value, $arr_all[$stt2])){
									$contain_list[] = $value;
								}
							}
							$contai_i[] =  $tmp_i;
						}	
					}
				}
				$stt2++;
			}
			$stt++;
		} 
		foreach($contain_list as $value){
			echo $value.'<br>';
		}
		
    } else {
       echo SimpleXLSX::parseError();
    }
}
echo '<h2>Upload form</h2>
<form method="post" enctype="multipart/form-data">
*.XLSX <input type="file" name="file"  />&nbsp;&nbsp;<input type="submit" value="Parse" />
</form>'; 
?> 