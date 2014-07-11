<?php
class PrinterAction extends UserAction{
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
		$this->printer_model = M('printer_set');
		$db=M('printer_set');
		//$where['uid']=$this->_get('wxgjuid');
		$where['token']=$_SESSION['token'];
		$this->printer_set =$db->where($where)->find();
		//$this->printer_set = M('printer_set')->where(array('token'=>$this->token,'uid'=>session('uid')))->find();
	}
	public function index(){
		$set=$this->printer_set;
		$db=$this->printer_model;
		if(IS_POST){
			$_POST['uid']=SESSION('uid');
			$_POST['token']=SESSION('token');
			if($set==false){
				if ($db->create() === false) {
					$this->error($db->getError());
				} else {
					$id = $db->add();
					if ($id == true) {
						$this->success('操作成功', U('Printer/index',array('token'=>$this->token)));
					} else {
						$this->error('操作失败');
					}
				}
			}else{
				$_POST['id']=$set['id'];
				if ($db->create() === false) {
					$this->error($db->getError());
				} else {
					$id = $db->save();
					if ($id == true) {
						$this->success('操作成功', U('Printer/index',array('token'=>$this->token)));
					} else {
						$this->error('操作失败');
					}
				}	
			}
		}else{
			$this->assign('set',$set);
			$this->display();
		}
	}
	//测试打印
	public function test(){
		//设置打印服务器开始
		$db=M('printer_set');
		//$where['uid']=$_SESSION['uid'];
		$where['token']=$_SESSION['token'];
		$printer_set=$db->where($where)->find();
		define('MEMBER_CODE', $printer_set['memberCode']);
		define('FEYIN_KEY', $printer_set['feiyin_key']);
		define('DEVICE_NO', $printer_set['deviceNo']);
		define('FEYIN_HOST','my.feyin.net');
		define('FEYIN_PORT', 80);
		$str='
    订餐打印测试
	
条目      单价（元）   数量
----------------------------
盛世源码微信     600.0       1
炸鸡        20.0        1
盛世源码(赠品)   0.0       1

备注：要微辣的哦。
----------------------------
合计：620.0元 

送货地址：*******
联系电话：186371****
订购时间：'.date("Y-m-d H:i:s");
		$msgInfo=array(
			'memberCode'=>MEMBER_CODE,
			'msgDetail'=>$str,
			'deviceNo'=>DEVICE_NO,
			'msgNo'=>time()+1,
			'reqTime' => number_format(1000*time(), 0, '', '')
		);
		$content = $msgInfo['memberCode'].$msgInfo['msgDetail'].$msgInfo['deviceNo'].$msgInfo['msgNo'].$msgInfo['reqTime'].FEYIN_KEY;
		$msgInfo['securityCode'] = md5($content);
		$msgInfo['mode']=2;
		$client = new HttpClient(FEYIN_HOST,FEYIN_PORT);
		if($client->post('/api/sendMsg',$msgInfo)){
			$printstate=$client->getContent();
		}
		if($printstate==0){
			$this->success('打印成功', '/admin/weiqiother/Prinet.html');
		}else{
			$this->error('打印失败，错误代码：'.$printstate,'/admin/weiqiother/Prinet.html');
		}
	}
}
?>