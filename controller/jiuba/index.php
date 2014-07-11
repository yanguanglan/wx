<?php
if(Request::get('wxid')){
	Session::set('wxid',Request::get('wxid'));
}
if(Request::get('wid')){
	Session::set('wid',Request::get('wid'));
}
if(Session::has('wxid') && Session::has('wid')){


	$set = new Model('micro_jiuba_set');
	$set->find(array('wid'=>Session::get('wid')));
	if(!$set->has_id()){
		die();
	}
	
	$hb = new Model('micro_jiuba_haibao');
	$hb->find(array('wid'=>Session::get('wid')));
	$xwurl = '/weiweb/'.Session::get('wid').'/'.$set->xwid.'.html?wxid='.Session::get('wxid').'#mp.wx.qq.com';
	$yyurl = '/yuyue/yy-'.$set->yyid.'.html?wxid='.Session::get('wxid').'#mp.wx.qq.com';
	$hyurl = '/wx/hyk-'.$set->hyid.'.html?wxid='.Session::get('wxid').'#mp.wx.qq.com';
}else{
	die();
}
