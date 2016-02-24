<?php
require_once "presets.php";
$id = htmlspecialchars($_GET["id"]);

if(!isset($id) || !is_numeric($id))
{
	$id=1;
}

dbConnect();
$query = "SELECT * FROM Product WHERE id_product='$id'";
$result = mysql_query($query);
if(!$result) die ("Сбой при доступе к базе данных: " . mysql_error());
$row = mysql_fetch_row($result);
$typeGlass = $row[1];
$typeDevice = $row[2];
$firmDevice = $row[3];
$model = $row[4];
$mSize = $row[5];
$avaliability = $row[6];
$price = $row[7];
$infoGlass = $row[8];
$idCat = $row[9];
$query = "SELECT * FROM Category WHERE id_category='$idCat'";
$result = mysql_query($query);
if(!$result) die ("Сбой при доступе к базе данных: " . mysql_error());
$row = mysql_fetch_row($result);
$categorys = $row[1];
echo<<<_END
	<!DOCTYPE HTML>
	<html>
	<head>
	<meta charset="utf-8">
	<title>$model</title>

	<link rel="stylesheet" href="common.css">
	<link rel="stylesheet" href="item.css">
	<script src="scripts/common.js"></script>
	<script src="scripts/item.js"></script>
_END;
	echoHeadInfo();
echo<<<_CA
	</head>	
	<body>
_CA;
	echoHeader();
echo<<<_END1
	<div id = content>
		<div id='wrapper'>
		<h2>$model</h2>
		
		<div class='image'><img src="img/goods/large/${id}.png" width="500" height="500" /></div>
		<div class='info' id='info_block'>
			<table>
			<tr><td class='property'>Категория</td><td class='value'>
				<a href='http://all-glasses.ru/catalog.php?catid=$idCat' title='Все товары в категории "$categorys"'>
					$categorys
				</a>
			</td></tr>
			<tr><td class='property'>Модель</td><td class='value'>$model</td></tr>
			<tr><td class='property'>Фирма</td><td class='value'>
				<a href='http://all-glasses.ru/catalog.php?catid=$idCat&firm=$firmDevice' title='Все товары фирмы "$firmDevice"'>
					$firmDevice
				</a>
			</td></tr>
			<tr><td class='property'>Размеры</td><td class='value'>$mSize&#34;</td></tr>
			<tr><td class='property'>Толщина</td><td class='value'>$typeGlass mm</td></tr>
			<tr><td class='property'>Описание</td><td class='value'>
				$infoGlass
			</td></tr>
			</table>
			
			<div class='actions' data-item-id='$id'>
				
				<div class='buy-btn' title='Добавить в корзину и перейти к покупке' id='buy_btn'>$price Р</div>
				<div class='add-to-cart-btn' id='add_btn'>
					<a href='javascript://' title='Добавить в корзину и продолжить выбор'>Добавить в корзину</a>
				</div>
			</div>
		</div>
		
		<div class='navigation'>
			<a href='javascript://' onclick='window.history.back();'>Вернуться к каталогу</a>
		</div>

	</div>
	</div>
_END1;
	echoFooter();
echo<<<_END2
	</body>
	</html>
_END2;
?>