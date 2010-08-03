<?php
class localIterator implements Iterator {
	private $items = array();
	public function __construct($array){
		if(is_array($array)){
			$this->items = $array;
		}
	}
	public function rewind(){
		reset($this->items);
	}
	public function current() {
		$var = current($this->items);
		return $var;
	}
	public function key() {
		$var = key($this->items);
		return $var;
	}
	public function next() {
		$var = next($this->items);
		return $var;
	}
	public function valid() {
		$var = $this->current() !== false;
		return $var;
	}
}
?>