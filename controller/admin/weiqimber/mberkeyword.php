<?php
$wid = Session::get('wid');
$set = new Model('weiqiwx_reply_info');
$set->find(array('token'=>$wid));
if($set->try_post()){
	$set->token = $wid;
	$set->save();
	tusi('设置成功');
}