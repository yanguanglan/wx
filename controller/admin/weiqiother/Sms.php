<?php
$wid = Session::get('wid');
$set = new Model('weiqiwx_sms_set');
$set->find(array('token'=>$wid));
if($set->try_post()){
	$set->token = $wid;
	$set->save();
	tusi('设置成功');
}
