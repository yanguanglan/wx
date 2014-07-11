<?php

//关注提示页
$model = new Model('cms_adv');
if($model->try_post()) {
    $model->m_id  = Session::get('wid');
    $model->type = 1;
    $model->create_at = time();
    $model->adimg = trim($_POST['adimg']);
    $model->wximg = trim($_POST['wximg']);
    $model->save();
}
$model->find(array('m_id' => Session::get('wid'), 'type' =>1));