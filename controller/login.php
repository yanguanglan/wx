<?php
$u = new SampleModel();
if($u->try_post()){
	if(!$u->un){
		tusi('����û���������');
	}
	if(!$u->pwd){
		tusi('����������');
	}
}