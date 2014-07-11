<?php
$route = new Model('cms_route');
$route->find(array('mac' => strtolower($_GET['mac'])));

$m_id = $route->m_id;

$db  = DB::get_db();
$sql ="select t.id,t.m_id,t.url,t.create_at from cms_link t where t.m_id = {$m_id} and t.type = 1";

$rs  = $db->query($sql);

if(isset($rs[0]['url']) && !empty($rs[0]['url'])){
    Redirect::to($rs[0]['url'],1);
    die();
}