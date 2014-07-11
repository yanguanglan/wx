<?php
   $m = new Model('webmenu');
   $m->find(array('id'=>Request::get(1),'wid'=> Session::get('wid')));
if($m->try_post()){
	$m->wid = Session::get('wid');
	$m->save();
	tusi("保存成功");
	Redirect::to('set.html');
}