

function watermark(object)
{
	if(object.value == "")
		{
		value = "Phone Number";
		object.value = "Phone Number";
		}
	object.onfocus = function()
	{
		if(object.value == value)
		{
			object.value = '';
		}
	}
	
	object.onblur = function()
	{
		if(object.value == '')
		{
			object.value = value;
		}
	}
}