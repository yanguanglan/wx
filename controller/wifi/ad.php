<?php

$type = Request::get(1);
$model = new Model('cms_adv');
$model->find(array('m_id' => Request::get(2), 'type' => Request::get(1)));
$array = array(1=>'关注提示页。',2=>'成功提示页。');
//如果没设置，则显示这个
if(!$model->has_id()){
    echo '请设置'.$array[$type];
    die();
}

/*
if($model->target){
        $url = $model->urllink;
        if(!preg_match('/http/', $model->urllink)){
            $url = 'http://'.$model->urllink;
        }
        Redirect::to($url,0);
        die();
}
*/
$adlink = addHttp($model->adlink, $type);
$wxlink = addHttp($model->wxlink, $type);



function addHttp($link = '', $style){
    if(!$link || $style == 1){
        return '###';
    }
    if(!preg_match('/http/', $link)){
        $link = 'http://'.$link;
    }
    return $link;
}