<?php
require_once "presets.php";
if(isset($_GET['catid']))
{
	$cat_id=$_GET['catid'];
	if(!is_numeric($cat_id))
	{
		$cat_id=1;
	}
}
else
{
	$cat_id=1;
}

if(isset($_GET['page']))
{
	$page=$_GET['page'];
	if(!is_numeric($page))
	{
		$page=1;
	}
}
else
{
	$page=1;
}

if(isset($_GET['search']))
{
	$search=$_GET['search'];
	if (get_magic_quotes_gpc($search))
	{
		$search=stripslashes($search);
	}
	$search=htmlentities($search);
	$search=strip_tags($search);
}

if(isset($_GET['firm']))
{
	$firm=$_GET['firm'];
	if (get_magic_quotes_gpc($firm))
	{
		$firm=stripslashes($firm);
	}
	$firm=htmlentities($firm);
	$firm=strip_tags($firm);
}

$condition="WHERE id_category='$cat_id'";
if(isset($search)) $condition.=" AND model LIKE \"%$search%\"";
if(isset($firm)) $condition .= " AND firm_of_device='$firm'";

echo<<<_HEAD
	<!DOCTYPE HTML>
	<html>
		<head>
		<meta charset="utf-8">
		<title>Каталог товаров</title>
		<link rel="stylesheet" href="common.css">
		<link rel="stylesheet" href="catalog.css">
		<script src="scripts/common.js"></script>
		<script src="scripts/catalog.js"></script>
_HEAD;
echoHeadInfo();
echo <<<_HEAD1
		</head>
		<body>
_HEAD1;
	echoHeader();

	dbConnect();

	$result = mysql_query("SELECT name FROM Category WHERE id_category='$cat_id'");
	if (!$result) die("Сбой при доступе к базе данных: " . mysql_error());
	$row = mysql_fetch_row($result);
	$cat_name=$row[0];

if(isset($search))
{
	echo <<<_TITLE
		<div id='content'>
			<div id='wrapper'>
				<h2>Результаты поиска по запросу:"$search"</h2>

		<ul class = "items" id='items_list'>
_TITLE;
}
else if(isset($firm)) {
	echo <<<_TITLE
		<div id='content'>
			<div id='wrapper'>
				<h2>Товары для фирмы "$firm"</h2>

		<ul class = "items" id='items_list'>
_TITLE;
}
else {
	echo <<<_TITLE
		<div id='content'>
			<div id='wrapper'>
				<h2>Товары в категории "$cat_name"</h2>

		<ul class = "items" id='items_list'>
_TITLE;
}



	$position =  ($page - 1)*9;

		/*$query="PREPARE statement FROM 'SELECT * FROM Product WHERE model LIKE \"%?%\" LIMIT $position,9'";
		mysql_query($query);

		$query = "SET @search = $search";
		mysql_query($query);

		$query = 'EXECUTE statement USING @search';
		$result = mysql_query($query);
		if (!$result) die("Сбой при доступе к базе данных: " . mysql_error());

		$query = 'DEALLOCATE PREPARE statement';
		mysql_query($query);*/

	$search1="%".$search."%";
	//$query = "SELECT * FROM Product WHERE model LIKE \"$search1\" LIMIT $position,9";
	$query = "SELECT * FROM Product $condition LIMIT $position,9";
	$result = mysql_query($query);


	if (!$result) die("Сбой при доступе к базе данных: " . mysql_error());
	$rows = mysql_num_rows($result);

	for ($i=0; $i < $rows ; $i++)
	{
		$row = mysql_fetch_row($result);
		$title = $row[4];
		$price = $row[7];
		$idProduct = $row[0];
		echo <<<_LIST
			<li class = "item" data-item-id="$idProduct">
				<div class = "title">
					$title
				</div>
				<div class = "image">
					<img src = "img/goods/preview/$idProduct.png" />
				</div>
				<div class = "price">
					Подробнее...
				</div>
				<div class = "button-order">
					$price Р
				</div>
				<div class = "clear"> </div>
			</li>
_LIST;
	}

	if($rows==0)
	{
		echo "<div class='no-items'>По вашему запросу ничего не найдено</div>";
	}

echo <<<_NAVIGATION
	</ul>
		<div class = "navigation">
			<ul class = "navigation">
				Cтраницы
_NAVIGATION;
$prevPage = $page - 1;
$nextPage = $page + 1;
if (!$search){
	$result = mysql_query("SELECT COUNT(*) FROM Product WHERE id_category='$cat_id'");
}
else{
	/*$query='PREPARE statement FROM "SELECT COUNT(*) FROM Product WHERE model LIKE "%?%""';
	mysql_query($query);
	$query = "SET @search = $search";
	mysql_query($query);
	$query = 'EXECUTE statement USING @search';
	$result = mysql_query($query);
	$query = 'DEALLOCATE PREPARE statement';
	mysql_query($query);
	$search1="%".$search."%";
	$result = mysql_query("SELECT COUNT(*) FROM Product WHERE model LIKE \"$search1\"");*/
}

$result = mysql_query("SELECT COUNT(*) FROM Product $condition");

if (!$result) echo("Сбой при подключении базы данных: " . mysql_error());
$row = mysql_fetch_row($result);
$pages = intval($row[0] / 9);
$pages++;
if (($row[0] % 9) == 0){
	$pages--;
}

if (!$search){
	$prevlink = "/catalog.php?catid=$cat_id&page=$prevPage";
	$nextlink = "/catalog.php?catid=$cat_id&page=$nextPage";
}
else{
	$prevlink = "/catalog.php?page=$prevPage&search=$search";
	$nextlink = "/catalog.php?page=$nextPage&search=$search";
}
for ($i=1; $i <= $pages; $i++) {
	if (($page != 1) && ($page == $i) && ($page != $pages) && ($pages != 1)){
		echo <<<_NAVELEM
		<li class="navigation"><a href='$prevlink'> &lt; Предыдущая</a></li>
		<li class='navigation current'> $i </li>
		<li class="navigation"><a href='$nextlink'> Следующая &gt;</a></li>
_NAVELEM;
	}
	elseif (($page == 1) && ($i == 1) && ($pages != 1)) {
		echo "<li class='navigation current'> $page </li>";
		echo "<li class='navigation'><a href='$nextlink'> Следующая &gt;</a></li>";
	}
	elseif(($page == 1) && ($i == 1) && ($pages == 1))
	{
		echo "<li class='navigation current'> $page </li>";
	}
	elseif (($page == $pages) && ($i == $pages) && ($pages != 1)) {
		echo <<<_NAVELEMENT
		<li class="navigation"><a href='$prevlink'> &lt; Предыдущая</a></li>
		<li class='navigation current'> $i </a></li>
_NAVELEMENT;
	}
	else{
		if (!$search){
			echo "<li class='navigation'><a href='/catalog.php?catid=$cat_id&page=$i'> $i </a></li>";
		}
		else{
			echo "<li class='navigation'><a href='/catalog.php?search=$search&page=$i'> $i </a></li>";
		}
	}
}

	/*<li class="navigation"><a href='all-glasses.ru/catalog.php?page=1'>1</a></li>
	<li class="navigation"><a href='all-glasses.ru/catalog.php?page=2'>2</a></li>
	<li class="navigation"><a href='all-glasses.ru/catalog.php?page=$prevPage'>&#9194; Предыдущая</a></li>
	<li class="navigation current">3</li>*/

echo <<<_DIV
			</ul>
		</div>
	</div>
</div>
_DIV;
	echoFooter();
	mysql_close();
echo <<<_END
	</body>
	</html>
_END;
?>