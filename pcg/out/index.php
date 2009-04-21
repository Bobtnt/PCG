<?php
/**
 * Use this file with the sample database to test all functionnalities of each objects.
 * 
 */


ini_set('include_path', '.;..;../lib');

function __autoload($className){
	if(is_file('./class.'.$className.'.php')){
		require_once './class.'.$className.'.php';
	}	
	if(is_file('../lib/class.'.$className.'.php')){
		require_once '../lib/class.'.$className.'.php';
	}	
	elseif($className == 'Zend_Loader'){
		require_once 'Zend/Loader.php';
	}
	else{
		Zend_Loader::loadClass($className);
	}
}

$bug = new bug();
$bug->setSubject("test")->setDescription("desc test ".rand(200,2000))->save();

$id = $bug->getId();
Zend_Debug::dump($id);
unset($bug);


$bug2 = new bug($id);
$bug2->setSubject('some words...')->save();

unset($bug2);

$bug_collection = new bug_collection();
$bug_collection->select("select * from bug where bug_id < 90 and bug_id > 0");

$bugs[] = $bug_collection->get('subject NOT LIKE Création ');
$bugs[] = $bug_collection->get('subject LIKE Création ');
$bugs[] = $bug_collection->get('id == 88 ');
$bugs[] = $bug_collection->get('id != 88 ');
$bugs[] = $bug_collection->get('id < 88 ');
$bugs[] = $bug_collection->get('id > 88 ');
$bugs[] = $bug_collection->get('id <= 88 ');
$bugs[] = $bug_collection->get('id >= 88 ');
$nb = count($bugs);
for ($a = 0 ; $a < $nb ; $a++) {
	if(is_object($bugs[$a])){
		Zend_Debug::dump($bugs[$a]->getId());
	}else{
		Zend_Debug::dump(false);
	}
}
echo '<hr>';
//foreach ($bug_collection as $bug){
//	Zend_Debug::dump($bug);
//}

$bug_collection = new bug_collection();
$bug_collection->
select("select * from bug where bug_id >= 5  and bug_id <= 7")->
select("select * from bug where bug_id >= 10  and bug_id <= 12");
foreach ($bug_collection as $bug){
	Zend_Debug::dump($bug->getId());
}
echo '<hr>';
/*
collection bug id content:
int(5)
int(6)
int(7)
int(10)
int(11)
int(12)
*/
$bug_collection->get("id == 7")->remove();

foreach ($bug_collection as $bug){
	Zend_Debug::dump($bug->getId());
}
/*
collection bug id content:
int(5)
int(6)
int(10)
int(11)
int(12)
*/
echo '<hr>';
$otherBug = new bug(99);
$bug_collection->add($otherBug);
foreach ($bug_collection as $bug){
	Zend_Debug::dump($bug->getId());
}
/*
collection bug id content:
int(5)
int(6)
int(10)
int(11)
int(12)
int(100)
*/
echo '<hr>';
/**
 * $bug_collection->get('id == 99') is memory pointer to $otherBug
 * $bug_collection->get('id == 99')->getSubject() return 'my test'
 */
$otherBug->setSubject('my test');
echo $bug_collection->get('id == 99')->getSubject();
$bug_collection->save();
echo '<hr>';

unset($otherBug);
unset($bug_collection);

$mybug = new bug(100);
echo $mybug->getSubject();

echo '<hr>';

$bug = new bug(rand(10,100));

Zend_Debug::Dump($bug->getId());
$catName = $bug->category->getName();
$catId = $bug->category->getId();
Zend_Debug::dump($catId.' => '.$catName);

$bug->setCategoryId(2);
$catName = $bug->category->getName();
Zend_Debug::dump($catName);

echo '<hr>';
//if 1:1 relationship is not intialized, the UPDATE (save) won't work 
$bug->setReportedBy(1)->setVerifiedBy(2)->setCreatedBy(3)->save();
Zend_Debug::Dump($bug->getId());
unset($bug);
$bug = new bug(62);
Zend_Debug::Dump($bug->reportedBy->getId());
unset($bug);
echo '<hr>';
//now insert from scratch
$bug = new bug();
$bug->setSubject('new bug')->setDescription('My description !')->setReportedBy(1)->setVerifiedBy(2)->setCreatedBy(3)->save();
$newbugid = $bug->getId();
Zend_Debug::Dump($bug->reportedBy->getId());
unset($bug);


$bug = new bug($newbugid);
Zend_Debug::Dump($bug->reportedBy->getId());
echo '<hr>';

$group = new groups(1);

echo $group->users->count();
//Zend_Debug::Dump($group->users);
echo '<hr>';
$u = new user(1);
echo $u->groups_collection->count();

echo '<hr>';

$g = new groups();
$g->save();
$gid = $g->getId();
$g->setName('My new Group '.$g->getId())->save();
Zend_Debug::Dump($g->getName());

$uc = new user_collection();
$uc->select("SELECT * FROM user");
$i=0;
foreach ($uc as $u) {
	if($i == 1){
		$u->remove();
	}
	$i++;
}

$g->user_collection = $uc;
$g->save();
unset($g);

$gg = new groups($gid);
Zend_Debug::Dump($gg->user_collection->count());



?>