<?php
if(Request::get('wid')){
	Session::set('wapwid',Request::get('wid'));
}
$wid = Session::get('wapwid');
$m = new Model('micro_wall_config');
$m->find(array('wid'=>$wid));
$color_str = 'new Array(';
foreach((array)$m->items_color as $r){
    $color_str .= ''.$r.',';
}
$color_str = rtrim($color_str, ",").');';

$last_id = 0;

if(!$m->show_last) try{
    $pdobj = getpdobj();
    if($pdobj){
        $last_id = (int)$pdobj->query('select max(id) from `micro_wall_config`')->fetchColumn();
    }else{
        die('-02');
    }
}catch(PDOException $e){
    die('-1');
}
