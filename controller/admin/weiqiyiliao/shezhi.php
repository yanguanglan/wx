<?php
$m = new Model('weiqi_yiliao_set');
$m->find(array('wid'=>Session::get('wid')));
if($m->has_id()){

}else{
	tusi('请先配置 ‘挂号设置’！');
}

if($m->try_post()){
	$m->wid = Session::get('wid');
	$m->save();
	tusi('保存成功');
}
if(empty($m->bookingset)){
	$m->bookingset = '[{"type":"text","name":"患者姓名","holder":"请输入您的名字","issys":"0"},{"type":"select","name":"性别","holder":"男|女","issys":"0"},{"type":"text","name":"联系电话","holder":"请输入您的电话","issys":"0"},{"type":"datetime","name":"预定日期","holder":"请输入预定日期","issys":"0"},{"type":"text","name":"联系地址","holder":"请输入联系地址","issys":"0"},{"type":"select","name":"预约科室","holder":"例如:神经科|妇科|皮肤科","issys":"0"},{"type":"select","name":"预约专家","holder":"例如:张三|李四|王二","issys":"0"},{"type":"select","name":"预约病种","holder":"例如:血管瘤|高血压|冠心病","issys":"0"},{"type":"textarea","name":"留言","holder":"输入留言内容","issys":"0"},{"type":"select","name":"备用下拉","holder":"","issys":"0"}]';
	}