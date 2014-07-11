<?php
class WxpayAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $payConfig;
	public function _initialize() {
		parent::_initialize();
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		$this->orderid	= $this->_get('orderid');
		$this->order_db = M('product_cart');
		$this->order_list = M('product_cart_list');
		$order = M('product_cart')->where(array('orderid'=>$this->orderid))->find();
		if ($order){
			$order['ordername']="订单号：".$order['orderid'];
			if($order['dining'] ==1){
				$this->orders_url=U('Dining/dingdan',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'storeid'=>$order['storeid']));
			}else{
				$this->orders_url=U('Product/my',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'storeid'=>$order['storeid']));
			}
			$this->order=$order;
		}
		
		//微信支付
		$pay_config =M('payment')->where(array('token'=>$this->token,'pay_code'=>'wxpay'))->find();
		$pay_config = unserialize($pay_config['pay_config']);
		$wxpay_config['appId']=$pay_config['appId'];
		$wxpay_config['appKey']=$pay_config['appKey'];
		$wxpay_config['appSecret']=$pay_config['appSecret'];
		$wxpay_config['partnerId']=$pay_config['partnerId'];
		$wxpay_config['partnerKey']=$pay_config['partnerKey'];
		$this->wxpay_config = $wxpay_config;
	}
	public function pay(){
		import("@.ORG.Weixinpay.CommonUtil");
		import("@.ORG.Weixinpay.WxPayHelper");
		$order = $this->order;
		$wxpay_config = $this->wxpay_config;
		$commonUtil = new CommonUtil();
		$wxPayHelper = new WxPayHelper($this->wxpay_config['appId'],$this->wxpay_config['appKey'],$this->wxpay_config['partnerKey']);

		$wxPayHelper->setParameter("bank_type", "WX");
		$wxPayHelper->setParameter("body", $order['orderid']);
		$wxPayHelper->setParameter("partner", $wxpay_config['partnerId']);
		$wxPayHelper->setParameter("out_trade_no",$order['orderid']);
		$wxPayHelper->setParameter("total_fee", floatval($order['price'])*100);
		$wxPayHelper->setParameter("fee_type", "1");
		$wxPayHelper->setParameter("notify_url", C('site_url').'/wxpay/?g=Wap&m=Wxpay&a=notify_url');
		$wxPayHelper->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);
		$wxPayHelper->setParameter("input_charset", "GBK");
		$payurl=$wxPayHelper->create_biz_package();
		$this->assign('payurl',$payurl);
		$this->assign('returnUrl',$this->orders_url);
		$this->assign('order',$order);
		$this->display();
	}
	public function warning(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if(!empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			
			$this->logger("错误类型：".$postObj->ErrorType."错识描述：".$postObj->Description."错误详情：".$postObj->AlarmContent,'warning');
		}else{
			echo 'HTTP_RAW_POST_DATA Not Existed!';
		}
		
		echo 'success';	
	}
	public function payfeedback(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if(!empty($postStr)){
 			$this->logger($postStr,'feedback');
		}else{
			echo 'HTTP_RAW_POST_DATA Not Existed!';
		}
		echo 'success';		
	}
	public function getpackage(){
		echo 'success';	
	}
	//日志记录
	protected function logger($log_content,$kind)
	{
		$max_size = 100000;
		$log_filename = LOG_PATH."wxpay_".$kind."_log.xml";
		if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
		file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
	}
	//同步数据处理
	public function notify_url (){
		F('pay',$_GET);
		$out_trade_no = $this->_get('out_trade_no');
		if(intval($_GET['total_fee'])&&!intval($_GET['trade_state'])) {
			$product_cart_model=M('product_cart');
			$order=$product_cart_model->where(array('orderid'=>$out_trade_no))->find();
			F('order',$order);
			if (!$this->wecha_id){
				$this->wecha_id=$order['wecha_id'];
			}
			if($order){
				if($order['paid']==1){exit('该订单已经支付,请勿重复操作');}
				$returnRs=array();
				$returnRs['transaction_id']=$this->_get('transaction_id');
				$returnRs['paid']=1;
				$product_cart_model->where(array('orderid'=>$out_trade_no))->save($returnRs);
				
				/************************************************/
				$member_card_create_db=M('Member_card_create');
				$userCard=$member_card_create_db->where(array('token'=>$order['token'],'wecha_id'=>$order['wecha_id']))->find();
				$member_card_set_db=M('Member_card_set');
				$thisCard=$member_card_set_db->where(array('id'=>intval($userCard['cardid'])))->find();
				$set_exchange = M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
				//
				$arr['token']=$order['token'];
				$arr['wecha_id']=$order['wecha_id'];
				$arr['expense']=$order['price'];
				$arr['time']=time();
				$arr['cat']=99;
				$arr['staffid']=0;
				$arr['score']=intval($set_exchange['reward'])*$order['price'];
				M('Member_card_use_record')->add($arr);
				$userinfo_db=M('Userinfo');
				$thisUser = $userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->find();
				$userArr=array();
				$userArr['total_score']=$thisUser['total_score']+$arr['score'];
				$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
				$userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->save($userArr);
				
			}else{
				exit('订单不存在：'.$out_trade_no);
			}
		}else {
			exit('付款失败');
		}
	}
	
}
?>