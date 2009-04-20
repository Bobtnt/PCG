<?php /* Smarty version 2.6.22, created on 2009-04-20 18:14:04
         compiled from index.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>PCG - PHP Class Generator</title>
</head>
<body>
<h1>Welcome On Php Class Generator</h1>

<form action="generate">
<p>
Ces objets seront générer par PCG
</p>
<p>
<ul>
<?php $_from = $this->_tpl_vars['objectsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['obj']):
?>
<li><?php echo $this->_tpl_vars['obj']['name']; ?>
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
</p>  
<input type="checkbox" value="1" name="useZendLoader" id="useZendLoader" /> Use Zend_Loader format<br />
<input type="submit" name="Validate" id="Validate" value="Generate">
</form>
</body>
</html>