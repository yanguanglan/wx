<?php
if(Request::get('wxid')){
	Session::set('wxid',Request::get('wxid'));
}
if(Request::get('wid')){
	Session::set('wapwid',Request::get('wid'));
}
if(Session::has('wxid') && Session::has('wapwid')){
$h = new Model('weiqi_yiliao_set');
$h->find(array('wid'=>Session::get('wapwid')));
$red = new Model('weiqi_yiliao_record');
$ddres = $red->where(array('wid'=>$h->wid,'wxid'=>Session::get('wxid')))->order('id desc')->list_all();
$state_arr = array('0'=>'已预订','1'=>'已确认','2'=>'已取消','3'=>'已完成');
$rednum = new Model('weiqi_yiliao_record');
$ddzs = $rednum->where(array('hid'=>Request::get(1),'wxid'=> Session::get('wxid')))->count();
$ddxjson = json_decode($h->bookingset);
foreach ($ddres as $dr){
	$formind = 0;
	$ddx = array();

	foreach ($ddxjson as $dj){
		$dd = new stdClass();
		$dd->name = $dj->name;
		$val = 'form'.$formind;
		$dd->val = $dr->$val;
		$formind++;
		$ddx[] = $dd;
	}
	$dr->nr = $ddx;
}
}else{
die();
}