<?php 
interface databaseTemplate {
	
	public function query($sql);
	public function fetchAll();
	public function getInsertId();
	
}

?>