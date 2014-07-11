<?php
class PayAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $pay_config;
	public $orders_url;
	public $wapalipay_config;
	public $zfalipay_config;
	public $wxpay_config;
	public function _initialize() {
		parent::_initialize();
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		$this->orderid	= $this->_get('orderid');
		$this->order_db = M('product_cart');
		$this->order_list = M('product_cart_list');
		$order = M('product_cart')->where(array('orderid'=>$this->orderid))->find();
		if ($order){
			/*获得订单产品
			if($order['diningtype'] ==3){
				$order_name=M('dining_tables')->where(array('id'=>$order['tableid']))->getField('name');
			}else{
				$products=$this->order_list->where(array('cartid'=>$order['id'],'token'=>$this->token))->select();
				$order_name='';
				foreach($products as $v){
					if($v['goodstype']=='dining'){
						$product_name = M('dining')->where(array('id'=>$v['productid']))->getField('name');
					}else{
						$product_name = M('product')->where(array('id'=>$v['productid']))->getField('name');
					}
					$order_name .=$product_name.'/';
				}
			}
			$order['ordername']= rtrim($order_name,'/');*/
			$order['ordername']="订单号：".$order['orderid'];
			if($order['dining'] ==1){
				$this->orders_url=U('Dining/dingdan',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'storeid'=>$order['storeid']));
			}else{
				$this->orders_url=U('Product/my',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'storeid'=>$order['storeid']));
			}
			$this->order=$order;
		}
		//手机支付配置
		$pay_config =M('payment')->where(array('token'=>$this->token,'pay_code'=>'wapalipay'))->find();
		$pay_config = unserialize($pay_config['pay_config']);
		$wapalipay_config['seller_email'] = trim($pay_config['account']);
		$wapalipay_config['partner']		= trim($pay_config['pid']);
		$wapalipay_config['key']			= trim($pay_config['key']);
		$wapalipay_config['sign_type']    = strtoupper('MD5');
		$wapalipay_config['input_charset']= strtolower('utf-8');
		$wapalipay_config['cacert']    = EXTEND_PATH.'Vendor\\Malipay\\cacert.pem';
		$wapalipay_config['transport']    = 'http';
		$this->wapalipay_config = $wapalipay_config;
		//免签支付宝配置
		$pay_config =M('payment')->where(array('token'=>$this->token,'pay_code'=>'zfalipay'))->find();
		$pay_config = unserialize($pay_config['pay_config']);
		$zfalipay_config['seller_email'] = C('alipay_name');
		$zfalipay_config['partner']		= C('alipay_pid');
		$zfalipay_config['key']			= C('alipay_key');
		$zfalipay_config['sign_type']    = strtoupper('MD5');
		$zfalipay_config['input_charset']= strtolower('utf-8');
		$zfalipay_config['cacert']    = getcwd().'\\zhifeng\\Lib\\ORG\\Alipay\\cacert.pem';
		$zfalipay_config['transport']    = 'http';
		$zfalipay_config['royalty_email'] = trim($pay_config['account']);
		$this->zfalipay_config = $zfalipay_config;
		//财付通配置
		$pay_config =M('payment')->where(array('token'=>$this->token,'pay_code'=>'tenpay'))->find();
		$pay_config = unserialize($pay_config['pay_config']);
		$tenpay_config['partnerId']=trim($pay_config['partnerId']);
		$tenpay_config['partnerKey']=trim($pay_config['partnerKey']);
		$tenpay_config['sign_type']=strtoupper('MD5');
		$tenpay_config['service_version']='1.0';
		$tenpay_config['input_charset']=strtolower('utf-8');
		$this->tenpay_config=$tenpay_config;
		//手机财付通配置
		$pay_config =M('payment')->where(array('token'=>$this->token,'pay_code'=>'waptenpay'))->find();
		$pay_config = unserialize($pay_config['pay_config']);
		$waptenpay_config['partnerId']=trim($pay_config['partnerId']);
		$waptenpay_config['partnerKey']=trim($pay_config['partnerKey']);
		$this->waptenpay_config=$waptenpay_config;
	}
	//手机支付宝
	public function wapalipay(){
        vendor('Malipay.alipay_submit','','.class.php');
		$alipay_config=$this->wapalipay_config;
		$order = $this->order;
		//返回格式
		$format = "xml";
		$v = "2.0";
		$req_id = date('Ymdhis');
		$notify_url=C('site_url').'/index.php/Wap/Pay/wapalipay_notify_url';
		$call_back_url=C('site_url').'/index.php/Wap/Pay/wapalipay_call_back_url';
		$seller_email = $alipay_config['seller_email'];
		$out_trade_no = $order['orderid'];
		$subject = $order['ordername'];
		$total_fee = $order['price'];
		
		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee></direct_trade_create_req>';
		
		//构造要请求的参数数组，无需改动
		$para_token = array(
				"service" => "alipay.wap.trade.create.direct",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);

		//URLDECODE返回的信息
		$html_text = urldecode($html_text);

		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);

		//获取request_token
		$request_token = $para_html_text['request_token'];


		/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '正确为您跳转到支付宝支付界面!');
		echo $html_text;	
	}
	//免签支付宝
	public function zfalipay(){
		import("@.ORG.Alipay.AlipaySubmit");
		$alipay_config=$this->zfalipay_config;
		$order = $this->order;
		
		//即时到帐支付类型
		$payment_type = "1";
		$notify_url=C('site_url').'/index.php/Wap/Pay/zfalipay_notify_url';
		$return_url=C('site_url').'/index.php/Wap/Pay/zfalipay_return_url';
		//付款金额
		$total_fee =floatval($order['price']);
		//计算分润
		$royalty_money= round($total_fee*0.985,2);
		$royalty_type = "10";
		$royalty_parameters = $alipay_config['royalty_email']."^".$royalty_money."^".$order['ordername'];
		
		$seller_email = $alipay_config['seller_email'];
		$out_trade_no = $order['orderid'];
		$subject = $order['ordername'];
		$partner	=  $alipay_config['partner'];
		$body = $order['ordername'];
		//商品展示地址
		$show_url = 'http://'.$_SERVER['HTTP_HOST'].U('Wap/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id));
		$anti_phishing_key = "";
		$exter_invoke_ip = "";

		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service" => "create_direct_pay_by_user",
			"partner" =>$partner,
			"payment_type"	=> $payment_type,
			"notify_url"	=> $notify_url,
			"return_url"	=> $return_url,
			"royalty_type"=> $royalty_type,
			"royalty_parameters"=>$royalty_parameters,
			"seller_email"	=> $seller_email,
			"out_trade_no"	=> $out_trade_no,
			"subject"	=> $subject,
			"total_fee"	=> $total_fee,
			"body"	=> $body,
			"show_url"	=> $show_url,
			"anti_phishing_key"	=> $anti_phishing_key,
			"exter_invoke_ip"	=> $exter_invoke_ip,
			"_input_charset"	=>trim(strtolower('utf-8'))
		);
		//print_r($parameter);exit;
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "进行支付");
		echo '正在跳转到支付宝进行支付...<div style="display:none">'.$html_text.'</div>';
	}
	//微信支付
	public function wxpay(){
		$pay_url=C('site_url')."/wxpay/?g=Wap&m=Wxpay&a=pay&token=".$this->token."&wecha_id=".$this->wecha_id."&orderid=".$this->orderid;
		header("location:".$pay_url);
	}
	public function wapalipay_call_back_url(){
        vendor('Malipay.alipay_notify','','.class.php');
		$alipay_config=$this->wapalipay_config;
		
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		
		$out_trade_no = $this->_get('out_trade_no');//商户订单号
		$trade_no = $this->_get('trade_no');//支付宝交易号
		$result = $this->_get('result');//交易状态
		
		$order_db = $this->order_db;
		$order = $order_db->where(array('orderid'=>$out_trade_no))->find();
		$order_token = $order['token'];
		$order_wecha_id = $order['wecha_id'];
		if($order['diningtype']){
			$back_url = U('Dining/dingdan',array('token'=>$order_token,'wecha_id'=>$order_wecha_id,'storeid'=>$order['storeid']));
		}else{
			$back_url = U('Product/my',array('token'=>$order_token,'wecha_id'=>$order_wecha_id,'storeid'=>$order['storeid']));
		}
		if($verify_result){//验证成功
			if($result == "success") {
				$data['paid'] = 1;
				$data['payment'] = 'wapalipay';
				$order_db->where(array('orderid'=>$out_trade_no))->save($data);
				$this->success('支付成功', $back_url);
			}
			else {
				$this->error('支付失败', $back_url);
			}
		}else{
			//$this->error('支付验证失败！', $back_url);
			$this->redirect($back_url);
		}
	}
	public function wapalipay_notify_url()
	{	
		vendor('Malipay.alipay_notify','','.class.php');
		$alipay_config=$this->wapalipay_config;
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();

		if($verify_result) {//验证成功
			$trade_status   = $_POST['trade_status']; //交易状态
			$out_trade_no = $_POST['out_trade_no']; //商户订单号
			$trade_no = $_POST['trade_no']; //支付宝交易号
			$trade_status = $_POST['trade_status']; //交易状态
			$total_fee =$_POST['total_fee']; //交易金额
			
			$order =  $this->order_db->where(array('orderid'=>$out_trade_no))->find();
			if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
				if($order['paid']==0){
					$data['paid'] = 1;
					$data['payment'] = 'wapalipay';
					$this->order_db->where(array('orderid'=>$out_trade_no))->save($data);
				}
				echo "success";	
			}	
		}
	}
	public function zfalipay_return_url(){
		import("@.ORG.Alipay.AlipayNotify");
		$alipay_config=$this->zfalipay_config;
		
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		
		$out_trade_no = $this->_get('out_trade_no');//商户订单号
		$trade_no = $this->_get('trade_no');//支付宝交易号
		$result = $this->_get('result');//交易状态
		
		$order_db = $this->order_db;
		$order = $order_db->where(array('orderid'=>$out_trade_no))->find();
		$order_token = $order['token'];
		$order_wecha_id = $order['wecha_id'];
		if($order['diningtype']){
			$back_url = U('Dining/dingdan',array('token'=>$order_token,'wecha_id'=>$order_wecha_id,'storeid'=>$order['storeid']));
		}else{
			$back_url = U('Product/my',array('token'=>$order_token,'wecha_id'=>$order_wecha_id,'storeid'=>$order['storeid']));
		}
		if($verify_result){//验证成功
			if($result == "success") {
				$data['paid'] = 1;
				$data['payment'] = 'zfalipay';
				$this->order_db->where(array('orderid'=>$out_trade_no))->save($data);
				$this->success('支付成功', $back_url);
			}
			else {
				$this->error('支付失败', $back_url);
			}
		}else{
			//$this->error('支付验证失败！', $back_url);
			$this->redirect($back_url);
		}
	}
	public function zfalipay_notify_url(){	
		import("@.ORG.Alipay.AlipayNotify");
		$alipay_config=$this->zfalipay_config;
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();

		if($verify_result) {//验证成功
			$trade_status   = $_POST['trade_status']; //交易状态
			$out_trade_no = $_POST['out_trade_no']; //商户订单号
			$trade_no = $_POST['trade_no']; //支付宝交易号
			$trade_status = $_POST['trade_status']; //交易状态
			$total_fee =$_POST['total_fee']; //交易金额
			
			$order =  $this->order_db->where(array('orderid'=>$out_trade_no))->find();
			if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
				if($order['paid']==0){
					$data['paid'] = 1;
					$data['payment'] = 'zfalipay';
					$this->order_db->where(array('orderid'=>$out_trade_no))->save($data);
					$this->order_db->where(array('orderid'=>$out_trade_no))->setField('paid',1);
				}
				echo "success";	
			}	
		}
	}
	public function yu(){
		$token=$this->token;
		$wecha_id=$this->wecha_id;
		$order = $this->order;
		//付款金额
		$total_fee =floatval($order['price']);
		$userInfo= M('Userinfo')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->find();
		$useryue=$userInfo['account']-$total_fee;
		if($useryue<0){
			$this->error('支付失败，请重试',$this->orders_url);
			//$this->error('余额不足,请充值',U('Card/recherge',array('token'=>$this->token,'wecha_id'=>$this->wecha_id)));
		}else{
			$useInfo['account']=$useryue;
			$trade_status=M('Userinfo')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->save($useInfo);
			if($trade_status){
					$data['paid'] = 1;
					$data['payment'] = 'yuepay';
					$this->order_db->where(array('orderid'=>$order['orderid']))->save($data);
					//消费记录
					$accountlog['token']=$token;
					$accountlog['wecha_id']=$wecha_id;
					$accountlog['price']=$total_fee;
					$accountlog['retype']=3;
					$accountlog['reuser']='会员购物';
					$accountlog['info']='订单号'.$order['orderid'];
					$accountlog['time']=time();
					M('member_accountlog')->add($accountlog);
					$backurl=$this->orders_url;
					$this->success('支付成功',$backurl);
			}else{
				$this->error('支付失败，请重试',$this->orders_url);
			}
		}
	}
	public function tenpay(){
		import("@.ORG.Tenpay.RequestHandler");
		$tenpay_config=$this->tenpay_config;
		$order = $this->order;
		$orderName =  $order['ordername'];
        if(!floatval($order['price']))exit('必须有价格才能支付');
        $total_fee = floatval($order['price']);		
        $out_trade_no = $order['orderid'];		
		$notify_url = C('site_url').'/index.php?g=Wap&m=Pay&a=tenpayshare_notify_url';
		//需http://格式的完整路径，不能加?id=123这类自定义参数
		//页面跳转同步通知页面路径
		$return_url = C('site_url').'/index.php?g=Wap&m=Pay&a=tenpayshare_return_url';
		//
		
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$key=$tenpay_config['partnerKey'];
		$partner=$tenpay_config['partnerId'];
		$reqHandler->setKey($key);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

		//----------------------------------------
		//设置支付参数
		//----------------------------------------
		$reqHandler->setParameter("partner", $partner);
		$reqHandler->setParameter("out_trade_no", $out_trade_no);
		$reqHandler->setParameter("total_fee", $total_fee * 100);  //总金额
		$reqHandler->setParameter("return_url", $return_url);
		$reqHandler->setParameter("notify_url", $notify_url);
		$reqHandler->setParameter("body", $orderName);
		$reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
		//用户ip
		$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//客户端IP
		$reqHandler->setParameter("fee_type", "1");               //币种
		$reqHandler->setParameter("subject",'weixin');          //商品名称，（中介交易时必填）

		//系统可选参数
		$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
		$reqHandler->setParameter("input_charset", "utf-8");   	  //字符集
		$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号

		//业务可选参数
		$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
		$reqHandler->setParameter("product_fee", "");        	  //商品费用
		$reqHandler->setParameter("transport_fee", "0");      	  //物流费用
		$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
		$reqHandler->setParameter("time_expire", "");             //订单失效时间
		$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
		$reqHandler->setParameter("goods_tag", "");               //商品标记
		$reqHandler->setParameter("trade_mode",1);              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
		$reqHandler->setParameter("transport_desc","");              //物流说明
		$reqHandler->setParameter("trans_type","1");              //交易类型
		$reqHandler->setParameter("agentid","");                  //平台ID
		$reqHandler->setParameter("agent_type","");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
		$reqHandler->setParameter("seller_id","");                //卖家的商户号



		//请求的URL
		$reqUrl = $reqHandler->getRequestURL();

		//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
		/**/
		$debugInfo = $reqHandler->getDebugInfo();
		header('Location:'.$reqUrl);
		//echo "<br/>" . $reqUrl . "<br/>";
		//echo "<br/>" . $debugInfo . "<br/>";
	}
	public function waptenpay(){
		import("@.ORG.Tenpay.RequestHandler");
		$waptenpay_config=$this->waptenpay_config;
		$order = $this->order;
        $orderName =  $order['ordername'];
        if(!floatval($order['price']))exit('必须有价格才能支付');
        $total_fee = floatval($order['price']);
        $out_trade_no = $order['orderid'];
        $req = new WapPayRequest($this -> payConfig['partnerkey']);
        $req -> setInSandBox(false);
        $req -> setAppid($this -> payConfig['partnerid']);
        $req -> setParameter('total_fee', $total_fee * 100);
        $req -> setParameter('body',  $orderName);
        $req -> setParameter('notify_url', $notify_url);
        $req -> setParameter('out_trade_no', $out_trade_no);
        $req -> setParameter('return_url', $return_url);
        $req -> setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);
        $req -> setParameter('request_token', $_GET['token']);
        echo $req -> getURL();
	}
	
	//同步数据处理
	public function tenpayshare_return_url (){
		import("@.ORG.Tenpay.ResponseHandler");
		
		if($resHandler->isTenpaySign()) {
			$notify_id = $resHandler->getParameter("notify_id");
			//商户订单号
			$out_trade_no = $resHandler->getParameter("out_trade_no");
			//财付通订单号
			$transaction_id = $resHandler->getParameter("transaction_id");
			//金额,以分为单位
			$total_fee = $resHandler->getParameter("total_fee");
			//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
			$discount = $resHandler->getParameter("discount");
			//支付结果
			$trade_state = $resHandler->getParameter("trade_state");
			//交易模式,1即时到账
			$trade_mode = $resHandler->getParameter("trade_mode");
			
			$order_db = $this->order_db;
			$order = $order_db->where(array('orderid'=>$out_trade_no))->find();
			$order_token = $order['token'];
			$order_wecha_id = $order['wecha_id'];
			if($order['diningtype']){
			$back_url = U('Dining/dingdan',array('token'=>$order_token,'wecha_id'=>$order_wecha_id,'storeid'=>$order['storeid']));
			}else{
				$back_url = U('Product/my',array('token'=>$order_token,'wecha_id'=>$order_wecha_id,'storeid'=>$order['storeid']));
			}
			
			if("0" == $trade_state) {
				$data['paid'] = 1;
				$data['payment'] = 'zfalipay';
				$this->order_db->where(array('orderid'=>$out_trade_no))->save($data);
				$this->success('支付成功', $back_url);
			}else{$this->error('支付失败', $back_url);}
		}else {
			exit('sign error');
        }
    }
	//财付通同意异步处理url
	 public function tenpayshare_notify_url(){
        echo "success";
        eixt();
    }
}
?>