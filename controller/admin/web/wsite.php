<?php
$wid = Session::get('wid');
$mywwz= Conf::$http_path.'weiweb/'.$wid;
$myweb= Conf::$http_path.'weiweb/yulan.html?wid='.$wid;
$m = new SampleModel();