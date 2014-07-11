<?php
header("content-type:text/html;charset=utf-8");
ini_set("magic_quotes_runtime",0);
require 'class.phpmailer.php';
require 'sms.php';
$sms = new Model('weiqiwx_sms_set');
$sms->find(array('token'=>Session :: get('wid')));
$semail = new Model('weiqiwx_email_set');
$semail->find(array('token'=>Session :: get('wid')));
$yuyue = new Model('newyy');
$yuyue->find(array('id'=>Request :: get(1)));

if(Request :: get('wxid')){
    Session :: set('wxid', Request :: get('wxid'));
}
if(Request :: get('wid')){
    Session :: set('wid', Request :: get('wid'));
}
if(Session :: has('wxid') && Session :: has('wid')){
    $m = new Model('newyy_record');
    if($m -> has_id()){
    }
    if($m -> try_post()){
        $m -> hid = Request :: get(1);
        $m -> wid = Session :: get('wid');
        $m -> wxid = Session :: get('wxid');
        $m -> ctime = DB :: raw('now()');
        $m -> save();
		if($yuyue->yuyuestatus == 0){
			Response :: write('提交成功');
			}else{
		$mail = new PHPMailer(true); 
		$mail->IsSMTP();
		$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
		$mail->SMTPAuth   = true;                  //开启认证
		$mail->Port       = $semail->port;                    
		$mail->Host       = $semail->server; 
		$mail->Username   = $semail->account;    
		$mail->Password   = $semail->password;            
		$mail->From       = $semail->account;
		$mail->FromName   = "微系统管理员";
		$mail->AddAddress($yuyue->email);
		$mail->Subject  = "预约订单提醒";
		$mail->Body = $yuyue->emailtext;
		$mail->WordWrap   = 80; // 设置每行字符串的长度
		$mail->IsHTML(true); 
        if($mail->Send()){
			$snoopy = new snoopy();
            $smsuser = $sms->account;
            $smspass = md5($sms->password);
            $shangjia = "http://api.smsbao.com/sms?u={$smsuser}&p={$smspass}&m=".$yuyue->phone."&c=" . urlencode($yuyue->yuyuetext);
			$kehu = "http://api.smsbao.com/sms?u={$smsuser}&p={$smspass}&m=".$m->form1."&c=" . urlencode($yuyue->yonghutext);
            $snoopy -> fetch($shangjia);
			if($yuyue->yonghustatus == 1 ){
			$snoopy -> fetch($kehu);
			}
            $result = $snoopy -> results;
			Response :: write('提交成功');
			}
			}
    }
}else{
    die();
}
?>