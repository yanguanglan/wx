<?php
$wid = Session::get('wid');
$uid = Session::get('uid');
$set = new Model('weiqi_shop_keyword');
$set->find(array('wid'=>$wid));

$s = new Model('weiqi_shop_style');
$s->find(array('wid'=>Session::get('wid')));
switch($s->s_style)
{
case 0:
$url = Conf::$http_path."weiqiwx/index.php?g=Wap&m=Product&a=index&token=".$wid."&wid=".Session::get('wid');
break;
case 1:
$url = Conf::$http_path."weiqiwx/index.php?g=Wap&m=Product&a=index&token=".$wid."&wid=".Session::get('wid');
break;
case 2:
$url = Conf::$http_path."weiqiwx/index.php?g=Wap&m=Product&a=index&token=".$wid."&wid=".Session::get('wid');
break;
}
if($set->try_post()){
	$set->wid = $wid;
	$set->uid = $uid;
	$set->save();
	tusi('设置成功');
}
