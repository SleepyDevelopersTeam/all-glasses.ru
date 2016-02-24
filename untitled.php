 <?php
echo<<<_H
<!DOCTYPE HTML>
<html>
  <head>
   	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   	<link rel="stylesheet" href="style.css">
   	<link rel="icon" type="image/png" href="/static/favicon1.png" >
		<link rel="stylesheet" href="bootstrap.min.css">
   	<title>
			Http file server
		</title>
		<style>
		</style>
	</head>
	<body>
	<div>
		<h1><z>Http file server</z></h1>
		<form enctype="multipart/form-data" action="/untitled.php" method="POST">
		<div >
   <input type="submit" name="sendButton" value="send">
   <input type="submit"  name="deleteButton" value="delete">
   </div>
   <br>
   <input type="file" name="myfiles" multiple="multiple">
      <br>
_H;
   // Проверяем загружен ли файл
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   {
   	echo("HHH");
     // Если файл загружен успешно, перемещаем его
     // из временной директории в конечную
     move_uploaded_file($_FILES["filename"]["tmp_name"], "/domains/all-glasses.ru/public_html/upload".$_FILES["filename"]["name"]);
   } else {
      echo("Ошибка загрузки файла");
   }
echo<<<_G
		</form>
	</body>
</html>
_G;
?>