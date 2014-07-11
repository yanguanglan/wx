<?php
access_control();
$res = Request::json();
$wid = Session::get('wid');
if($wid == Request::get(1) || Request::get(2) == "1")
{
$wxweb = new Model('wxweb');
$wxweb->delete(array('wid'=>Request::get(1)));
foreach ($res as $r){
	$r['wid'] = Request::get(1);
	$wxweb = new Model('wxweb');
	$wxweb->save($r);
}
Response::write('ok');
}else{
Response::write('nos');
}