<?php
$m = new Model('weiqi_web_menu');
$s = $m->where(array('wid' => Request::get(1)))->list_all();
   $m = new Model('weiqi_web_menustyle');
   $m->find(array('wid'=> Request::get(1)));
Page::view($m->style);