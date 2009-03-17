<?php 
interface databaseTemplate {
	
	public function query($sql);	//excute sql query
	public function fetchAll();		//fetch assoc last query
	public function getInsertId();	//get last insert id
	public function getAffectedRows();
	
}

?>