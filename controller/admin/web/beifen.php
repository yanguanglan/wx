<?php
$wid = Session::get('wid');
$wxweb = new Model('weiqi_web_beifen');
$m = $wxweb->where(array('wid'=>$wid))->list_all();
if(Request::get(1) == "tobe"){
//备份
$beifen = new Model('weiqi_web_beifen');
$web = new Model('wxweb');
$json=$web->where(array('wid'=>$wid))->order('id')->list_all_array();
$beifen->content = json_encode($json);
$beifen->wid = $wid;
$beifen->time = time();
$beifen->save();
$tusi = "备份成功";
Redirect::to('beifen');
}elseif(Request::get(1) == "del"){
//删除
$web = new Model('weiqi_web_beifen');
$web->delete(array('wid'=>$wid,'time'=>Request::get(2)));
$tusi = "删除成功";
Redirect::to('beifen');
}elseif(Request::get(1) == "use"){
//还原
$web = new Model('weiqi_web_beifen');
$beifen = $web->find(array('wid'=>$wid,'time'=>Request::get(2)));
$res = json_decode($beifen->content);
if(!empty($wid)){
$web2 = new Model('wxweb');
$web2->delete(array('wid'=>$wid));
foreach ($res as $r){
	$web = new Model('wxweb');
	foreach($r as $l => $m)
	{
	$web->$l = $m;
	}
	$web->wid = $wid;
	$web->id = null;
	$web->save();
}
$tusi = "还原成功";
Redirect::to('wsite');
}else{
$tusi = "还原失败！请重新登录！";
}
}
if(!empty($tusi))
{
tusi($tusi);
}
?>