<?php
access_control();
$res = Request::json();
$wid = Session::get('wid');
if($wid == $_GET['wid'])
{
$wxweb = new Model('wxweb');
$wxweb->delete(array('wid'=>$wid));
foreach ($res as $r){
	$r['wid'] = $wid;
	$wxweb = new Model('wxweb');
	$wxweb->save($r);
}
Response::write('ok');
}