<?php


access_control();
$uid = Session::get('uid');
$wid = Session::get('wid');
$keyWord = new Model('cms_route');

$tj = new SampleModel();

$where = $tj->load_array_from_get();

$where['m_id'] = $wid;

$p = new Pagination();
$rs = $p->model_list($keyWord->where($where)->order('id desc'));

//关键词删除操作
if (Request::get()) {
    $kw = new Model('cms_route');
    // delete single record
    if (Request::get(1) == 'del') {
        $kw->id(Request::get(2))->remove();
        tusi('操作成功');
        Redirect::delay_to("list.html", 1);
    }
    // To delete multiple records
    if ($_GET['type'] == 'dels') {
        $ids = $_GET['ids'];
        $id_s = explode(',', $ids);
        foreach ($id_s as $v) {
            $rs = $kw->delete(array('id' => $v));
        }
        Response::write(1);
    }
}

function getTitleById($id1) {

    $res_con = new Model('res_content');
    $title = $res_con->field('title')->find(array('rid' => $id1, 'ord' => 1));
    echo $title->title;
}
