<?php
use Shuchkin\SimpleXLSX;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
require_once __DIR__.'/../src/SimpleXLSX.php';
if (isset($_FILES['file'])) {
    if ($xlsx = SimpleXLSX::parse($_FILES['file']['tmp_name'])) {
		$sheet_array = $xlsx->sheetNames();
		$all_net_sheet = array();
		for($k=0;$k<count($sheet_array);$k++){
			if(strpos($sheet_array[$k],"port") != false){
				$st =0;
				foreach ($xlsx->rows($k) as $r) {
					if($st>3){
						$all_net_sheet[] = $r[0];
					}
					$st++;
				} 	
			}
		}
		for($k=0;$k<count($sheet_array);$k++){
			$str = $sheet_array[$k];
			$dim = $xlsx->dimension($k);
			$num_cols = $dim[0];
			$num_rows = $dim[1];
			if(!strpos($str,"eport")){
				foreach ($xlsx->rows($k) as $r) {
					for ($i = 0; $i < $num_cols; $i ++) {
						if(count($r)>$i){
							$element = $r[$i];
							if( strlen($element)>1 && in_array( $element ,$all_net_sheet ) )
							{
								echo $element.'<br>';
							}
						}
					}	
				} 
						
			}
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