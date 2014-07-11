<?php
if(Request::get('wid')){
	Session::set('wid',Request::get('wid'));
}
$wid = Session::get('wid');
$uid = Session::get('uid');
$set = new Model('wxweb');
$set->find(array('wid'=>$wid));
