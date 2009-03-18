<?php
/**
 * collection object for bugs
 *
 * <pattern syntax>
 * $pattern = "proprety operator value"
 * operator can be [== | != | < | <= | > | >= | LIKE | NOT LIKE]
 * Examples: 
 * $pattern = "subject LIKE some words";
 * $pattern = "description NOT LIKE some words";
 * $pattern = "id LIKE 123" ;
 */
class bugs_collection implements IteratorAggregate {

	const MODE_APPEND = 1;
	const MODE_REPLACE = 2;	
	
	private $bug_items = array();

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
	/**
	 * count collection elements
	 *
	 * @param [mixed] $count by ref
	 * @return bug_colection|integer
	 */
	public function count(&$count=null){
		if(!is_null($count)){
			$count = count($this->bug_items);
			return $this;
		}
		else{
			return count($this->bug_items);	
		}		
	}
	/**
	 * remove current item from collection
	 *
	 * @param bugs $bugs
	 * @return boolean
	 */
	public function remove(bugs $bugs){
		$existFlag = false;
		foreach ($this as $key => $innerBugs){
			//check for existing bugs
			if($innerBugs->getId() == $bugs->getId()){
				$existFlag = true;
				$existKey = $key;
				break;
			}
		}
		if($existFlag){
			unset($this->bug_items[$existKey]);
			return true;
		}else{
			return false;
		}
	}
	/**
	 * get first object match $pattern selector from collection
	 *
	 * Pattern is a selector from object properties:
	 * $pattern = 'id == 88'
	 * 
	 * @param string $pattern
	 * @return bugs 
	 */
	public function get($pattern){
		$pattern = trim($pattern);
		if(empty($pattern)){
			return false;
		}
	 	$keyList = $this->_filter($pattern);
		return (count($keyList)) != 0 ?  $this->item($keyList[0]) : false;
	}
	/**
	 * call collection item's by $index
	 *
	 * @param integer $index
	 * @return bugs
	 */
	public function item($index){
		return $this->bug_items[$index];
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
	/**
	 * remove items in collection matching $pattern
	 *
	 * @param string $pattern
	 * @return bugs_collection
	 */
	public function filter($pattern){
		$pattern = trim($pattern);
		if(empty($pattern)){
			return false;
		}
		$keyList = $this->_filter($pattern);
		if(is_array($keyList)){
			foreach ($keyList as $key){
				unset($this->bug_items[$key]);
			}
		}
		return $this;
	}
	/**
	 * save all elements in collection
	 *
	 * @return bugs_collection
	 */
	public function save(){
		foreach($this as $item){
			$item->save();
		}
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
	/**
	 * return list of key matching $pattern
	 * 
	 * @access private
	 * @see collection
	 * @param string $pattern
	 * @return array
	 */
	private function _filter($pattern){
		//parse pattern and generate condition
		$patternizerPattern = '#(.+)(==|!=|<=|<|>=|>|(?<=[^T] )LIKE | NOT LIKE )(.+)#';
		$matches = array();  
		preg_match($patternizerPattern, $pattern, $matches);
		$localTestedProperty = trim($matches[1]);
		$operator = trim($matches[2]);
		$testedValue = trim($matches[3]);
		$testedValue = (is_numeric($testedValue) ? intval($testedValue) : $testedValue);
		$results = array();
		$getterName = 'get'.preg_replace("#(_(.))#e" , "strtoupper('\\2')" , '_'.$localTestedProperty);
		foreach ($this as $key => $item){
			switch($operator){
				case "==":
				if($item->$getterName() == $testedValue){
					$results[] = $key;
				}
				break;
				case "!=":
				if($item->$getterName() != $testedValue){
					$results[] = $key;
				}
				break;
				case "<=":
				if($item->$getterName() <= $testedValue){
					$results[] = $key;
				}
				break;
				case "<":
				if($item->$getterName() < $testedValue){
					$results[] = $key;
				}
				break;
				case ">=":
				if($item->$getterName() >= $testedValue){
					$results[] = $key;
				}
				break;
				case ">":
				if($item->$getterName() > $testedValue){
					$results[] = $key;
				}
				break;
				case "LIKE":
				if(strpos($item->$getterName(),$testedValue) !== false){
					$results[] = $key;
				}
				break;
				case "NOT LIKE":
				if(strpos($item->$getterName(),$testedValue) === false){
					$results[] = $key;
				}
				break;
				default:
					//what ??!
					return false;
			}			
		}
		return $results;
	}
	/**
	 * called by iterator (foreach syntax)
	 *
	 * @return localIterator
	 */
	public function getIterator() {
		return new localIterator($this->bug_items);
	}
}
?>