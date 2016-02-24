<?php
require_once "presets.php";

if(isset($_POST['tel']) && isset($_POST['name']) && isset($_POST['q']))
{
	$tel=$_POST['tel'];
	$name=$_POST['name'];
	$q=$_POST['q'];
	
	$tel=fixString($tel);
	$name=fixString($name);
	$q=fixString($q);
	
	dbConnect();
	$result=mysql_query("INSERT INTO Info VALUES (NULL, '$name', '$tel', '$q', '1')");
	if(!$result) die('{ "error": "'.mysql_error().'", "errcode": "1" }');
	die('{ "answer": "success" }');
}
else
{
	echo '{ "error": "You haven\'t filled in some required fields!", "errcode": "0" }';
}
?>