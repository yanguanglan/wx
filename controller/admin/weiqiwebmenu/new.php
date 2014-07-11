<?php
   $m = new Model('weiqi_web_menu');
   $m->find(array('id'=>Request::get(1),'wid'=> Session::get('wid')));
if($m->try_post()){
	$m->wid = Session::get('wid');
	$m->save();
	tusi("保存成功，自动跳转");
	Redirect::delay_to('set.html',1);
}