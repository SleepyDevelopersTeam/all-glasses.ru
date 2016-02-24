<?php
//header( 'Location: http://all-glasses.ru/catalog.php?catid=1', true, 303);

require_once "presets.php";

echo<<<_HEAD
	<!DOCTYPE HTML>
	<html>
	<head>
	<meta charset="utf-8">
	<title>Главная </title>
	<link rel="stylesheet" href="common.css">
	<link rel="stylesheet" href="index.css">
	<script src="scripts/common.js"></script>
_HEAD;
echoHeadInfo();
echo <<<_HEAD1
		</head>
		<body>
_HEAD1;

echoHeader();

echo<<<_BODY
	<div id='content'>
		<div id='wrapper'>
			<h2>Главная страница</h2>
			<div class = "contacts">
				<div class = "title">
					Защитные стекла для телефонов
				</div>
				<ul class = "items" id='items_list'> 
					<a href="http://all-glasses.ru/catalog.php?catid=1&firm=Apple" class = "title">
						<li class = "item">
							<div class = "title">
								Apple Iphone
							</div>
							<div class = "image">
								<img src = "img/goods/apple.png" />
							</div>
						</li>
					</a>
					<a href="http://all-glasses.ru/catalog.php?catid=1&firm=Samsung" class = "title">
						<li class = "item">
							<div class = "title">
								Samsung
							</div>
							<div class = "image">
								<img src = "img/goods/samsung.png" />
							</div>
						</li>
					</a>
					<a href="http://all-glasses.ru/catalog.php?catid=1&firm=HTC" class = "title">
						<li class = "item">
							<div class = "title">
								HTC
							</div>
							<div class = "image">
								<img src = "img/goods/htc.png" />
							</div>
						</li>
					</a>
					<a href="http://all-glasses.ru/catalog.php?catid=1" class = "title">
						<li class = "item">
							<div class = "title">
								 Все
							</div>
							<div class = "image">
								<img src = "img/goods/all.png" />
							</div>
						</li>
					</a>
				</ul>
				<div class = "title">
					Защитные стекла для планшетов
				</div>
				<ul class = "items" id='items_list'> 
					<a href="http://all-glasses.ru/catalog.php?catid=2&firm=Apple" class = "title">
						<li class = "item">
							<div class = "title">
								Apple Ipad
							</div>
							<div class = "image">
								<img src = "img/goods/apple.png" />
							</div>
						</li>
					</a>
					<a href="http://all-glasses.ru/catalog.php?catid=2&firm=Samsung" class = "title">
						<li class = "item">
							<div class = "title">
								Samsung
							</div>
							<div class = "image">
								<img src = "img/goods/samsung.png" />
							</div>
						</li>
					</a>
					<a href="http://all-glasses.ru/catalog.php?catid=2&firm=Sony" class = "title">
						<li class = "item">
							<div class = "title">
								Sony
							</div>
							<div class = "image">
								<img src = "img/goods/sony.png" />
							</div>
						</li>
					</a>
					<a href="http://all-glasses.ru/catalog.php?catid=2" class = "title">
						<li class = "item">
							<div class = "title">
								Все
							</div>
							<div class = "image">
								<img src = "img/goods/all.png" />
							</div>
						</li>
					</a>
				</ul>
			</div>
		</div>
	</div>
_BODY;

echoFooter();
?>