<?php
require_once "presets.php";
$flagF = TRUE;
$flagRes = FALSE;
$s='';
$finishSum=0;
$finishAmount=0;
openCart();
foreach($cart_content as $v1)
	{
		$i=1;
		foreach($v1 as $v2)
			{
				if($i==1) $idProduct=$v2;
				if($i==2) $amount=$v2;
				if($i==3) $price=$v2;
				$i+=1;
			}
			unset($v2);
			$finishAmount+=$amount;
			$sum=($amount*$price);
			$finishSum+=$sum;
	}		
			unset($v1);
if(isset($_POST['submitted']) && ($finishAmount!=0))
{
	$flag = array(
		"name1" => TRUE,
		"name2" => TRUE,
		"subway" => TRUE,
		"tel" => TRUE,
	);
	if(isset($_POST['name1']) && strlen($_POST['name1'])>0) { $name1=$_POST['name1']; fixString($name1); fixSQL($name1); }
	else $flag["name1"]=FALSE;
	if(isset($_POST['name2']) && strlen($_POST['name2'])>0) {$name2=$_POST['name2']; fixString($name2); fixSQL($name2); }
	else $flag["name2"]=FALSE;
	if(isset($_POST['name3']) && strlen($_POST['name3'])>0) {$name3=$_POST['name3']; fixString($name3); fixSQL($name3); }
	else $name3="-";
	if(isset($_POST['subway'])) $subway=$_POST['subway'];
	else $flag["subway"]=FALSE;
	if(isset($_POST['tel']) && strlen($_POST['tel'])>0) {$tel=$_POST['tel']; fixString($tel); fixSQL($tel); }
	else $flag["tel"]=FALSE;
	if(isset($_POST['mail'])&& strlen($_POST['mail'])>0) {$usermail=$_POST['mail']; fixString($mail); fixSQL($mail); }
	else $usermail="-";
	if(isset($_POST['note'])&& strlen($_POST['note'])>0) {$note=$_POST['note']; fixString($note); fixSQL($note); }
	else $note="-";
	$s = "Вы не ввели: ";
	foreach ($flag as $key => $value)
	{
		if($value==FALSE)
		{
			$flagF=FALSE;
			switch ($key)
			{
				case "name1":
					$temp="Имя";
					break;
				case "name2":
					$temp="Фамилию";
					break;
				case "subway":
					$temp="Метро";
					break;
				case "tel":
					$temp="Телефон";
					break;
			}
			$s.=' '.$temp . ',';
		}
	}
	unset($key, $value);
	//$s[strlen(s)-1]='.';
	$s=substr_replace($s,".",strlen($s)-1,1);
	if ($flagF==TRUE)
	{
		dbConnect();
		$query = "INSERT INTO Buyer VALUES(NULL, '$name1', '$name2', '$name3', '$usermail', '$tel', '$note', '$subway', NULL, 1)";
		$result = mysql_query($query);
		$idBuyer=mysql_insert_id();
		if(!$result) die ("Сбой при доступе к базе данных: " . mysql_error());
		openCart();
		foreach($cart_content as $v1)
		{
			$i=1;
			foreach($v1 as $v2)
			{
				if($i==1) $idProduct=$v2;
				if($i==2) $amount=$v2;
				if($i==3) $price=$v2;
				$i+=1;
			}
			unset($v2);
			$sum=($amount*$price);
			$query = "INSERT INTO Sale VALUES(NULL, '$amount', '$sum' , '$idProduct','$idBuyer')";
			$result = mysql_query($query);
			if(!$result) die ("Сбой при доступе к базе данных: " . mysql_error());
		}		
		unset($v1);
		$adminMail="imaginary-for-spam@yandex.ru";
		$subject="Поступил новый заказ!";
		$message= "
		<html>
		<head>
			<title>Новый заказ</title>
		</head>
		<body>
			<h3>Данные нового заказа</h3>
			<table>
			<tr>
				<th>Заказчик:</th><td>$name1 $name2 $name3</td>
			</tr>
			<tr>
				<th>E-mail:</th><td>$usermail</td>
			</tr>
			<tr>
				<th>Телефон:</th><td>$tel</td>
			</tr>
			<tr>
				<th>Метро:</th><td>$subway</td>
			</tr>
			<tr>
				<th>Коментарий к заказу:</th><td>$note</td>
			</tr>
			</table>
			<p>Данные о заказанном товаре можете посмотреть через учетную записаь администратора на сайте </p>
			<br>
			<a href=\"http://all-glasses.ru/orders.php\">Перейти к просмотру</a>
			</body>
			</html>
		";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$results=mail($adminMail, $subject, $message, $headers);
		if($results==FALSE) echo "Error post message";
		mysql_close($datebase);
		$s="Вы успешно совершили заказ.";
		clearCart();
		$flagRes=TRUE;
	}
}

echo<<<_END1
	<!DOCTYPE HTML>
	<html>
	<head>
	<meta charset="utf-8">
	<title>Оформление заказа</title>
	<link rel="stylesheet" href="common.css">
	<link rel="stylesheet" href="order.css">
	<script src="scripts/common.js"></script>
	<script src="scripts/order.js"></script>
_END1;
	echoHeadInfo();
echo<<<_CA
	</head>
	<body>
_CA;
	echoHeader();
echo<<<_END2
	<div id ='content'>
	<div id='wrapper'>
		<h2>Оформление заказа</h2>
		
		<form method='post' action="order.php" id='order_form'>
			<table>
_END2;

if(($finishAmount==0) &&(!isset($_POST['submitted'])))
{
	$flagRes=TRUE;
echo<<<_ERRAMOUNT
		<tr class='error'>
			<td>Ошибка:</td>
			<td>Нет товаров в корзине, пожалуйста верните в каталог и выберете нужный товар.</td>
		</tr>	
	</table>
	<div class='navigation'>
			<a href='http://all-glasses.ru/'>Вернуться в каталог</a>
		</div>
	</div>
	</div>
_ERRAMOUNT;
}



if(($flagF == FALSE) && isset($_POST['submitted']))
{
echo<<<_ERR
	<tr class='error'>
		<td>Ошибка:</td>
		<td>$s <br/> Пожалуйста повторите ввод.</td>
	</tr>			
_ERR;
}
else{
	if(isset($_POST['submitted']))
	{
echo<<<_OK
		<tr class='ok'>
			<td colspan='2'>$s</td>
		</tr>	
	</table>
	<div class='navigation'>
			<a href='http://all-glasses.ru/'>Вернуться в каталог</a>
		</div>
	</div>
	</div>
_OK;
echoFooter();
	
echo<<<_END25
	</body>
	</html>
_END25;
exit;
	}
}
if($flagRes==FALSE){	
echo<<<_END3
			<tr class='not-empty'>
				<td>Ваше имя:</td>
				<td><input name='name1' type='text' placeholder='Иванов' /></td>
			</tr>
			
			<tr class='not-empty'>
				<td>Ваша фамилия:</td>
				<td><input name='name2' type='text' placeholder='Иван' /></td>
			</tr>
			
			<tr>
			<td>Ваше отчество:</td>
			<td><input name='name3' type='text' placeholder='Иванович' /></td>
			</tr>
			
			<tr class='not-empty'>
				<td>Станция метро:</td>
				<td>
				<select name='subway'>
				<optgroup label='Сокольническая линия' class='red'>
					<option name='Улица Подбельского'>Улица Подбельского</option>
					<option name='Черкизовская'>Черкизовская</option>
					<option name='Преображенская площадь'>Преображенская площадь</option>
					<option name='Сокольники'>Сокольники</option>
					<option name='Красносельская'>Красносельская</option>
					<option name='Комсомольская'>Комсомольская</option>
					<option name='Красные ворота'>Красные ворота</option>
					<option name='Чистые пруды'>Чистые пруды</option>
					<option name='Лубянка'>Лубянка</option>
					<option name='Охотный ряд'>Охотный ряд</option>
					<option name='Библиотека имени Ленина'>Библиотека имени Ленина</option>
					<option name='Кропоткинская'>Кропоткинская</option>
					<option name='Парк культуры'>Парк культуры</option>
					<option name='Фрунзенская'>Фрунзенская</option>
					<option name='Спортивная'>Спортивная</option>
					<option name='Воробьевы горы'>Воробьевы горы</option>
					<option name='Университет'>Университет</option>
					<option name='Проспект Вернадского'>Проспект Вернадского</option>
					<option name='Юго-Западная'>Юго-Западная</option>
					
				</optgroup>
				<optgroup label='Замоскворецкая линия' class='green'>
					
					<option name='Речной вокзал'>Речной вокзал</option>
					<option name='Водный стадион'>Водный стадион</option>
					<option name='Войковская'>Войковская</option>
					<option name='Сокол'>Сокол</option>
					<option name='Аэропорт'>Аэропорт</option>
					<option name='Динамо'>Динамо</option>
					<option name='Белорусская'>Белорусская</option>
					<option name='Маяковская'>Маяковская</option>
					<option name='Тверская'>Тверская</option>
					<option name='Театральная'>Театральная</option>
					<option name='Новокузнецкая'>Новокузнецкая</option>
					<option name='Павелецкая'>Павелецкая</option>
					<option name='Автозаводская'>Автозаводская</option>
					<option name='Коломенская'>Коломенская</option>
					<option name='Каширская'>Каширская</option>
					<option name='Кантемировская'>Кантемировская</option>
					<option name='Царицино'>Царицино</option>
					<option name='Орехово'>Орехово</option>
					<option name='Домодедовская'>Домодедовская</option>
					<option name='Красногвардейская'>Красногвардейская</option>
					
				</optgroup>
				<optgroup label='Каховская линия' class='cyan'>
					
					<option name='Каховская'>Каховская</option>
					<option name='Варшавская'>Варшавская</option>
					<option name='Каширская'>Каширская</option>
					
				</optgroup>
				<optgroup label='Арбатско-Покровская линия' class='blue'>
					
					<option name='Парк победы'>Парк победы</option>
					<option name='Киевская'>Киевская</option>
					<option name='Смоленская'>Смоленская</option>
					<option name='Арбатская'>Арбатская</option>
					<option name='Площадь революции'>Площадь революции</option>
					<option name='Курская'>Курская</option>
					<option name='Бауманская'>Бауманская</option>
					<option name='Электрозаводская'>Электрозаводская</option>
					<option name='Семеновская'>Семеновская</option>
					<option name='Партизанская (Измайловский парк)'>Партизанская (Измайловский парк)</option>
					<option name='Измайловская'>Измайловская</option>
					<option name='Первомайская'>Первомайская</option>
					<option name='Щелковская'>Щелковская</option>
					
				</optgroup>
				<optgroup label='Филевская линия' class='lblue'>
					
					<option name='Крылатское'>Крылатское</option>
					<option name='Молодежная'>Молодежная</option>
					<option name='Кунцевская'>Кунцевская</option>
					<option name='Пионерская'>Пионерская</option>
					<option name='Филевский парк'>Филевский парк</option>
					<option name='Багратионовская'>Багратионовская</option>
					<option name='Фили'>Фили</option>
					<option name='Кутузовская'>Кутузовская</option>
					<option name='Студенческая'>Студенческая</option>
					<option name='Международная'>Международная</option>
					<option name='Деловой центр'>Деловой центр</option>
					<option name='Киевская'>Киевская</option>
					<option name='Смоленская'>Смоленская</option>
					<option name='Арбатская'>Арбатская</option>
					<option name='Александровский сад'>Александровский сад</option>
					
				</optgroup>
				<optgroup label='Кольцевая линия' class='brown'>
					
					<option name='Белорусская'>Белорусская</option>
					<option name='Новослободская'>Новослободская</option>
					<option name='Проспект мира'>Проспект мира</option>
					<option name='Комсомольская'>Комсомольская</option>
					<option name='Курская'>Курская</option>
					<option name='Таганская'>Таганская</option>
					<option name='Павелецкая'>Павелецкая</option>
					<option name='Добрынинская'>Добрынинская</option>
					<option name='Октябрьская'>Октябрьская</option>
					<option name='Парк культуры'>Парк культуры</option>
					<option name='Киевская'>Киевская</option>
					<option name='Краснопресненская'>Краснопресненская</option>
					
				</optgroup>
				<optgroup label='Калужско-Рижская линия' class='orange'>
					
					<option name='Медведково'>Медведково</option>
					<option name='Бабушкинская'>Бабушкинская</option>
					<option name='Свиблово'>Свиблово</option>
					<option name='Ботанический сад'>Ботанический сад</option>
					<option name='ВДНХ'>ВДНХ</option>
					<option name='Алексеевская'>Алексеевская</option>
					<option name='Рижская'>Рижская</option>
					<option name='Проспект мира'>Проспект мира</option>
					<option name='Сухаревская'>Сухаревская</option>
					<option name='Тургеневская'>Тургеневская</option>
					<option name='Китай-город'>Китай-город</option>
					<option name='Третьековская'>Третьековская</option>
					<option name='Октябрьская'>Октябрьская</option>
					<option name='Шабловская'>Шабловская</option>
					<option name='Ленинский проспект'>Ленинский проспект</option>
					<option name='Академическая'>Академическая</option>
					<option name='Профсоюзная'>Профсоюзная</option>
					<option name='Новые черемушки'>Новые черемушки</option>
					<option name='Калужская'>Калужская</option>
					<option name='Беляево'>Беляево</option>
					<option name='Коньково'>Коньково</option>
					<option name='Теплый стан'>Теплый стан</option>
					<option name='Ясенево'>Ясенево</option>
					<option name='Битцевский парк'>Битцевский парк</option>
					
				</optgroup>
				<optgroup label='Таганско-Краснопресненская линия' class='purple'>
					
					<option name='Планерная'>Планерная</option>
					<option name='Сходненская'>Сходненская</option>
					<option name='Тушинская'>Тушинская</option>
					<option name='Щукинская'>Щукинская</option>
					<option name='Октябрьское поле'>Октябрьское поле</option>
					<option name='Полежаевская'>Полежаевская</option>
					<option name='Беговая'>Беговая</option>
					<option name='Улица 1905 года'>Улица 1905 года</option>
					<option name='Баррикадная'>Баррикадная</option>
					<option name='Пушкинская'>Пушкинская</option>
					<option name='Кузнецкий мост'>Кузнецкий мост</option>
					<option name='Китай-город'>Китай-город</option>
					<option name='Таганская'>Таганская</option>
					<option name='Пролетарская'>Пролетарская</option>
					<option name='Волгоградский проспент'>Волгоградский проспент</option>
					<option name='Текстильщики'>Текстильщики</option>
					<option name='Кузьминки'>Кузьминки</option>
					<option name='Рязанский проспект'>Рязанский проспект</option>
					<option name='Выхино'>Выхино</option>
					
				</optgroup>
				<optgroup label='Калининская линия' class='yellow'>
					
					<option name='Третьяковская'>Третьяковская</option>
					<option name='Марксистская'>Марксистская</option>
					<option name='Площадь Ильича'>Площадь Ильича</option>
					<option name='Авиамоторная'>Авиамоторная</option>
					<option name='Шоссе Энтузиастов'>Шоссе Энтузиастов</option>
					<option name='Перово'>Перово</option>
					<option name='Новогиреево'>Новогиреево</option>
					
				</optgroup>
				<optgroup label='Серпуховско-Тимирязевская линия' class='gray'>
					
					<option name='Алтуфьево'>Алтуфьево</option>
					<option name='Бибирево'>Бибирево</option>
					<option name='Отрадное'>Отрадное</option>
					<option name='Владыкино'>Владыкино</option>
					<option name='Петровско-Разумовская'>Петровско-Разумовская</option>
					<option name='Тимирязевская'>Тимирязевская</option>
					<option name='Дмитровская'>Дмитровская</option>
					<option name='Савеловская'>Савеловская</option>
					<option name='Менделеевская'>Менделеевская</option>
					<option name='Цветной бульвар'>Цветной бульвар</option>
					<option name='Чеховская'>Чеховская</option>
					<option name='Боровицкая'>Боровицкая</option>
					<option name='Полянка'>Полянка</option>
					<option name='Серпуховская'>Серпуховская</option>
					<option name='Тульская'>Тульская</option>
					<option name='Нагатинская'>Нагатинская</option>
					<option name='Нагорная'>Нагорная</option>
					<option name='Нахимовский проспект'>Нахимовский проспект</option>
					<option name='Севастопольская'>Севастопольская</option>
					<option name='Чертановская'>Чертановская</option>
					<option name='Южная'>Южная</option>
					<option name='Пражская'>Пражская</option>
					<option name='Улица Академика Янгеля'>Улица Академика Янгеля</option>
					<option name='Аннино'>Аннино</option>
					<option name='Бульвар Дмитрия Донского'>Бульвар Дмитрия Донского</option>
					
				</optgroup>
				<optgroup label='Люблинская линия' class='lime'>
					
					<option name='Трубная'>Трубная</option>
					<option name='Чкаловская'>Чкаловская</option>
					<option name='Римская'>Римская</option>
					<option name='Крестьянская застава'>Крестьянская застава</option>
					<option name='Дубровка'>Дубровка</option>
					<option name='Кожуховская'>Кожуховская</option>
					<option name='Печатники'>Печатники</option>
					<option name='Волжская'>Волжская</option>
					<option name='Люблино'>Люблино</option>
					<option name='Братиславская'>Братиславская</option>
					<option name='Марьино'>Марьино</option>
					
				</optgroup>
				<optgroup label='Бутовская линия' class='llblue'>
					
					<option name='Улица Старокачаловская'>Улица Старокачаловская</option>
					<option name='Улица Скобелевская'>Улица Скобелевская</option>
					<option name='Бульвар Адмирала Ушакова'>Бульвар Адмирала Ушакова</option>
					<option name='Улица Горчакова'>Улица Горчакова</option>
					<option name='Бунинская Аллея '>Бунинская Аллея </option>
					
				</optgroup>
				<optgroup label='Монорельс' class='blue'>
					
					<option name='Тимирязевская'>Тимирязевская</option>
					<option name='Ул. Милашенкова'>Ул. Милашенкова</option>
					<option name='Телецентр'>Телецентр</option>
					<option name='Ул. академика Королева'>Ул. академика Королева</option>
					<option name='Выставочный центр'>Выставочный центр</option>
					<option name='Улица Сергея Эйзенштейна'>Улица Сергея Эйзенштейна</option>
					
				</optgroup>
				</select>
				<p class='info'>Внимание! Доставка осуществляется только до станции метро.</p>
				</td>
			</tr>
			
			 <tr class='not-empty'>
				 <td>Ваш телефон:</td>
				 <td><input name='tel' type='tel' placeholder='+7(987)-654-32-10' /></td>
			 </tr>
			 
			 <tr>
				 <td>Ваш e-mail:</td>
				 <td><input name='mail' type='email' placeholder='name@server.ru' /></td>
			 </tr>
			 
			 <tr>
				<td>Дополнительно:</td>
				<td><textarea name='note' placeholder='Укажите здесь дополнительные сведения, которые вы считаете необходимым сообщить'></textarea></td>
			</tr>
			
			<tr class='info'>
				<td>Информация о заказе:</td>
				<td>Всего товаров: <b>$finishAmount</b>, общая сумма: <b>$finishSum Р</b>. Расчёт осуществляется наличными.</td>
			</tr>
			
			</table>
			<div class='actions'>
				<div class='clear-btn' id='clear_btn'>Сбросить</div>
				<div class='order-btn' id='order_btn'>Готово</div>
				<input type="hidden" name="submitted" value="yes" />
			</div>
		</form>
		<div class='navigation'>
			<a href='' onclick='window.history.back();'>Вернуться в корзину</a>
		</div>
	</div>
	</div>
_END3;
}

echoFooter();
	
echo<<<_END4
	</body>
	</html>
_END4;

?>