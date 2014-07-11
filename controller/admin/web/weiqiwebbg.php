<?php
$m = new Model('weiqi_web_bg');
$m->find(array('wid'=>Session::get('wid')));
if($m->try_post()){
	$m->wid = Session::get('wid');
	$m->save();
	tusi('保存成功');
}