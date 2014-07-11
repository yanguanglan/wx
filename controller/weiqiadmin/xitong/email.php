<?php
$mu = Session::get('mu');
$u = new Model('weiqi_email_config');
$u->find($mu->id);
if($u->try_post()){
	$u->save();
	tusi('设置成功');
}

Session::set('mu',$u);