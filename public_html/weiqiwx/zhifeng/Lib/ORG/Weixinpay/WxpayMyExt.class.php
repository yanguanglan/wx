<?php
include_once("CommonUtil.class.php");
include_once("WxPayHelper.class.php");

class WxpayMyExt {
	 /**
     * HTTPS形式消息验证地址
     */
	var $https_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&';
	/**
     * HTTP形式消息验证地址
     */
	var $http_deliver_url = 'https://api.weixin.qq.com/pay/delivernotify?access_token=';
	var $order_info;
	var $wxpay_config;
	var $WxPayHelper;
	
	function __construct($wxpay_config,$order_info){
		$this->order_info = $order_info;
		$this->wxpay_config = $wxpay_config;
		$this->WxPayHelper = new WxPayHelper($this->wxpay_config['appId'],$this->wxpay_config['appKey'],$this->wxpay_config['partnerKey']); 
	}
	
	function getAccessToken(){
		//1. 获取access token
		$appid = $this->wxpay_config['appId'];
		$appsecret = $this->wxpay_config['appSecret'];
		$url = $this->https_token_url."appid=$appid&secret=$appsecret";
		$result = $this->https_request($url);
		$jsoninfo = json_decode($result, true);
		$access_token = $jsoninfo["access_token"];	
		
		return $access_token;
	}
	

	//发送发货通知
	function delivernotify_sent(){
		//获得预发送json包
		$jsonmenu=$this->WxPayHelper->create_deliver_package($this->order_info);
		
		$access_token=$this->getAccessToken();
		$url = $this->http_deliver_url.$access_token;
		$result = $this->https_request($url, $jsonmenu);
		
		return $result;
	}
	
	function https_request($url, $data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	
	
}