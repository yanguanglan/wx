<?php

if(Request::get(1) == "fl")
{
$url = '/weiqiwx/index.php?g=User&m=Product&a=cats&dining=0&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'));
}elseif(Request::get(1) == "sp"){
$url = '/weiqiwx/index.php?g=User&m=Product&a=index&dining=0&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'));
}elseif(Request::get(1) == "dd"){
$url = '/weiqiwx/index.php?g=User&m=Product&a=orders&dining=0&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'));
}elseif(Request::get(1) == "lbs"){
$url = '/weiqiwx/index.php?g=User&m=Company&a=index&dining=1&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'));
}
$u = new Model('user');
$u->find(Session::get('uid'));
Redirect::to($url);
?>