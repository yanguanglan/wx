<?php
$u = new Model('user');
$u->find(Session::get('uid'));
$url = '/weiqiwx/index.php?g=User&m=Payment&a=index&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'));
Redirect::to($url);