<?php
require_once 'presets.php';

if(isset($_POST['model']))
{
	dbConnect();
	$type_of_glass=$_POST['type_of_glass'];
	$firm_of_device=$_POST['firm_of_device'];
	$model=$_POST['model'];
	$size=$_POST['size'];
	$price=$_POST['price'];
	$info_of_glass=$_POST['info_of_glass']; 
	$id_category=$_POST['id_category'];
	$result=mysql_query("INSERT INTO Product (type_of_glass,firm_of_device,model,size,price,info_of_glass,id_category)
		VALUES ('$type_of_glass','$firm_of_device','$model','$size','$price','$info_of_glass','$id_category')");
	if(!$result) die("Сбой при добавлении");
	$id = mysql_insert_id();
	if(isset($_FILES['image']))
	{
		$b = chdir('img/goods/large');
		$h=move_uploaded_file($_FILES['image']['tmp_name'],$id.".png");
		$image = imagecreatefrompng($id.".png");
		$new_image = imagecreatetruecolor(400, 200);
		imagecopyresampled ($new_image, $image, 0, 0, 0, 0,400 , 200,imagesx($image),imagesy($image));
		//imagepng($new_image,$id.".png");
		imagepng($new_image,$id.'.png');
	}
	
	mysql_close($database);
	
	
}

echo<<<_HEAD
	<!DOCTYPE HTML>
	<html>
		<head>
		<meta charset="utf-8">
		<title>Каталог товаров</title>
		<link rel="stylesheet" href="common.css">
		<link rel="stylesheet" href="add.css">
		<script src="scripts/common.js"></script>
		<script src="scripts/add.js"></script>
		</head>
		<body>
_HEAD;

echoHeader();

echo<<<_BODY

<div id='content'>
		<div id='wrapper'>
			<h2>Добавления товара в базу данных.</h2>   
			<form method = "post" enctype="multipart/form-data">
				<p class = "add">  модель стекла
					<input type = "text" class = "add" name = "model" required > 
					</input>
				</p>
				<p class = "add">   фирма устройства
					<input type = "text" class = "add" name ="firm_of_device" required  >
					</input>
				</p>
				<p class = "add">   толщина стекла
					<input type = "text" class = "add" name = "type_of_glass" required> 
					</input>
				</p>
				<p class = "add">   стоимость товара
					<input type = "text" class = "add" name = "price" required> 
					</input>
				</p>
				<p class = "add">   категория товара: </br>1, если стекло для телефона и 2,если для планшета
					<input type = "text" class = "add" name = "id_category" required> 
					</input>
				</p>
				<p class = "add">   описание товара
					<input type = "text" class = "add" name = "info_of_glass" required> 
					</input>
				</p>
				<p class = "add">   загрузите файл с картинкой
					<input type = "file" class = "add" name = "image"  required> 
					</input>
				</p>
				<p>
					<input type = "submit" class = "add" >
					</input>
				</p>
				<p>
					<input type = "reset" class = "add">
					</input>
				</p>
			</form>
		</div>
	</div>
_BODY;

echoFooter();

?>