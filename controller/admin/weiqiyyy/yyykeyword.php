<?php
$wid = Session::get('wid');
$uid = Session::get('uid');
$set = new Model('weiqi_yyy_keyword');
$set->find(array('wid'=>$wid));
$mywwz = Conf::$http_path."/weiqiwx/index.php?g=Wap&m=Shakedo&a=index&token=".$wid."&phone=".$tel;
if($set->try_post()){
	$set->wid = $wid;
	$set->uid = $uid;
	$set->save();
	tusi('设置成功');
}
