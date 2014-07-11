<?php
$wid = Session::get('wid');
$set = new Model('weiqi_web_banquan');
$set->find(array('wid'=>$wid));
if($set->try_post()){
	$set->wid = $wid;
	$set->save();
	tusi('设置成功');
}
