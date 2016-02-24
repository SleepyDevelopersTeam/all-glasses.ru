addOnLoadCallback(function()
{
	var ul=byId('items_list');
	
	var lis=byTag(ul, 'li');
	for(var i=0; i<lis.length; i++)
	{
		var ordBtn=byClass(lis[i], 'button-order')[0];
		ordBtn.onclick=function()
		{
			if(this.classList.contains('loading')) return;
			if(document.performing)
			{
				showMessage('Подождите, пока пройдёт предыдущий запрос!', 'Ошибка');
				return;
			}
			var id=this.parentElement.getAttribute('data-item-id');
			this.classList.add('loading');
			document.performing=true;
			performQuery('cartapi.php', false, {
				cmd: 'add',
				pid: id
			},
			function(answer)
			{
				//showMessage(answer.responseText, 'answer.responseText');
				var response=JSON.parse(answer.responseText.replace(/[^0-9A-z\"\:\,\{\}\]\[]/g, ''));
				if(typeof response.error != typeof undefined)
				{
					showMessage(response.error, 'Ошибка при добавлении в корзину');
					this.classList.remove('loading');
					return;
				}
				document.location='http://all-glasses.ru/cart.php';
			},
			function(answer)
			{
				showMessage(xhr.statusText, 'Ошибка при отправке запроса');
				this.classList.remove('loading');
			});
		}
		
		
		var moreBtn=byClass(lis[i], 'price')[0];
		moreBtn.onclick=function()
		{
			var id=this.parentElement.getAttribute('data-item-id');
			document.location='http://all-glasses.ru/item.php?id='+id;
		}
		
		byClass(lis[i], 'image')[0].onclick=function()
		{
			var id=this.parentElement.getAttribute('data-item-id');
			document.location='http://all-glasses.ru/item.php?id='+id;
		}
	}
});