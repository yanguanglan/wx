<?php

//查是否存在这个路由器
$route = new Model('cms_route');
$route->find(array('mac' => strtolower($_GET['gwmac'])));

//直接访问仅显示他们自己的广告
if(!$route->has_id()){
    Redirect::to('wifi/welcome.html');
}else{
    
    Redirect::to("wifi/ad-1-{$route->m_id}.html");
}