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
		//set context object
		$bugs->setBugsContextObject($this);
//		Zend_Debug::dump(__CLASS__.' '.$bugs->getId());
		foreach ($this as $key => $innerBugs){
			//check for existing bugs
			if($innerBugs->getId() == $bugs->getId()){
				$existFlag = true;
				$existKey = $key;
				break;
			}
		}
		if($existFlag && $mode == self::MODE_APPEND){
			// do nothing
		}
		else if($existFlag && $mode == self::MODE_REPLACE){
			//replace
			$this->_set($existKey, $bugs);
		}
		else if(!$existFlag && ($mode == self::MODE_APPEND || $mode == self::MODE_APPEND)){
			//simply add
			$this->_add($bugs);
		}
		else{
			//what ??!
			return false;
		}
		return $this;
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
	 * @return bugs_collection
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
	
	
	/**
	 * Set bugs for local collection
	 *
	 * @access private
	 * @param integer $key
	 * @param bugs $bugs
	 * @return bugs_collection
	 */
	private function _set($key, bugs $bugs){
		$this->bug_items[$key] = $bugs;
		return $this;
	}
	/**
	 * add bugs for local collection 
	 *
	 * @access private
	 * @param bugs $bugs
	 * @return bugs_collection
	 */
	private function _add(bugs $bugs) {
		$this->bug_items[] = $bugs;
		return $this;
	}
	

}
?>