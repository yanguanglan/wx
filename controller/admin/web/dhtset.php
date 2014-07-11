<?php
$wxwebdh = new Model('wxwebdh');
$wxwebdh -> find(array('wid' => Session :: get('wid')));
if ($wxwebdh -> try_post()){
    $wxwebdh -> wid = Session :: get('wid');
    $wxwebdh -> status = $_POST['status'];
    $wxwebdh -> dhtid = $_POST['dhtid'];
    $wxwebdh -> dhtext = $_POST['dhtext'];
    $wxwebdh -> dhstatus = $_POST['dhstatus'];
    if($wxwebdh -> save()){
        tusi('保存成功');
        Redirect :: to('dhtset');
    }else{
        tusi("保存失败");
    }
}else{
    Page :: view('dhtset');
}
?>