<?php
   $m = new Model('weiqi_web_menustyle');
   $m->find(array('wid'=> Session::get('wid')));
   
if(!empty($_POST['RadioGroup1'])){
	$m->wid = Session::get('wid');
	$m->style = $_POST['RadioGroup1'];
	$m->save();
	tusi("模板保存成功，自动跳转");
	Redirect::delay_to('set.html',1);
}