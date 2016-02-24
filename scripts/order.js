addOnLoadCallback(function()
{
	window.orderForm=byId('order_form');
	
	byId('order_btn').onclick=function()
	{
		window.orderForm.submit();
	}
	
	byId('clear_btn').onclick=function()
	{
		window.orderForm.reset();
	}
});