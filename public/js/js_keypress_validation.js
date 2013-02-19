function numOnly(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /[0-9]/
		return re.test(keyChar);
	}	
}

function alphaOnly(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /[\sa-zA-Z-,\/]/
		return re.test(keyChar);
	}	
}


function alphacommaOnly(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /[\sa-zA-Z-,\/]/
		return re.test(keyChar);
	}	
}

function alphaNumOnly(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /[a-zA-Z0-9_-]/
		return re.test(keyChar);
	}	
}

function alphaNumSpaceOnly(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /[\sa-zA-Z0-9_.-]/
		return re.test(keyChar);			
	}	
}

function noWhiteSpace(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /\s/ 
		return !re.test(keyChar);
	}	
}
function numOnlySlash(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /[0-9\/]/
		return re.test(keyChar);
	}	
}
function numOnlyHyphen(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /[0-9-]/
		return re.test(keyChar);
	}	
}

function numOnlyTel(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /[0-9-\)\(\/]/
		return re.test(keyChar);
	}	
}


function numOnlyPeriod(evt)
{	
	var charCode = (evt.which) ? evt.which : window.event.keyCode;

	if (charCode <= 13)
	{
		return true;
	}
	else
	{
		var keyChar = String.fromCharCode(charCode);
		var re = /[0-9.]/
		return re.test(keyChar);
	}	
}