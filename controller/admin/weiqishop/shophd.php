<?php
$m = new Model('weiqiwx_product_weiqi');
$m->find(array('token'=>Session::get('wid')));
if($m->try_post()){
	$m->token = Session::get('wid');
	$m->save();
	tusi('保存成功');
}