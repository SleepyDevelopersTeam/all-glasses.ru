<?php
	error_reporting(E_ALL);


	$db_initialized=false;
	function dbConnect()
	{
		global $datebase, $db_initialized;
		$datebase =mysql_connect('localhost',"vh4u6448","hb8yd3pk");
		if(!$datebase)
		{
			return false;
		}
		mysql_select_db("vh4u6448_glass");
		mysql_query("SET NAMES utf8");

		$db_initialized=true;
		return true;
	}

	$cart_initialized=false;
	function openCart()
	{
		global $cart_content, $cart_initialized;
		session_start();
		if(!isset($_SESSION['cart_content']))
		{
			$_SESSION['cart_content']=array();
		}
		$cart_content=$_SESSION['cart_content'];
		session_write_close();
		$cart_initialized=true;
	}
	function clearCart()
	{
		global $cart_content;
		session_start();
		$_SESSION['cart_content']=array();
		session_write_close();
		$cart_content=array();
	}

function echoHeader()
{
	global $cart_content;
	openCart();
	$totalSum=0;
	$count=count($cart_content);
	for($i=0; $i<$count; $i++)
	{
		$totalSum+=$cart_content[$i][1]*$cart_content[$i][2];
	}
	if($totalSum>0) $totalSum='('.$totalSum.' Р)';
	else $totalSum='';
echo<<<_HTMLHEADER
	<div id='header'>
			<a href="http://all-glasses.ru/"><img id ="logo" src="img/logo.png" alt="LOGO"></a>
				<div id="cart">
					<a href="http://all-glasses.ru/cart.php"><img src="img/cart.png" alt="CART"></a>
					<span><a href="http://all-glasses.ru/cart.php">Корзина $totalSum</a></span>
				</div>
				<div id="telephone">
					Наш телефон: <span>+7 (985) 736-03-80</span>
				</div>
	<ul id="menu">
		<li><a href="http://all-glasses.ru/catalog.php?catid=1">Для смартфонов</a>
			<ul>
				<li><a href="http://all-glasses.ru/catalog.php?catid=1&firm=Apple">Apple iPhone</a></li>
				<li><a href="http://all-glasses.ru/catalog.php?catid=1&firm=Samsung">Samsung</a></li>
				<li><a href="http://all-glasses.ru/catalog.php?catid=1&firm=Sony">Sony</a></li>
				<li><a href="http://all-glasses.ru/catalog.php?catid=1&firm=HTC">HTC</a></li>
				<li><a href="http://all-glasses.ru/catalog.php?catid=1&firm=Lenovo">Lenovo</a></li>
			</ul>
		</li>
		<li><a href="http://all-glasses.ru/catalog.php?catid=2">Для планшетов</a>
			<ul>
				<li><a href="http://all-glasses.ru/catalog.php?catid=2&firm=Apple">Apple iPad</a></li>
				<li><a href="http://all-glasses.ru/catalog.php?catid=2&firm=Samsung">Samsung</a></li>
				<li><a href="http://all-glasses.ru/catalog.php?catid=2&firm=Sony">Sony</a></li>
				<li><a href="http://all-glasses.ru/catalog.php?catid=2&firm=HTC">HTC</a></li>
				<li><a href="http://all-glasses.ru/catalog.php?catid=2&firm=Lenovo">Lenovo</a></li>
			</ul>
		</li>
		<li><a href="http://all-glasses.ru/discounts.php">Акции</a></li>
		<li><a href="http://all-glasses.ru/contacts.php">Контакты</a></li>
	<li><input id="search" type="text" method="POST" placeholder="Искать на сайте..."></li>
	</ul>
	</div>
_HTMLHEADER;
}

function fixString($string){
	if (get_magic_quotes_gpc($string)) {
	$string=stripslashes($string);
	}
	$string=htmlentities($string);
	$string=strip_tags($string);
	return $string;
}

function fixSQL($string){
	$string = mysql_real_escape_string($string);
	$string = fixString($string);
	return $string;
}

function echoHeadInfo()
{
echo<<<_LIKEF
<link rel="icon" type="image/png" href="favicon.png" >
	<script type="text/javascript" src="//vk.com/js/api/openapi.js?117"></script>
	<script type="text/javascript">
  	VK.init({apiId: 5088397, onlyWidgets: true});
	</script>
_LIKEF;
}

function echoFooter()
{
echo<<<_HTMLFOOTER
	<div id="ask">
		<div id="ask_caption">Задать вопрос</div>
		<div>Отправьте нам ваш вопрос, и наш менеджер обязательно с вами свяжется.</div>
		<input  type="text" name="telephone" placeholder="Ваш телефон" />
		<input  type="text" name="name" placeholder="Ваше имя" />
		<textarea placeholder="Ваш вопрос"></textarea>
		<div class="button" id="ask_button">
			Отправить
		</div>
	</div>
	<div id="line">
			&#160
	</div>
	<div id="footer">
	<div id ="socnet">
	<div id="vk_like"></div>
			<script type="text/javascript">
			VK.Widgets.Like("vk_like", {type: "button"}, 1);
			</script>
	</div>
		<div>
		Copyright (C) 2015 <a href="http://all-glasses.ru">all-glasses.ru</a> Все права защищены.
		<p>Сайт разработан командой <a href="http://sdevteam.ru" target="_blank">sdevteam.ru</a></p>
		</div>
	</div>
	<div id='underlayer'>
		<div id='msg'>
			<h2>Заголовок</h2>
			<p>Сообщение</p>
			<div class='button' id='ok_btn'>OK</div>
		</div>
	</div>
_HTMLFOOTER;
}
?>