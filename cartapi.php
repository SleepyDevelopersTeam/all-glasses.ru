<?php
	require_once 'presets.php';
	
	header("Content-type: text/plain; charset=utf-8");
	
	function printCartAndAnswer($answer)
	{
		echo '{ "answer": "'.$answer.'", "cart": [ ';
		$cnt=count($_SESSION['cart_content']);
		for($i=0; $i<$cnt; $i++)
		{
			echo '{ "id": "'.$_SESSION['cart_content'][$i][0];
			echo '", "count": "'.$_SESSION['cart_content'][$i][1];
			echo '", "price": "'.$_SESSION['cart_content'][$i][2].'" }';
			if($i != $cnt-1) echo ', ';
		}
		echo ' ] }';
	}
	
	if(!isset($_GET['cmd'])) die('{"error": "no command set", "errcode": "0"}');
	$cmd=$_GET['cmd'];
	
	switch($cmd)
	{
		case 'add': // (pid)
			// adding specified item to the cart
			$pid=$_GET['pid'];
			if(!is_numeric($pid)) die('{ "error": "pid should be a number!", "errcode": "1" }');
			
			session_start();
			$cnt=count($_SESSION['cart_content']);
			for($i=0; $i<$cnt; $i++)
			{
				if($_SESSION['cart_content'][$i][0]==$pid)
				{
					// this item is already in the cart, inc it's count
					$_SESSION['cart_content'][$i][1]++;
					printCartAndAnswer("increment");
					session_write_close();
					exit();
				}
			}
			// this is a new item
			dbConnect();
			$result=mysql_query("SELECT price FROM Product WHERE id_product='$pid'");
			$row=mysql_fetch_row($result);
			mysql_close();
			
			session_start();
			$_SESSION['cart_content'][]=array($pid, 1, $row[0]);
			session_write_close();
			printCartAndAnswer("success");
		break;
		
		case 'rem': // (pid)
			// removing one item from the cart
			$pid=$_GET['pid'];
			if(!is_numeric($pid)) die('{ "error": "pid should be a number!", "errcode": "1" }');
			
			session_start();
			$cnt=count($_SESSION['cart_content']);
			for($i=0; $i<$cnt; $i++)
			{
				if($_SESSION['cart_content'][$i][0]==$pid)
				{
					array_splice($_SESSION['cart_content'], $i, 1);
					printCartAndAnswer("success");
					session_write_close();
					exit();
				}
			}
			printCartAndAnswer("fail");
		break;
		
		case 'inc': // (pid)
			// incrementing amount of specified item
			$pid=$_GET['pid'];
			if(!is_numeric($pid)) die('{ "error": "pid should be a number!", "errcode": "1" }');
			
			session_start();
			$cnt=count($_SESSION['cart_content']);
			for($i=0; $i<$cnt; $i++)
			{
				if($_SESSION['cart_content'][$i][0]==$pid)
				{
					$_SESSION['cart_content'][$i][1]++;
					printCartAndAnswer("success");
					session_write_close();
					exit();
				}
			}
			printCartAndAnswer("fail");
		break;
		
		case 'dec': // (pid)
			// decrementing amount of specified item
			$pid=$_GET['pid'];
			if(!is_numeric($pid)) die('{ "error": "pid should be a number!", "errcode": "1" }');
			
			session_start();
			$cnt=count($_SESSION['cart_content']);
			for($i=0; $i<$cnt; $i++)
			{
				if($_SESSION['cart_content'][$i][0]==$pid)
				{
					$current_amount=$_SESSION['cart_content'][$i][1];
					if($current_amount <= 1) die('{ "error": "cannot decrease amount to value lesser than 1!", "errcode": "2" }');
					$_SESSION['cart_content'][$i][1]--;
					printCartAndAnswer("success");
					session_write_close();
					exit();
				}
			}
			printCartAndAnswer("fail");
		break;
		
		case 'set': //(pid, value)
			// setting specified count of specified item
			// (if not exist, add it with specified count)
			$pid=$_GET['pid'];
			if(!is_numeric($pid)) die('{ "error": "pid should be a number!", "errcode": "1" }');
			
			$value=$_GET['value'];
			if(!is_numeric($value)) die('{ "error": "value should be a number!", "errcode": "1" }');
			if($value < 1) die('{ "error": "value should be greater than 0!", "errcode": "1" }');
			
			session_start();
			$cnt=count($_SESSION['cart_content']);
			for($i=0; $i<$cnt; $i++)
			{
				if($_SESSION['cart_content'][$i][0]==$pid)
				{
					$_SESSION['cart_content'][$i][1]=$value;
					printCartAndAnswer("success");
					session_write_close();
					exit();
				}
			}
			//printCartAndAnswer("fail");
			// OK, that's a new element, let's calculate its price and add it into the cart
			dbConnect();
			$result=mysql_query("SELECT price FROM Product WHERE id_product='$pid'");
			$row=mysql_fetch_row($result);
			mysql_close();
			$price=$row[0];
			
			$_SESSION['cart_content'][]=array($pid, $value, $price);
			printCartAndAnswer("success");
			session_write_close();
		break;
		
		case 'get': // ()
			// listing back cart content
			session_start();
			printCartAndAnswer("success");
		break;
	}
?>