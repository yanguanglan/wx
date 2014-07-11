<?php
$wid = Session::get('wid');
$m = new Model('weiqi_api_set');
if($_GET['xing']=='1.html')
{
$m->find(array('wid' => $wid,'name'=>"图片优先处理"));
$m->name = "图片优先处理";
}elseif($_GET['xing']=='2.html'){
$m->find(array('wid' => $wid,'name'=>"*"));
$m->name = "*";
}
if(Request::get(1)){
	$m->find(Request::get(1));
	if($m->wid != $wid){
		die();
	}
}
if($m->try_post()){
$s = new Model('weiqi_api_set');
$s->find(array('wid' => $wid,'name'=>trim($m->name)));
if($s->has_id())
{
	$m->id =$s->id;
}
	$m->wid = $wid;
	$m->save();
	tusi('保存成功');
	Redirect::delay_to('list',1);
}
