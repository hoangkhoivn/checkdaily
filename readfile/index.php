<?php
use Shuchkin\SimpleXLSX;
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
require_once __DIR__.'/../src/SimpleXLSX.php';
if (isset($_FILES['file'])) {
    if ($xlsx = SimpleXLSX::parse($_FILES['file']['tmp_name'])) {
       
		$sheet_array = $xlsx->sheetNames();
		for($k=0;$k<count($sheet_array);$k++){
			$str = $sheet_array[$k];
			$dim = $xlsx->dimension($k);
			//if($str !="report"){
				foreach ($xlsx->rows($k) as $r) {
					for ($i = 0; $i < $dim[0]; $i ++) {
						$element = $r[$i];
						if(count($r)>= $i){
							if( strlen($element)>1){
								echo  $element. '<BR>';	
							} 
						}
						
					}
					
				} 	
			//}
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