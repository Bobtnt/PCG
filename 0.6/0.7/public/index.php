<?php
if(is_file(getcwd().'/install.php')){
	require_once getcwd().'/install.php';
}
else{
	require_once '../index.php';
}
?>