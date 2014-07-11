<?php
/**
邮箱设置
**/
class EmailAction extends UserAction{
	public $token;
	public $wecha_id;
	
	public function _initialize() {
		parent::_initialize();
		$this->token= $this->token;
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->wecha_id){
			$this->wecha_id='';
		}
		$this->assign('token',$this->token);
		$this->assign('wecha_id',$this->wecha_id);
	}
	public function index(){
		$set=M('email_set')->where(array('token'=>$this->token,'uid'=>session('uid')))->find();
		$db=M('email_set');
		if(IS_POST){
			$_POST['uid']=SESSION('uid');
			$_POST['token']=SESSION('token');
			if($set==false){
				if ($db->create() === false) {
					$this->error($db->getError());
				} else {
					$id = $db->add();
					if ($id == true) {
						$this->success('操作成功', U('Email/index',array('token'=>$this->token)));
					} else {
						$this->error('操作失败', U('Email/index',array('token'=>$this->token)));
					}
				}
			}else{
				$_POST['id']=$set['id'];
				if ($db->create() === false) {
					$this->error($db->getError());
				} else {
					$id = $db->save();
					if ($id == true) {
						$this->success('操作成功',  U('Email/index',array('token'=>$this->token)));
					} else {
						$this->error('操作失败',  U('Email/index',array('token'=>$this->token)));
					}
				}	
			}
		}else{
			$this->assign('set',$set);
			$this->display();
		}
	}
	//测试发送邮件
	public function send(){
		$set=M('email_set')->where(array('token'=>$this->token))->find();
		$re = $this->Send_email("微信平台测试邮件","这是测试邮件内容",$set['emails'], "微信平台测试");
		if($re){
			$this->success('邮件发送成功','/admin/weiqiother/Email.html');
		}else{
			$this->error('邮件发送失败','/admin/weiqiother/Email.html');
		}
	}
}
?>