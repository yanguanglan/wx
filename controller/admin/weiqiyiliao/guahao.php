<?php
$xw = new Model('wxweb');
$web = $xw->where(array('wid'=>Session::get('wid')))->order('id')->map_array('uuid', 'name');
$weburl= Conf::$http_path.'wyl/guahao.html?wid='.Session::get('wid');
$m = new Model('weiqi_yiliao_set');
$m->find(array('wid'=>Session::get('wid')));
if($m->try_post()){
	$m->wid = Session::get('wid');
	$m->save();
	tusi('保存成功');
}