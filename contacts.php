<?php
require_once "presets.php";

echo<<<_HEAD
	<!DOCTYPE HTML>
	<html>
	<head>
	<meta charset="utf-8">
	<title>Каталог товаров</title>
	<script src="http://vk.com/js/api/openapi.js" type="text/javascript"></script>
	<link rel="stylesheet" href="common.css">
	<link rel="stylesheet" href="contacts.css">
	<script src="scripts/common.js"></script>

_HEAD;

echoHeadInfo();

echo "</head><body>";

echoHeader();

echo<<<_BODY
	<div id='content'>
		<div id='wrapper'>
			<h2>Контакты</h2>
			<div class = "contacts">
				<table class = "contacts" cellspacing="10px">
					<tr>
						<td>
							<div>Наши контакты</div>
						</td>
						<td>
							<div>Мы в VK</div>
						</td>
						<td>
							<div>Мы в Instagram</div>
						</td>
					</tr>
					<tr>
						<td width = "300px" class = "contacts">
						<div id="about">
							Интернет-магазин защитных стекол для телефонов и планшетов.
							<br />
							<b>Наш телефон</b>: +7 (985) 736-03-80.
							<br />
							Также вы можете оставить заявку на обратный звонок, используя форму ниже.
						</div>
						</td>
						<td width = "300px" class = "contacts" align = "center">
						<div="VK">
							<div id="vk_groups"></div>
								<script type="text/javascript">
								VK.Widgets.Group("vk_groups", {mode: 0, width: "300", height: "500"}, 52363468);
							</script>
						</div>
						</td>
						<td width = "300px" class = "contacts">
						<div id ="instagr">
							<blockquote class="instagram-media" data-instgrm-captioned data-instgrm-version="4" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"><div style="padding:8px;"> <div style=" background:#F8F8F8; line-height:0; margin-top:40px; padding:26.2037037037% 0; text-align:center; width:100%;"> <div style=" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAAGFBMVEUiIiI9PT0eHh4gIB4hIBkcHBwcHBwcHBydr+JQAAAACHRSTlMABA4YHyQsM5jtaMwAAADfSURBVDjL7ZVBEgMhCAQBAf//42xcNbpAqakcM0ftUmFAAIBE81IqBJdS3lS6zs3bIpB9WED3YYXFPmHRfT8sgyrCP1x8uEUxLMzNWElFOYCV6mHWWwMzdPEKHlhLw7NWJqkHc4uIZphavDzA2JPzUDsBZziNae2S6owH8xPmX8G7zzgKEOPUoYHvGz1TBCxMkd3kwNVbU0gKHkx+iZILf77IofhrY1nYFnB/lQPb79drWOyJVa/DAvg9B/rLB4cC+Nqgdz/TvBbBnr6GBReqn/nRmDgaQEej7WhonozjF+Y2I/fZou/qAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;"></div></div> <p style=" margin:8px 0 0 0; padding:0 4px;"> <a href="https://instagram.com/p/8MUkAFIaPb/" style=" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;" target="_top">Water on Mars! New findings from our Mars Reconnaissance Orbiter (MRO) provide the strongest evidence yet that liquid water flows intermittently on present-day Mars. Dark, narrow streaks on Martian slopes such as these at Hale Crater are inferred to be formed by seasonal flow of water on contemporary Mars. The streaks are roughly the length of a football field. The imaging and topographical information in this processed, false-color view. These dark features on the slopes are called &#34;recurring slope lineae&#34; or RSL. Planetary scientists detected hydrated salts on these slopes at Hale Crater, corroborating the hypothesis that the streaks are formed by briny liquid water. Image Credit: NASA/JPL-Caltech/Univ. of Arizona #nasa #nasabeyond #mars #mro #space #planets #marsannouncement #journeytomars #science</a></p> <p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">Фото опубликовано NASA (@nasa) <time style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;" datetime="2015-09-28T23:21:14+00:00">Сен 28 2015 в 4:21 PDT</time></p></div></blockquote> <script async defer src="//platform.instagram.com/en_US/embeds.js"></script>
						</div>
						</td>
					</tr>
				</table>	
			</div>
		</div>
	</div>
_BODY;

echoFooter();
?>