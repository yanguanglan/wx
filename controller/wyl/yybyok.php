<?php
if(Request::get('wxid')){
	Session::set('wxid',Request::get('wxid'));
}
if(Request::get('wid')){
	Session::set('wapwid',Request::get('wid'));
}
if(Session::has('wxid') && Session::has('wapwid')){
	$h = new Model('weiqi_yiliao_set');
	$h->find(Request::get(1));
$m = new Model('weiqi_yiliao_record');
//$m->find(array('wid'=>Session::get('wapwid'),'wxid'=> Session::get('wxid'),'state'=>'0'));
if($m->has_id()){
	//Response::write('rep');
}
if($m->try_post()){
	$m->hid = Request::get(1);
	$m->wid = Session::get('wapwid');
	$m->wxid = Session::get('wxid');
	$m->ctime = DB::raw('now()');
	$ddxjson = json_decode($h->bookingset);
	foreach ($ddxjson as $dj){
	$body .= "<br>".$dj->name.": %s";
	}
	$body = "预约内容：".$body."<br>".$h->tit;
	$re = sprintf($body,$m->form0,$m->form1,$m->form2,$m->form3,$m->form4,$m->form5,$m->form6,$m->form7,$m->form8,$m->form9,$m->form10,$m->form11,$m->form12,$m->form13,$m->form14,$m->form15);
		if($n->noticeemailon){
	$f = SendMail::normal_send("reg",trim($h->noticeemail),"新预约",trim($h->tit),trim($re));
	}
		$m->save();
	 	Response::write($f);
}
}else{
die();
}