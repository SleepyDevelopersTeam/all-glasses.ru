<?php
	require_once "presets.php";
echo<<<_CA
	<!DOCTYPE HTML>
	<html>
		<head>
			<meta charset="utf-8">
			<title>Корзина</title>
			<link rel="stylesheet" href="common.css">
			<link rel="stylesheet" href="cart.css">
			<script src="scripts/common.js"></script>
			<script src="scripts/cart.js"></script>
_CA;
		echoHeadInfo();
echo<<<_CB
		</head>
		<body>
_CB;
	echoHeader();
echo<<<_P1
			<div id ='content'>
			<div id='wrapper'>
			<h2>Корзина</h2>
			<table class='cart' id='cart_table'>
			<tr class='title'><td>Наименование</td><td>Цена</td><td>Количество</td><td>Стоимость</td><td></td></tr>
_P1;
			openCart();
			if(count($cart_content)>0)
			{
				dbConnect();
				foreach($cart_content as $i)
				{
					$id=$i[0];
					$result=mysql_query("SELECT model, price FROM Product WHERE id_product = '$id'");
					if(!$result) die("Сбой при доступе к базе данных:".mysql_error());
					$row = mysql_fetch_row($result);
					$name=$row[0];
					$count = $i[1];
					$price = $row[1];
					
					
					echo<<<_P2
					<tr data-product-id='$id'>
					<td><a href='http://all-glasses.ru/item.php?id=$id' target="_blank">$name</a></td>
					<td>$price</td>
				<td>
					<div class='counter inc'>+</div>
					<input type='text' value=$count class='amount'>
					<div class='counter dec'>-</div>
				</td>
			<td> </td>
			<td><a href='javascript://'>Убрать из корзины</a></td>
_P2;
				}
				mysql_close($datebase);
			}
			else
			{
				// the cart is empty
				echo "<tr><td colspan='5'>Корзина пуста!</td></tr>";
			}
			
echo<<<_P3
	<tr class='summ'>
		<td>Итог:</td>
		<td></td>
		<td></td>
		<td id='td_summ'></td>
		<td></td>
	</tr>

	</table>

	<div class='order-btn' id='order_btn'>Оформить заказ</div>

	<div class='navigation'>
		<a href='javascript://' onclick='window.history.back();'>Вернуться к каталогу</a>
	</div>
	</div>
	</div>
_P3;
	echoFooter();
echo<<<_R
	</body>
	</html>
_R;
?>