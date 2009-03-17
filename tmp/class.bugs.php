<?php
/**
 * bugs object
 **/
class bugs {

	private $id; //this is the primary key
	private $subject;
	private $description;
	private $createDate;
	private $updateDate;
	private $priority = 'low';
	private $modified = array('id' => false,'subject' => false,'description' => false,'createDate' => false,'updateDate' => false,'priority' => false);
	 
	private $context; //context object, generaly collection object
	
	
	/**
	 * bugs object constructor
	 * Build bugs with $bugs_id or create new bugs without $bugs_id
	 *
	 * @param [integer] $bugs_id
	 * @param [object] $context
	 */
	function __construct($bugs_id=null, $context=null){
		bugs_manager::factory($this);
		if($bugs_id){
			$this->setId($bugs_id);
			bugs_manager::build($this);
		}
		if($context){
			$this->context = $context;
		}
	}
	/**
	 * Save bugs
	 *
	 * @return bugs
	 **/
	public function save(){
		bugs_manager::using($this);
		bugs_manager::save();
		$this->resetModifier();
		return $this;
	}
	/**
	 * Reset all modifier
	 * You may not use this function
	 **/
	private function resetModifier(){
		$this->modifed['id'] = false;
		$this->modifed['subject'] = false;
		$this->modifed['description'] = false;
		$this->modifed['createDate'] = false;
		$this->modifed['updateDate'] = false;
		$this->modifed['priority'] = false;
	}
	/**
	 * set modifier
	 *
	 * @return bugs
	 **/
	private function setModifier($propertyName, $modified=true){
		$this->modifed[$propertyName] = $modified;
		return $this;
	}
	/**
	 * get modifier
	 *
	 * @return bool
	 **/
	public function getModifier($propertyName){
		return $this->modifed[$propertyName];
	}
	/**
	 * Check function. Unused for the moment.
	 */
	static function check(){
		return $this;
	}
	
	/******************************
	 * GETTER AND SETTER
	 *******************************/
	
	/**
	 * @return bugs->id
	 **/
	public function getId(){
		return (int)$this->id;
	}
	/**
	 * @param $id
	 * @return bugs
	 **/
	public function setId($id){
		$this->id =(int) $id;
		$this->setModifier('id');
		return $this;
	}
	/**
	 * @return bugs->subject
	 **/
	public function getSubject(){
		return stripslashes($this->subject);
	}
	/**
	 * @param $subject
	 * @return bugs
	 **/
	public function setSubject($subject){
		$this->subject = addslashes($subject);
		$this->setModifier('subject');
		return $this;
	}
	/**
	 * @return bugs->description
	 **/
	public function getDescription(){
		return stripslashes($this->description);
	}
	/**
	 * @param $description
	 * @return bugs
	 **/
	public function setDescription($description){
		$this->description = addslashes($description);
		$this->setModifier('description');
		return $this;
	}
	/**
	 * @return bugs->createDate
	 **/
	public function getCreateDate(){
		return $this->createDate;
	}
	/**
	 * @param $createDate
	 * @return bugs
	 **/
	public function setCreateDate($createDate){
		$this->createDate = $createDate;
		$this->setModifier('createDate');
		return $this;
	}
	/**
	 * @return bugs->updateDate
	 **/
	public function getUpdateDate(){
		return $this->updateDate;
	}
	/**
	 * @param $updateDate
	 * @return bugs
	 **/
	public function setUpdateDate($updateDate){
		$this->updateDate = $updateDate;
		$this->setModifier('updateDate');
		return $this;
	}
	/**
	 * @return bugs->priority
	 **/
	public function getPriority(){
		return stripslashes($this->priority);
	}
	/**
	 * @param $priority
	 * @return bugs
	 **/
	public function setPriority($priority){
		$this->priority = addslashes($priority);
		$this->setModifier('priority');
		return $this;
	}
	
	public function setBugsContextObject($context){
		$this->context = $context;
	}
}
?>
