<?php
if(Request::get('wxid')){
	Session::set('wxid',Request::get('wxid'));
}

$wid = Request::part(1);
$m1 = new Model('weiqi_web_music');
$m1->find(array('wid'=>$wid));
$banquan = new Model('weiqi_web_banquan');
$banquan->find(array('wid'=>$wid));
$hb = new Model('weiqi_web_bg');
$hb->find(array('wid'=>$wid));
$f = new Model('wwz_keyword');
$f->find(array('wid'=>$wid));
Session::set('wapwid',$wid);
$pageid = Request::part(2);
$m = new Model('wxweb');
$m->find(array('wid'=>$wid,'uuid'=>$pageid));
$xg = array('1'=>"xg",'2'=>"snow",'3'=>"meigui",'4'=>"realLeaf");
if($m->has_id()){
	
}else{
	Redirect::to_404();
}