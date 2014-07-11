<?php
$wid = Session::get('wid');
$uid = Session::get('uid');
$set = new Model('wwz_keyword');
$set->find(array('wid'=>$wid));
$xg = array('1'=>"星光",'2'=>"飘雪",'3'=>"飘玫瑰",'4'=>"下落的枫叶");
if($set->try_post()){
	$set->wid = $wid;
	$set->uid = $uid;
	$set->save();
	tusi('设置成功');
}
