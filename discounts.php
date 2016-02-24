<?php
require_once "presets.php";

echo<<<_HEAD
	<!DOCTYPE HTML>
	<html>
	<head>
	<meta charset="utf-8">
	<title>Акции</title>
	<script src="http://vk.com/js/api/openapi.js" type="text/javascript"></script>
	<link rel="stylesheet" href="common.css">
	<link rel="stylesheet" href="discounts.css">
	<script src="scripts/common.js"></script>

_HEAD;

echoHeadInfo();

echo "</head><body>";

echoHeader();

echo<<<_BODY
	<div id='content'>
		<div id='wrapper'>
			<h2>Действующие акции</h2>
			<ul class='discounts'>
				<li>
					<img src="img/disc/4+1.png" />
					<div class='description'>
					При покупке 4 стекол для любых моделей 5-е в подарок! (любое, кроме цветных, при наличии)
					</div>
				</li>
				<li>
					<img src="img/disc/-5.png" />
					<div class='description'>
					Если вы есть в нашей клиентской базе, то у вас постоянная скидка 5%!
					</div>
				</li>
				<li>
					<img src="img/disc/apple.png" />
					<div class='description'>
					При покупке защитного стекла для iPad любого поколения защитное стекло для iPhone4/5/6 в подарок!
					</div>
				</li>
			</ul>
		</div>
	</div>
_BODY;

echoFooter();
?>