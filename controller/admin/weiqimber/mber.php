<?php

if(Request::get(1) == "member"){
$url = '/weiqiwx/index.php?g=User&m=Member_card&a=index&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'));
}elseif(Request::get(1) == "chongzhi"){
$url = '/weiqiwx/index.php?g=User&m=Member&a=members&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'));
}elseif(Request::get(1) == "add"){
$url = '/weiqiwx/index.php?g=User&m=Member_card&a=design&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'));
}elseif(Request::get(1) == "dianyuan"){
$url = '/weiqiwx/index.php?g=User&m=Member_card&a=staff&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'));
}
$u = new Model('user');
$u->find(Session::get('uid'));
Redirect::to($url);
?>
