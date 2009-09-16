<?php



$smarty = render::getInstance();

$user = new user();

$user->setFullName('toto')->save();

$uid = $user->getId();

$user = new user($uid);
$user->setFullName('Utoto')->save();

$group = new group;
$group->setName('Gtoto')->save();

$group->user_collection->add($user)->save();
$group->save();


?>