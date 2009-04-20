<?php


if($_POST['Validate']){
	phpClassGenerator::$userZendLoader = true;
	phpClassGenerator::factory();
	phpClassGenerator::listTable();
	phpClassGenerator::makeAllObjects();
	phpClassGenerator::makeAll();
}
?>