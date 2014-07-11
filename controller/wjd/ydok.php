<?php
if(Request::get('wxid')){
	Session::set('wxid',Request::get('wxid'));
}
if(Request::get('wid')){
	Session::set('wapwid',Request::get('wid'));
}
$h = new Model('micro_hotel');
$h->find(Request::get(1));
$r = new Model('micro_hotel_room');
$r->find(Request::get(2));
$m = new Model('micro_hotel_record');
$m->find(array('hid'=>Request::get(1),'rid'=>Request::get(2),'state'=>'0'));
if($m->has_id()){
	Response::write('rep');
}
if($m->try_post()){
	$m->rid = Request::get(2);
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