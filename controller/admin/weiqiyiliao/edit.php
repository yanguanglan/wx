<?php
$m = new Model('weiqi_yiliao_record');
$m->find(array('wid'=>Session::get('wid'),'id'=>Request::get('1')));
$a['0'] = $m->form0;
$a['1'] = $m->form1;
$a['2'] = $m->form2;
$a['3'] = $m->form3;
$a['4'] = $m->form4;
$a['5'] = $m->form5;
$a['6'] = $m->form6;
$a['7'] = $m->form7;
$a['8'] = $m->form8;
$a['9'] = $m->form9;
$a['10'] = $m->form10;
$a['11'] = $m->form11;
$a['12'] = $m->form12;
$a['13'] = $m->form13;
$a['14'] = $m->form14;
$a['15'] = $m->form15;
$f=array("0"=>"未到诊","1"=>"已到诊");
if($m->try_post()){
	if($m->state=="已到诊")
	{
	$m->state=1;
	}elseif($m->state=="未到诊"){
	$m->state=0;
	}
	$m->save();
	tusi("保存成功");
    Redirect::delay_to('chaxun.html',2);
}
$s = new Model('weiqi_yiliao_set');
$s->find(array('wid'=>Session::get('wid')));
$ddx = json_decode($s->bookingset);