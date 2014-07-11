<?php
//优惠券活动
if(Request::get('wxid')){
	Session::set('wxid',Request::get('wxid'));
}
if(Request::get('wid')){
	Session::set('wapwid',Request::get('wid'));
}


if(Session::has('wapwid')){
	$set = new Model('micro_photo_set');
	$set->find(array('wid'=>Session::get('wapwid')));
	$lbs = new Model('micro_photo_list');
	$res = $lbs->where(array('wid'=>Session::get('wapwid'),'isshow'=>'1'))->order('sort desc,id desc')->list_all();
}
