<?php
$wxid = Session::has('wapwid')?Session::get('wapwid'):"wxid";
if(Session::has('wapwid')){
	$wid = Session::get('wapwid');
	$list = new Model('micro_xitie_infoList');
	$list->wxid = $wxid;
	$list->wid = $wid;
	$list->ctime = DB::raw('now()');
	if(Request::get(1) == 1){  //赴宴人数
		$list->name   = trim(Request::post('un'));
		$list->tel   = trim(Request::post('tel'));
		$list->rs   = trim(Request::post('rs'));
		$list->sid   = trim(Request::post('sid'));
		$list->type = 1;
		$list->save();
		Response::write(1);
	}
	if(Request::get(1) == 2){  //
		$list->name   = trim(Request::post('un'));
		$list->tel   = trim(Request::post('tel'));
		$list->zhufu   = trim(Request::post('zhufu'));
		$list->sid   = trim(Request::post('sid'));
		$list->type = 2;
		$list->save();
		Response::write(1);
	}
}