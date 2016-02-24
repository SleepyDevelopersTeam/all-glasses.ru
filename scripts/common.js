var windowLoadCallbacks=[];

function addOnLoadCallback(f) { windowLoadCallbacks.push(f); }

window.onload=function()
{
	for(var i=0; i<windowLoadCallbacks.length; i++)
	{
		windowLoadCallbacks[i]();
	}
}

function byId(id) { return document.getElementById(id); }
function byTag(parent, name)
{ if(typeof name==typeof undefined) { name=parent; parent=document; } return parent.getElementsByTagName(name); }
function byClass(parent, name)
{ if(typeof name==typeof undefined) { name=parent; parent=document; } return parent.getElementsByClassName(name); }

function wrapPrice(roubles) { return roubles + " Р"; }

function getXmlHttp(){
	var xmlhttp=null;
	try
	{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch (e)
	{
		try
		{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (E)
		{
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined')
	{
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function performQuery(url, isPost, params, callback, errcallback)
{
	var prm='';
	for(var x in params)
	{
		prm+=x+'='+encodeURIComponent(params[x])+'&';
	}
	prm=prm.substr(0, prm.length-1);

	console.log('XHR sending: '+url+', params: '+prm+', method '+(isPost?'post':'get'));

	if(!isPost)
	{
		url+='?'+prm;
		prm=null;
	}

	var xhr=getXmlHttp();
	xhr.open(isPost?'POST':'GET', url, true);

	if(isPost) xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.send(prm);

	xhr.onreadystatechange=function()
	{
		if (xhr.readyState != 4) return;
		if (xhr.status == 200)
		{
			callback(xhr);
		}
		else
		{
			if(typeof errcallback==typeof function(){})
				errcalback(xhr);
		}
	}
}

function showMessage(text, caption)
{
	if(typeof caption == typeof undefined) caption="Сообщение";

	und=byId('underlayer');
	und.classList.remove('inactive');
	und.classList.add('active');

	msg=byId('msg');
	byTag(msg, 'h2')[0].innerHTML=caption;
	byTag(msg, 'p')[0].innerHTML=text;

	byId('ok_btn').onclick=function()
	{
		closeMessage();
	}
}
function closeMessage()
{
	und=byId('underlayer');
	und.classList.add('inactive');
	setTimeout("byId('underlayer').classList.remove('active')", 500);
}

addOnLoadCallback(function()
{
	window.searchField=byId('search');

	window.searchField.onkeyup=function(ev)
	{
		if(ev.keyCode==13)
		{
			document.location='http://all-glasses.ru/catalog.php?search='+this.value;
		}
	}

	byId('ask_caption').onclick=function()
	{
		var block=byId('ask');
		if(block.classList.contains('open'))
		{
			block.classList.remove('open');
			block.classList.add('close');
		}
		else
		{
			block.classList.remove('close');
			block.classList.add('open');
		}
	}
	byId('ask_button').onclick=function() {
		var inputs=byTag(byId('ask'), 'input'); // [telephone, name]
		var textarea=byTag(byId('ask'), 'textarea')[0];
		if (inputs[0].value=="" || inputs[0].value=="" || textarea.value=="") {
			showMessage("Заполните все поля");
		} else {
			performQuery('ask.php', true, {
				tel: inputs[0].value,
				name: inputs[1].value,
				q: textarea.value
			},
			function(answer)
			{
				var response=JSON.parse(answer.responseText.replace(/[^0-9A-z\"\:\,\{\}\]\[]/g, ''));
				if(typeof response.error != typeof undefined)
				{
					showMessage(response.error, 'Ошибка при изменении корзины!');
					return;
				}
				else
				{
					showMessage('В ближайшее время наш менеджер перезвонит вам на указанный номер.', 'Заявка оставлена');
					byId('ask_caption').onclick();
				}
			},
			function(answer)
			{
				showMessage(xhr.statusText, 'Ошибка при отправке запроса');
			});
		}
	}
});