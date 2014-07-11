<?php

$p = new Model('cms_route');
if ($p->try_post()) {
    $p->mac = htmlspecialchars(strtolower($_POST['mac']));
    $p->create_at = time();
    $p->update_at = time();
    $p->m_name = session::get('un');
    $p->m_id = session::get('wid');
    $p->save();
    Redirect::delay_to('list', 1);
}