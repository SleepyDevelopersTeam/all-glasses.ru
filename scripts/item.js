function addToCart(redir)
{
	if(window.buyBtn.classList.contains('loading')) return;
	if(window.busy)
	{
		showMessage('Подождите, пока пройдёт предыдущий запрос!', 'Ошибка');
		return;
	}
	var id=window.buyBtn.parentElement.getAttribute('data-item-id');
	window.buyBtn.classList.add('loading');
	window.busy=true;
	
	var sredir=function(answer)
	{
		var response=JSON.parse(answer.responseText.replace(/[^0-9A-z\"\:\,\{\}\]\[]/g, ''));
		if(typeof response.error != typeof undefined)
		{
			showMessage(response.error, 'Ошибка при добавлении в корзину');
			window.buyBtn.classList.remove('loading');
		}
		else
		{
			document.location='http://all-glasses.ru/cart.php';
		}
		window.busy=false;
	}
	var sstay=function(answer)
	{
		var response=JSON.parse(answer.responseText.replace(/[^0-9A-z\"\:\,\{\}\]\[]/g, ''));
		if(typeof response.error != typeof undefined)
		{
			showMessage(response.error, 'Ошибка при добавлении в корзину');
		}
		else
		{
			window.addBtn.innerHTML='Товар добавлен';
			window.addBtn.classList.add('success');
			window.addBtn.onclick=function()
			{ document.location='http://all-glasses.ru/cart.php'; }
			window.addBtn.setAttribute('title', 'Перейти в корзину');
			
			window.buyBtn.classList.add('disabled');
			window.buyBtn.onclick=function(){};
			window.buyBtn.setAttribute('title', '');
		}
		window.buyBtn.classList.remove('loading');
		window.busy=false;
	}
	
	performQuery('cartapi.php', false, {
		cmd: 'add',
		pid: id
	},
	redir?sredir:sstay,
	function(answer)
	{
		showMessage(xhr.statusText, 'Ошибка при отправке запроса');
		window.buyBtn.classList.remove('loading');
		window.busy=false;
	});
}

addOnLoadCallback(function()
{
	window.busy=false;
	
	var div=byId('info_block');
	
	window.buyBtn=byClass(div, 'buy-btn')[0];
	window.pid=window.buyBtn.parentElement.getAttribute('data-item-id');
	buyBtn.onclick=function()
	{
		addToCart(true);
	}
	
	window.addBtn=byTag(byId('add_btn'), 'a')[0];
	window.addBtn.onclick=function()
	{
		addToCart(false);
	}
});