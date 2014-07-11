<?php
/**
短信设置
**/
class SmsAction extends UserAction{
	public $token;
	public $wecha_id;
	
	public function _initialize() {
		parent::_initialize();
		$this->token= session('token');
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->wecha_id){
			$this->wecha_id='';
		}
		$this->assign('token',$this->token);
		$this->assign('wecha_id',$this->wecha_id);
	}
	public function index(){
		$db=M('sms_set');
		$where['uid']=$_SESSION['uid'];
		$where['token']=$_SESSION['token'];
		$res=$db->where($where)->find();
		$this->assign('smsdata',$res);
		$this->display();
	}
	public function sends(){
		$db=M('sms_set');
		$where['token']=$_SESSION['token'];
		$set=$db->where($where)->find();
		$txt = $this->Send_sms($set['phone'],"您好，测试短信接口功能短信");
		if($txt){
			$this->success('短信发送成功','/admin/weiqiother/Sms.html');
		}else{
			$this->error('短信发送失败','/admin/weiqiother/Sms.html');
		}
	}
	public function insert(){
		$db=M('sms_set');
		$where['uid']=$_SESSION['uid'];
		$where['token']=$_SESSION['token'];
		$res=$db->where($where)->find();
		if($res==false){
			$where['phone']=$this->_post('phone','trim');
			$where['account']=$this->_post('account','trim');
			$where['status']=$this->_post('status','trim');
			if(isset($_POST['password'])){
				$where['password']=$this->_post('password','trim');
			}		
			if($where['account']==false){$this->error('帐号必须填写');}
			$id=$db->data($where)->add();
			if($id){
				$this->success('添加成功',U('Sms/index',array('token'=>$this->token)));
			}else{
				$this->error('添加失败',U('Sms/index',array('token'=>$this->token)));
			}
		}else{
			$where['id']=$res['id'];
			$where['phone']=$this->_post('phone','trim');
			$where['account']=$this->_post('account','trim');
			$where['status']=$this->_post('status','trim');
			if(isset($_POST['password'])){
				$where['password']=$this->_post('password','trim');
			}		
			if($db->save($where)){
				$this->success('更新成功',U('Sms/index',array('token'=>$this->token)));
			}else{
				$this->error('更新失败',U('Sms/index',array('token'=>$this->token)));
			}
		}
	}
}
?>