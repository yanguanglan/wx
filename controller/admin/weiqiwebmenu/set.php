<?php
$s = new Model('weiqi_web_menustyle');
$s->where(array('wid'=>Session::get('wid')));
$m = new Model('weiqi_web_menu');
if('del'==Request::get(1)){
	$id = Request::post('id');
	$m->find($id);
	if($m->wid != Session::get('wid')){
		die();
	}
	$m->remove();
}else{
	$res = $m->where(array('wid'=>Session::get('wid')))->list_all();
}