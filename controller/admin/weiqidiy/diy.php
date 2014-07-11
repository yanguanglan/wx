<?php
$wid = Session::get('wid');
$uid = Session::get('uid');
$set = new Model('weiqi_diy_keyword');
$mywwz = Conf::$http_path."/weiqidiy/diy.html?wid=".$wid;
$set->find(array('wid'=>$wid));
if($set->try_post()){
	$set->wid = $wid;
	$set->uid = $uid;
	$set->save();
	tusi('保存成功');
}
?>