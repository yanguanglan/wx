<?php
if(Request::get('wid')){
	Session::set('wid',Request::get('wid'));
}
$wid = Session::get('wid');
$uid = Session::get('uid');
$set = new Model('weiqi_diy_keyword');
$set->find(array('wid'=>$wid));
