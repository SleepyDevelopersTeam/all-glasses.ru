<?php
require_once 'presets.php';
$username = 'admin';
$password = '5d258786a17782ba82acd94a51db7065';
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
{
	if (($_SERVER['PHP_AUTH_USER']==$username) && (md5($_SERVER['PHP_AUTH_PW'])==$password))
	{
		echo<<<_HEAD
	<!DOCTYPE HTML>
	<html>
		<head>
			<meta charset="utf-8">
			<link rel="stylesheet" href="orders.css">
_HEAD;



	dbConnect();
	if(isset($_GET['buyer_id']))
	{
		$buyer_id=$_GET['buyer_id'];
		if(!is_numeric($buyer_id))
		{
			$buyer_id="";
		}
	}

	if(isset($_GET['makeinactive']))
	{
		$inactiveId=$_GET['makeinactive'];
		if(!is_numeric($inactiveId))
		{
			$inactiveId="";
		}
	}

	if ($inactiveId){
		$result=mysql_query("UPDATE Buyer SET active=0 WHERE id_buyer=$inactiveId");
		if (!$result) die("Сбой при подключиение". mysql_error());
	}

	if(isset($_GET['answered']))
	{
		$answeredId = $_GET['answered'];
		if(!is_numeric($answeredId))
		{
			$answeredId = "";
		}
	}

	if ($answeredId){
		$result = mysql_query("UPDATE Info SET active=0 WHERE id_question=$answeredId");
		if (!$result) die("Сбой при подключиение". mysql_error());
	}

	if(isset($_POST['call'])){
		$count = count($_POST["call"]);
		for ($i=0; $i < $count; $i++) {
			$questionID = $_POST["call"][$i];
			if (is_numeric($questionID)){
				$result = mysql_query("UPDATE Info SET active=0 WHERE id_question=$questionID");
				if (!$result) die("Сбой при подключиение". mysql_error());
			}
		}
	}
	if (isset($_POST["order"])) {
		$count = count($_POST["order"]);
		for ($i=0; $i < $count ; $i++) {
			$buyerID = $_POST["order"][$i];
			if (is_numeric($buyerID)) {
				$result = mysql_query("UPDATE Buyer SET active=0 WHERE id_buyer=$buyerID");
				if (!$result) die("Сбой при подключиение". mysql_error());
			}
		}

	}

	if (!$buyer_id){
		echo <<<_ORDERS
		<title>Cписок заказов</title>
		</head>
		<body>
		<form method="POST">
			<table class="orders">
				<thead>
_ORDERS;

		$result = mysql_query("SELECT COUNT(*) FROM Buyer WHERE active=1");
		if (!$result) die("Сбой при доступе к базе данных: " . mysql_error());
		$row = mysql_fetch_row($result);
		$count = $row[0];
		echo <<<_TABLE
		<p align="center">Всего активных заказов: $count шт.</p>
				 <tr>
					  <th> </th>
					  <th> Дата </th>
					 	<th> ФИО </th>
					 	<th> Телефон </th>
					 	<th> Цена </th>
					 	<th> </th>
					 </tr>
				</thead>
				<tbody>
_TABLE;

		$query = "SELECT date, first_name, second_name, third_name, phone_number, id_buyer FROM Buyer WHERE (active=1) ORDER BY date";

		$result = mysql_query($query);
		if (!$result) die("Сбой при доступе к базе данных: " . mysql_error());

		$rows = mysql_num_rows($result);
			for ($i=0; $i < $rows; $i++) {
			$row = mysql_fetch_row($result);
			$date = $row[0];
			$name = $row[1] . " " . $row[2] . " " . $row[3];
			$phone = $row[4];
			$idBuyer = $row[5];
			$query = "SELECT sum FROM Sale WHERE id_buyer=$idBuyer";
			$sumResult = mysql_query($query);
			if(!$sumResult) die("Сбой при доступе к базе данных: " . mysql_error(). "пизда");

			$amount = mysql_num_rows($sumResult);
			$sum = 0;
			for ($j=0; $j < $amount; $j++) {
				$sumRow = mysql_fetch_row($sumResult);
				$sum +=  $sumRow[0];
			}
			$cost = $sum;

			echo <<<_ORDER
				<tr>
					<td> <input type="checkbox" name="order[]" value="$idBuyer"></td>
					<td> $date </td>
					<td> <a href="/orders.php?buyer_id=$idBuyer"> $name </a> </td>
					<td> $phone </td>
					<td> $cost Р </td>
					<td>
						<a href="orders.php?makeinactive=$idBuyer"> Выполнить </a>
					</td>
				</tr>
_ORDER;
		}
		echo <<<_TABLETAIL
					</tbody>
		</table>
		<input type="submit" value="Выполнить" />
		</form>
_TABLETAIL;

		echo <<<_TABLE
		<form method="POST">
		<table class="calls">
			<thead>
				<p align="center"> Вопросы и запросы на звонок </p>
				 <tr>
					  <th> </th>
					 	<th> ФИО </th>
					 	<th> Телефон </th>
					 	<th> Вопрос </th>
					 	<th> </th>
					</tr>
			</thead>
			<tbody>
_TABLE;

		$query = "SELECT * FROM Info WHERE active=1";
		$result = mysql_query($query);
		if (!$result) die("Сбой при подключиение". mysql_error());

		$rows = mysql_num_rows($result);
		for ($i=0; $i < $rows; $i++) {
			$row = mysql_fetch_row($result);
			$idQuestion = $row[0];
			$name = $row[1];
			$phone = $row[2];
			$question = $row[3];
			echo <<<_CALL
				<tr>
					<td> <input type="checkbox" name="call[]" value="$idQuestion"></td>
					<td> $name </td>
					<td> $phone </td>
					<td> $question </td>
					<td>
						<a href="orders.php?answered=$idQuestion"> Отзвонился </a>
					</td>
				</tr>
_CALL;
		}
		echo <<<_TABLEEND
		</tbody>
	</table>
	<input type="submit" value="Отзвонился" />
	</form>
_TABLEEND;
	}
	else{
			echo <<<_ORDER1
			<title>Заказ</title>
			</head>
			<body>
				<ul class="order">
_ORDER1;
		$query="SELECT date, first_name, second_name, third_name, phone_number, mail, subway, note FROM Buyer WHERE id_buyer=$buyer_id";
		$result = mysql_query($query);
		$row = mysql_fetch_row($result);
		echo ("<li> Дата заказа: ". $row[0]. "</li>");
		echo ("<li> ФИО: ". $row[1]. " ". $row[2]. " ". $row[3] . "</li>");
		echo ("<li> Телефон: ". $row[4]. "</li>");
		echo ("<li> E-mail: ". $row[5]. "</li>");
		echo ("<li> Метро: ". $row[6]. "</li>");
		echo ("<li> Примечание к заказу: ". $row[7]. "</li>");
		echo ("<li> Список покупок: ". "</li>");
		echo <<<_ITEMTABLESTART
		<table>
			<thead>
				<tr>
					<th> Предмет </th>
					<th> Количество </th>
					<th> Цена </th>
				</tr>
			</thead>
			<tbody>
_ITEMTABLESTART;

		$query = "SELECT model, amount, sum FROM Sale, Product WHERE (Product.id_product=Sale.id_product) AND (id_buyer=$buyer_id)";
		$result = mysql_query($query);
		$rows = mysql_num_rows($result);
		$sum = 0;
		for ($i=0; $i < $rows ; $i++) {
			$row = mysql_fetch_row($result);
			$model = $row[0];
			$amount = $row[1];
			$cost = $row[2];
			$sum += $cost;
			echo <<<_TABLEITEM
			<tr>
				<td> $model </td>
				<td> $amount </td>
				<td> $cost </td>
			</tr>
_TABLEITEM;
		}
		echo <<<_LASTROW
			<tr>
				<td> </td>
				<td> ИТОГО: </td>
				<td> $sum </td>
			</tr>
_LASTROW;
	echo <<<_ITEMTABLESTOP
		</tbody>
	</table>

_ITEMTABLESTOP;
	echo "<a href='' onclick='window.history.back();'>Вернуться в заказы</a>";
}
	echo <<<_TAIL
	</body>
</html>
_TAIL;
		exit();
	}
}
header('WWW-Authenticate: Basic realm="only for owner" ');
header('HTTP/1.0 401 Unauthorized');
die ("Input name and password");
?>