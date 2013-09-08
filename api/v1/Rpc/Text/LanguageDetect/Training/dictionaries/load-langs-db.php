<?php


$db_name  = 'ds_detect_lang';
$db_host = 'localhost';
$db_user = 'root';
$db_pass  = '';


ini_set("max_execution_time","300000");
//ini_set("memory_limit","500M");


$dir_path = dirname(__FILE__);


$link = mysql_connect($db_host, $db_user, $db_pass);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db($db_name, $link);
if (!$db_selected) {
    die ('Can\'t use ' . $db_name . ' : ' . mysql_error());
}

$query = "SET NAMES 'utf8';";
$result = mysql_query($query, $link);
if (!$result) {
    die('Invalid utf8 query: ' . mysql_error());
}

/*
$query = "TRUNCATE TABLE words";
$result = mysql_query($query, $link);
if (!$result) {
    die('Invalid truncate query: ' . mysql_error());
}
*/


if($dir = opendir($dir_path)){

	$id = 0;
	//$id = 14459946;
	
	while (($file = readdir($dir)) !== false) {

		if(is_file($dir_path . DIRECTORY_SEPARATOR . $file)){
			$ext = pathinfo($file);
			if($ext['extension'] == 'dic' || $ext['extension'] == 'char'){
				echo "\nInit file proces -> $file\n";
				//flush();
				$cont = 0;
				$err = 0;
				
				if ($ext['extension'] == 'char'){
					$lang = basename($file, '.char');
				}
				else {
					$lang = basename($file, '.dic');
					$lang = explode('_', $lang);
					$lang = $lang[0];
				}

				$content = file($dir_path . DIRECTORY_SEPARATOR . $file);

				$mxiv = 0;
				$query = '';
				
				foreach($content as $n=>$line) {

					$line = trim($line);
					$len = strlen($line);
					if ($len > 0 && $len < 31){
					
						//si es numeric pasem d'ell
						if (is_numeric($line)){
							continue;
						}
						
						if ($ext['extension'] == 'dic'){
							if ($len < 2){
								continue;
							}
						}
						
						
						$mxiv++;
						if ($mxiv == 2500){
							
							$query = "INSERT INTO words (id, word, cod) VALUES $query ($id, '" . addslashes($line) . "','$lang');";
							$result = mysql_query($query, $link);
							if (!$result) {
								echo 'Invalid insert query: ' . mysql_error() . "\n";
								$err++;
								$id -= 2500;
							}
							else {
								$cont++;
								$id++;
							}
							$query = '';
							$mxiv = 0;
						}
						else {
							$query .= "($id, '" . addslashes($line) . "','$lang'), ";
							$id++;
						}
						
					}
				}
				
				if (strlen($query) > 0){
					$query = substr($query, 0, strlen($query) - 2);
					$query = "INSERT INTO words (id, word, cod) VALUES $query;";
					$result = mysql_query($query, $link);
					if (!$result) {
						echo 'Invalid insert query: ' . mysql_error() . "\n";
						$err++;
						$id -= $mxiv;
					}
					else {
						$cont++;
						$id++;
					}
				}
				
				echo $id . "\n\n";
				
				$arr_totals[$lang]['inserts'] = $cont * 2500;
				if ($err > 0){
					$arr_totals[$lang]['errors'] = $err * 2500;
				}
			}
		}
	}

	closedir($dir);

	if (is_array($arr_totals)){
		print_r($arr_totals);
	}
	
	echo "\n\nTotal records -> $id";
}


mysql_close($link);


exit;

?>