<?php


/**
 * @return bool
 */
function check_smarty_cache_access(){
	$smartyCacheDir = dirname(__FILE__).'/../smartyWorkDir/cache';	
	return is_writable($smartyCacheDir);	
}
/**
 * @return bool
 */
function check_smarty_compile_access(){
	$smartyCompileDir = dirname(__FILE__).'/../smartyWorkDir/templates_c';
	return is_writable($smartyCompileDir);	
}
/**
 * @return bool
 */
function check_out_dir(){
	$outDir = dirname(__FILE__).'/../out';
	return is_writable($outDir);
}
/**
 * @return bool
 */
function check_zend_framework(){
	$zendloader = dirname(__FILE__).'/../lib/Zend/Loader.php';
	return is_file($zendloader);
}
/**
 * @return bool
 */
function check_smarty(){
	$smarty = dirname(__FILE__).'/../lib/smarty/libs/Smarty.class.php';
	return is_file($smarty);
}

$finalCheck = true;
$ZFavaible = false;

if(check_smarty_cache_access()){
	$msgSmartyCache = '<span style="color:green">Passed</span>';
}
else{
	$msgSmartyCache = '<span style="color:red">Failed</span><br />
	<span>Cannot write in : '.realpath(dirname(__FILE__).'/../smartyWorkDir/cache').'</span>';
	$finalCheck = false;
}

if(check_smarty_compile_access()){
	$msgSmartyCompile = '<span style="color:green">Passed</span>';
}
else{
	$msgSmartyCompile = '<span style="color:red">Failed</span><br />
	<span>Cannot write in : '.realpath(dirname(__FILE__).'/../smartyWorkDir/templates_c').'</span>';
	$finalCheck = false;
}

if(check_out_dir()){
	$msgOutdir = '<span style="color:green">Passed</span>';
}
else{
	$msgOutdir = '<span style="color:red">Failed</span><br />
	<span>Cannot write in : '.realpath(dirname(__FILE__).'/../out').'</span>';
	$finalCheck = false;
}

if(check_smarty()){
	$msgSmarty = '<span style="color:green">Passed</span>';
}
else{
	$msgSmarty = '<span style="color:red">Failed</span>';
	$finalCheck = false;
}

if(check_zend_framework()){
	$msgZend = '<span style="color:green">Passed</span>';
	$ZFavaible = true;
}
else{
	$msgZend = '<span style="color:red">Failed</span>';
	$finalCheck = false;
}

if($ZFavaible){
	chdir(dirname(realpath(__FILE__)).'/..');
	ini_set('include_path', '.'.PATH_SEPARATOR.'./lib');
	require_once 'Zend/Config/Ini.php';
	
	require_once 'Zend/Db.php';
	require_once 'Zend/Db/Adapter/Pdo/Abstract.php';
	require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
	
	$config = new Zend_Config_Ini('etc/config.ini', 'DEFAULT_DATABASE');
	$db = Zend_Db::factory($config->database);
	
	$msgDb =  '<span style="color:green">Passed</span>';
	
	try {
		$db->getConnection();
	}
	catch (Zend_Db_Adapter_Exception $e) {
	    $msgDb = '<span style="color:red">Failed</span><br />
	    <span>'.$e->getMessage().'</span>'; 
	} 
	catch (Zend_Exception $e) {
	    $msgDb = '<span style="color:red">Failed</span><br />
	    <span>'.$e->getMessage().'</span>'; 
	}
}




$output = '<html>';
$output .= '<head>';
$output .= '<title>';
$output .= 'PCG installation';
$output .= '</title>';
$output .= '<style>';
$output .= '.nowrap { white-space:nowrap }';
$output .= '</style>';
$output .= '</head>';
$output .= '<body>';
$output .= '<h1>';
$output .= 'PCG install file';
$output .= '</h1>';
$output .= '<p>';
$output .= '<table border="0px" width="30%">';
$output .= '<tr>';
$output .= '<td class="nowrap"> Smarty cache access';
$output .= '</td>';
$output .= '<td>';
$output .= $msgSmartyCache;
$output .= '</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<td class="nowrap"> Smarty compile access';
$output .= '</td>';
$output .= '<td class="nowrap">';
$output .= $msgSmartyCompile;
$output .= '</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<td class="nowrap"> OUT directory';
$output .= '</td>';
$output .= '<td class="nowrap">';
$output .= $msgOutdir;
$output .= '</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<td class="nowrap"> Smarty lib';
$output .= '</td>';
$output .= '<td class="nowrap">';
$output .= $msgSmarty;
$output .= '</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<td class="nowrap"> Zend Framework lib';
$output .= '</td>';
$output .= '<td class="nowrap">';
$output .= $msgZend;
$output .= '</td>';
$output .= '</tr>';

if($ZFavaible){
	$output .= '<tr>';
	$output .= '<td class="nowrap"> Database access';
	$output .= '</td>';
	$output .= '<td class="nowrap">';
	$output .= $msgDb;
	$output .= '</td>';
	$output .= '</tr>';
	
}



$output .= '</table>';
$output .= '</p>';

$output .= '<p>';
$output .= '<input type="button" value="Refresh" onclick="location.reload()" />';
$output .= '</p>';

$output .= '</body>';
$output .= '</html>';

echo $output;
?>