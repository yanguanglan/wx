<?php
$lbs = new Model('lbs');
$mdres = $lbs->where(array('wid'=>Session::get('wapwid'),'istag'=>'1'))->list_all();