function parsePrice(str) { return str.replace(/[^0-9]/g, '')*1; }

function recalculatePrices()
{
	if(window.emptyCart) return;
	var totalPrice=0;
	
	// skipping title and summary rows
	for(var i=1; i<window.trs.length-1; i++)
	{
		if(window.trs[i].classList.contains('deleted')) continue;
		var tds=byTag(window.trs[i], 'td');
		// 0:name, 1:price, 2:quantity, 3:total price, 4:remove link
		
		// recalculate total prices for each position
		var price=parsePrice(tds[1].innerHTML)*parsePrice(tds[2].children[1].value);
		tds[3].innerHTML=wrapPrice(price);
		totalPrice+=price;
	}
	
	window.totalPriceTd.innerHTML=wrapPrice(totalPrice);
}

function syncCart(response)
{
	window.sync=true;
	
	var getTr=function(pid)
	{
		for(var i=1; i<window.trs.length-1; i++)
		{
			if(window.trs[i].getAttribute('data-product-id')==pid) return trs[i];
		}
		return null;
	}
	
	for(var i=1; i<window.trs.length-1; i++)
	{
		window.trs[i].classList.add('deleted');
		byTag(window.trs[i], 'td')[4].firstElementChild.innerHTML='Вернуть в корзину';
		window.trs[i].classList.remove('loading');
	}
	
	for(var i=0; i<response.cart.length; i++)
	{
		var item=response.cart[i];
		var tr=getTr(item.id);
		if(tr!=null)
		{
			tr.classList.remove('deleted');
			byTag(tr, 'td')[4].firstElementChild.innerHTML='Убрать из корзины';
			var tds=byTag(tr, 'td');
			tds[2].children[1].value=item.count;
		}
	}
	
	recalculatePrices();
	window.sync=false;
}
function clearLoadings()
{
	for(var i=1; i<window.trs.length-1; i++)
	{
		window.trs[i].classList.remove('loading');
	}
}

addOnLoadCallback(function()
{
	// flag shows that now we're syncronizing cart content with server,
	// and nothing should be modified in local copy
	window.sync=false;
	
	window.emptyCart=false;
	
	var table=byId('cart_table');
	
	var trs=byTag(table, 'tr');
	
	window.totalPriceTd=byTag(trs[trs.length-1], 'td')[3];
	
	var totalPrice=0;
	
	// skipping title and summary rows
	for(var i=1; i<trs.length-1; i++)
	{
		var tds=byTag(trs[i], 'td');
		// 0:name, 1:price, 2:quantity, 3:total price, 4:remove link
		
		if(tds.length<2)
		{
			// that means this:
			window.emptyCart=true;
			break;
		}
		
		tds[2].firstElementChild.onclick=function()
		{
			if(window.sync) return;
			if(this.parentElement.parentElement.classList.contains('deleted')) return;
			// '+' onclick
			/*if(this.parentElement.parentElement.classList.contains('deleted')) return;
			if(window.sync) return;
			
			this.nextElementSibling.value=parsePrice(this.nextElementSibling.value)+1;
			recalculatePrices();*/ // old handler
			
			this.parentElement.parentElement.classList.add('loading');
			var id=this.parentElement.parentElement.getAttribute('data-product-id');
			performQuery('cartapi.php', false, {
				cmd: 'inc',
				pid: id
			},
			function(answer)
			{
				//showMessage(answer.responseText, 'answer.responseText');
				var response=JSON.parse(answer.responseText.replace(/[^0-9A-z\"\:\,\{\}\]\[]/g, ''));
				if(typeof response.error != typeof undefined)
				{
					showMessage(response.error, 'Ошибка при изменении корзины!');
					clearLoadings();
					return;
				}
				else
				{
					syncCart(response);
				}
			},
			function(answer)
			{
				showMessage(xhr.statusText, 'Ошибка при отправке запроса');
				clearLoadings();
			});
		}
		tds[2].children[2].onclick=function()
		{
			if(window.sync) return;
			if(this.parentElement.parentElement.classList.contains('deleted')) return;
			// '-' onclick
			/*if(this.parentElement.parentElement.classList.contains('deleted')) return;
			if(window.sync) return;*/ // old handler
			
			var newCount=parsePrice(this.previousElementSibling.value)-1;
			if(newCount<1) return;
			
			this.parentElement.parentElement.classList.add('loading');
			var id=this.parentElement.parentElement.getAttribute('data-product-id');
			performQuery('cartapi.php', false, {
				cmd: 'dec',
				pid: id
			},
			function(answer)
			{
				//showMessage(answer.responseText, 'answer.responseText');
				var response=JSON.parse(answer.responseText.replace(/[^0-9A-z\"\:\,\{\}\]\[]/g, ''));
				if(typeof response.error != typeof undefined)
				{
					showMessage(response.error, 'Ошибка при изменении корзины!');
					clearLoadings();
					return;
				}
				else
				{
					syncCart(response);
				}
			},
			function(answer)
			{
				showMessage(xhr.statusText, 'Ошибка при отправке запроса');
				clearLoadings();
			});
		}
		tds[2].children[1].onblur=function()
		{
			// textbox loses focus
			if(window.sync) return;
			if(this.parentElement.parentElement.classList.contains('deleted')) return;
			
			var newCount=Math.max(parsePrice(this.value), 1);
			
			this.parentElement.parentElement.classList.add('loading');
			var id=this.parentElement.parentElement.getAttribute('data-product-id');
			performQuery('cartapi.php', false, {
				cmd: 'set',
				pid: id,
				value: newCount
			},
			function(answer)
			{
				//showMessage(answer.responseText, 'answer.responseText');
				var response=JSON.parse(answer.responseText.replace(/[^0-9A-z\"\:\,\{\}\]\[]/g, ''));
				if(typeof response.error != typeof undefined)
				{
					showMessage(response.error, 'Ошибка при изменении корзины!');
					clearLoadings();
					return;
				}
				else
				{
					syncCart(response);
				}
			},
			function(answer)
			{
				showMessage(xhr.statusText, 'Ошибка при отправке запроса');
				clearLoadings();
			});
		}
		
		tds[4].firstElementChild.onclick=function()
		{
			if(window.sync) return;
			// 'remove' link onclick
			/*var tr=this.parentElement.parentElement;
			tr.classList.toggle('deleted');
			var command='';
			if(tr.classList.contains('deleted'))
			{
				this.innerHTML='Вернуть в корзину';
				command='rem';
			}
			else
			{
				this.innerHTML='Убрать из корзины';
				command='set';
			}
			recalculatePrices();*/ // old handler
			
			this.parentElement.parentElement.classList.add('loading');
			var id=this.parentElement.parentElement.getAttribute('data-product-id');
			var del=this.parentElement.parentElement.classList.contains('deleted');
			var val=byTag(this.parentElement.parentElement, 'td')[2].children[1].value;
			performQuery('cartapi.php', false, {
				cmd: del?'set':'rem',
				pid: id,
				value: val
			},
			function(answer)
			{
				//showMessage(answer.responseText, 'answer.responseText');
				var response=JSON.parse(answer.responseText.replace(/[^0-9A-z\"\:\,\{\}\]\[]/g, ''));
				if(typeof response.error != typeof undefined)
				{
					showMessage(response.error, 'Ошибка при изменении корзины!');
					clearLoadings();
					return;
				}
				else
				{
					syncCart(response);
				}
			},
			function(answer)
			{
				showMessage(xhr.statusText, 'Ошибка при отправке запроса');
				clearLoadings();
			});
		}
	}
	
	window.trs=trs;
	
	byId('order_btn').onclick=function()
	{
		if(window.sync) showMessage('Идёт отправка ваших изменений на сервер. Пожалуйста, дождитесь её завершения.',
									'Пожалуйста, подождите...');
		else
		{
			if(window.emptyCart)
			{
				showMessage('Добавьте что-нибудь в корзину прежде, чем оформлять заказ.', 'Корзина пуста!');
			}
			else
				document.location='http://all-glasses.ru/order.php';
		}
	}
	
	recalculatePrices();
});