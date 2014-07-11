<?php

$word = new Model('cms_route');   //首次关注表
$wid = Session::get('wid');
$uid = Session::get('uid');
$id  = Request::get(2);
if ($word->try_post()) {
    $id = $_POST['id'];
    $word->find($id);
    $word->update_at = time();
    $word->mac = strtolower($_POST['mac']);
    $word->save();
    Redirect::delay_to('list', 0);
    die();
}

$word->find(Request::get(2));
if ('del' == Request::get(1)) {
    if ($word->m_id == $uid) {
        $word->remove();
        Redirect::delay_to('list', 0);
        die();
    }
}


//自动回复的单图文列表
/*$db  = DB::get_db();
$sql ="select t.id,t.m_id,t.m_name,t.mac,t.status,t.current_num,t.create_at,t.update_at from cms_route t where t.id = {$id}";
$rs  = $db->query($sql);
 * */

