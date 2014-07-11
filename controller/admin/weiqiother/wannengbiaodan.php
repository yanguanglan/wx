<?php
$u = new Model('user');
$u->find(Session::get('uid'));
$url = '/weiqiwx/index.php?g=User&m=Selfform&a=index&dining=1&wxgjuid='.Session::get('uid').'&wxgjpwd='.md5($u->pwd).'&token='.(Session::get('wid'))."&dining=1";
$u = new Model('user');
$u->find(Session::get('uid'));
Redirect::to($url);