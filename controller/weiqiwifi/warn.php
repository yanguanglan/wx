<?php
//优惠券活动

if(Request::get('wid')){
	Session::set('wapwid',Request::get('wid'));
}


if(Session::has('wapwid')){
	$set = new Model('weiqi_wifi_keyword');
	$set->find(array('wid'=>Session::get('wapwid')));
}
