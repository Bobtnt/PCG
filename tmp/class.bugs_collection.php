<?php
/**
 * collection object for bugs
 *
 */
class bugs_collection implements IteratorAggregate {

	const MODE_APPEND = 1;
	const MODE_REPLACE = 2;
	
	
	private $bug_items = array();
	
	
	public function getIterator() {
		return new localIterator($this->bug_items);
	}
	/**
	 * add bugs to collection
	 *
	 * @param bugs $bugs
	 * @param [integer] $mode @see bugs_collection::select
	 * @return bugs_collection
	 */	
	public function add(bugs $bugs, $mode=1){
		$existFlag = false;
		foreach ($this as $innerBugs){
			//check for existing bugs
			if($innerBugs->getId() == $bugs->getId()){
				$existFlag = true;
				break;
			}
		}
		if($existFlag && $mode == self::MODE_APPEND){
			
		}
		else if($existFlag && $mode == self::MODE_REPLACE){
			
		}
		
	}
	public function remove(){
		
	}
	public function get(){
		
	}
	
	/**
	 * Fill collection with sql query within $mode method
	 * 
	 * bugs_collection::MODE_APPEND
	 * This mode add bugs in collection without modify existing bugs if already in colection
	 * 
	 * bugs_collection::MODE_REPLACE
	 * This mode add bugs in collection replace existing bugs if already in colection
	 *
	 * @param string $query
	 * @param [integer] $mode bugs_collection::MODE_APPEND [default], bugs_collection::MODE_REPLACE
	 * @return 
	 */
	public function select($query, $mode=1){
		$bugsIds = bugs_manager::select($query);
		$nb = count($bugsIds);
		for($a = 0 ; $a < $nb; $a++){
			$bugs = new bugs($bugsIds[$a]);
			$this->add($bugs, $mode);
		}
		return $this;
	}
	
	public function filter(){
		
	}
	

}
?>