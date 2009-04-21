<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>PCG - PHP Class Generator</title>
</head>
<body>
<h2>{$message}</h2>
<ul>
{foreach from=$objectsList item=obj}
<li>{$obj.name}</li>
	<ul>
	{foreach from=$obj.properties item=prop}
		<li>{$prop}</li>
	{/foreach}
	</ul>
{/foreach}
</ul>
</body>
</html>