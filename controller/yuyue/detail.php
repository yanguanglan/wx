<?php
if(Request::get('wxid')){
	Session::set('wxid',Request::get('wxid'));
}
if(Request::get('wid')){
	Session::set('wid',Request::get('wid'));
}
if(Session::has('wxid') && Session::has('wid')){
$h = new Model('micro_hotel');
$h->find(Request::get(1));
}else{
die();
}
function hrefto($href){
	return trim($href)== '' ? 'javascript:;':$href;
}
