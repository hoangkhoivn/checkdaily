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
		$k = $_GET['id']-1;
		$str = $sheet_array[$k];
		$arr = array();
		$tmp = array();
		foreach ($xlsx->rows($k) as $r) {
			for ($i = 0; $i < $num_cols; $i ++) {
				if(count($r)>$i){
					$element = $r[$i];
					if(strlen($element)>1){
						if(strtotime($element) && strlen($element)>10){
							$date = date_create($element);
							$day = date_format($date, 'd/m/Y');
							$day2 = date_format($date, 'Y-m-d');
							$tmp[] = dayFromNumber(getWeekday($day2)).', '.$day;
						}
						if($element=='Holiday or Work'){
							$tmp[] = ' '. $r[$i+4];
							$arr[] = $tmp;
							$tmp = array();
						}
					}
				}
			}
			
		}
		foreach ($arr as $value){
			if($value[1]>0){
				echo $value[0];
				echo ' '.$value[1].'<br>';
			}else{
				echo '<b style="color: red;">'.
					$value[0]
				.'</b><br>';
				//echo '<b>'.$value[0].'</b><br>';
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
function getWeekday($d) {
    return date('w', strtotime($d));
}
function dayFromNumber($dy)
{
    $dates = array(
        0 => 'Chủ nhật',
        1 => 'Thứ hai',
        2 => 'Thứ ba',
        3 => 'Thứ tư',
        4 => 'Thứ năm',
        5 => 'Thứ sáu',
        6 => 'Thứ bảy',
    );
	
    return $dates[$dy];
}

?>
