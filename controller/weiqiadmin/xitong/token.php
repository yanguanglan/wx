<?php
$mu = Session::get('mu');
$u = new Model('pubs');
$uname = new Model('user');
$uname_arr = $uname->map_array('id','un');
$where['isval'] = '1';
$p = new Pagination();
$jgres = $p->model_list($u->where($where)->order('id desc'));
