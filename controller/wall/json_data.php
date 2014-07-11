<?php
$wid = Session::get('wapwid');
$m = new Model('micro_wall_content');
$ddres = $m->where(array('wid'=>$wid,'check' => 1))->list_all();
$count = $m->where(array('wid'=>$wid))->count();
$last_id = (int)$_GET['last_id'];
$arr = array();
foreach($ddres as $a => $b)
{
$num =(int)$a;
if($num>$last_id){
$m = new Model('micro_wall_user_name');
$wxid = trim($b->wxid);
$m->find(array('wxid'=>$wxid));
			$s['id'] = $num;
			$s['num'] = -1;
			$s['status'] = 0;
			$s['check'] = 1;
			$s['avatar'] = $m->img;
			$s['wxid'] = $m->name;
			$s['nickname'] = $m->name;
			$s['content'] = $b->content;
			$arr[] = $s;
}
}
echo json_encode($arr);