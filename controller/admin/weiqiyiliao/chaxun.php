<?php
$s = new Model('weiqi_yiliao_set');
$s->find(array('wid'=>Session::get('wid')));
$ddx = json_decode($s->bookingset);
$m = new Model('weiqi_yiliao_record');
$arr = $m->find(array('wid'=>Session::get('wid')))->list_all();
$fff=array("0"=>"未到诊","1"=>"已到诊");
