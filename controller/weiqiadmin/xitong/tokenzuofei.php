<?php
$mu = Session::get('mu');
$u = new Model('pubs');
$tj = new SampleModel('tj');
$where = $tj->load_array_from_get();
//name 模糊查询
if(trim($where['uid'])!=''){
	$where['uid@~'] = $where['uid'];	
}
unset($where['uid']);
$uname = new Model('user');
$uname_arr = $uname->map_array('id','un');
$where['isval'] = '1';
$p = new Pagination();
$jgres = $p->model_list($u->where($where)->order('id desc'));
