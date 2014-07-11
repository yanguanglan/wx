<?php
$wid = Session::get('wid');
$web = new Model('weiqi_web_beifen');
$beifen = $web->find(array('wid'=>$wid,'time'=>Request::get(1)));
$a = json_decode($beifen->content);
$m = $a[0];
//盛世源码 销售地址weiqiwx.tao bao.com
?>