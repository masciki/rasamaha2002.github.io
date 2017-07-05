function validateAlphanumeric(string)
{
	var filter = /^[0-9a-zA-Z]+$/;
        
	if(filter.test(string))
	{
		return true;
	}
	else
	{
		return false;	
	}
}

function validateOnlyLetters(string)
{
	var filter = /^[a-zA-Z\ \']+$/;
	
	if(filter.test(string))
	{
		return true;
	}
	else
	{
		return false;	
	}
}

function validateOnlyNumbers(string)
{
	var filter = /^[0-9\ ]+$/;
	
	if(filter.test(string))
	{
		return true;
	}
	else
	{
		return false;	
	}
}

function validateEmail(string)
{
	var filter = /^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/;
	
	if(filter.test(string))
	{
		return true;
	}
	else
	{
		return false;	
	}
}

function validateTelephone(string)
{
	var filter = /^[0-9\-\(\)\ ]+$/
	
	if(filter.test(string))
	{
		return true;
	}
	else
	{
		return false;	
	}
}

function getCheckedValuesCount(checkbox_name)
{
	return $('input[name=' + checkbox_name + ']:checked').length;
}

function getCheckedValues(checkbox_name)
{
	var checkValues = [];
	
	$('input[name=' + checkbox_name + ']:checked').each(function() {
															  
			checkValues.push($(this).val());	
	});
	
	var checkedString = checkValues.join(",");
	
	return checkedString;
}