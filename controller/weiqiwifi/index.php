<?php
//wx-0-d-u-cc 继续盗用吧，信你！有种自己开发

if(Request::get('wid')){
	Session::set('wapwid',Request::get('wid'));
}
if(Session::has('wapwid')){
	$set = new Model('weiqi_wifi_keyword');
	$set->find(array('wid'=>Session::get('wapwid')));
}
