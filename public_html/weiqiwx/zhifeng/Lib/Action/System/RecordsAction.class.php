<?php
class RecordsAction extends BackAction{
	public function index(){
		$records=M('indent');
		//$db=M('Users');
		$count=$records->count();
		$page=new Page($count,25);
		$show= $page->show();
		$info=$records->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		$this->assign('page',$show);
		$this->assign('info',$info);
		$this->display();
	}
	public function send(){
		$money=$this->_get('price','intval');
		$data['id']=$this->_get('uid','intval');
	//	dump($money);exit;
		if($money!=false&&$data['id']!=false){
			//dump($money);exit;
			$back=M('Users')->where($data)->setInc('money',$money);
			$status=M('Indent')->where(array('id'=>$this->_get('iid','intval')))->setField('status',2);
			if($back!=false&&$status!=false){
				$this->success('充值成功',U('Records/index'));
			}else{
				$this->error('充值失败');
			}
		}else{
			$this->error('非法操作');
		}
	}
	//删除记录
	public function del(){
		$id=$this->_get('iid','intval');
	$d=M('Indent')->where(array('id'=>$id))->delete();
		if($d){
			$this->success('操作成功',U('Records/index'));
		}else{
			$this->error('操作失败 ');
		}
	}
	//查询搜索	
	public function search(){
		$name=$this->_post('name');
		$type=$this->_post('type');
		switch($type){
			case 1:
			$data['uname']=$name;
			break;
			case 2:
			$data['id']=$name;
			break;
		}
		//dump($where);
		$list=M('indent')->where($data)->select();
		$this->assign('info',$list);
		$this->display('index');
	
	}
	
}