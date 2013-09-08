<?php


$db_host = 'localhost';
$db_user = 'root';
$db_pass  = '';
$db_name  = 'ds_detect_lang';


ini_set("max_execution_time","300000");



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


$query = "
SELECT 	t1.*
FROM words AS t1
JOIN (
	SELECT t2.*
	FROM words AS t2
	GROUP BY word
	HAVING count(*) > 1
) AS t3	ON t1.word = t3.word AND t1.cod = t3.cod";


$result = mysql_query($query, $link);
if (!$result) {
    die('Invalid select query: ' . mysql_error());
}

$str_tmp = '';
$cont = 0;

while ($myrow = mysql_fetch_array($result)){
	
	if ($myrow['word'] != $str_tmp){
		$str_tmp = $myrow['word'];
		continue;
	}
	else {

		$query = "DELETE FROM words WHERE id=" . $myrow['id'];
		$result2 = mysql_query($query, $link);
		if (!$result2) {
			echo 'Invalid delete query: ' . mysql_error() . "\n\n";
		}

		$cont++;
	}
		
	//echo $myrow['id'] . '    ' . $myrow['cod'] . '   ' . $myrow['word'] . "\n\n";
}

echo "\nTotal: " . $cont;



$max_rep = 3;


//SELECT 	A.*
$query = "
SELECT 	A.word
FROM words AS A
JOIN (
	SELECT t1.word 
	FROM words
	AS t1
	GROUP BY word
	HAVING count(*) > " . $max_rep . "
) AS t2	ON A.word = t2.word GROUP BY A.word";


$result = mysql_query($query, $link);
if (!$result) {
    die('Invalid select query: ' . mysql_error());
}

//$query = "select count(*) from words";
/*
$myrow = mysql_fetch_row($result);
print_r($myrow);
exit;
*/

$cont = 0;

while ($myrow = mysql_fetch_array($result)){
	$cont++;
	
	//echo $myrow['id'] . '    ' . $myrow['cod'] . '   ' . $myrow['word'] . "\n\n";
	
	$query = "DELETE FROM words WHERE word='" . addslashes($myrow['word']) . "'";
	//$query = "DELETE FROM words WHERE id=" . $myrow['id'];
	$result2 = mysql_query($query, $link);
	if (!$result2) {
		echo 'Invalid delete query: ' . mysql_error() . "\n\n";
	}

}

echo "\nTotal: " . $cont;


mysql_free_result($result);

mysql_close($link);


exit;

?>