<?php
$u = new SampleModel();
if($u->try_post()){
	if(!$u->un){
		tusi('你的用户名不存在');
	}
	if(!$u->pwd){
		tusi('你的密码错误');
	}
}