<?php
   $m = new Model('webmenustyle');
   $m->find(array('wid'=> Session::get('wid')));
   
if(!empty($_POST['RadioGroup1'])){
	$m->wid = Session::get('wid');
	$m->style = $_POST['RadioGroup1'];
	$m->save();
	tusi("����ɹ�");
	Redirect::to('set.html');
}