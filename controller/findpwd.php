<?php
header("content-type:text/html;charset=utf-8");
ini_set("magic_quotes_runtime",0);
require 'class.phpmailer.php';
$u = new SampleModel();
$email = new Model('weiqi_email_config');
function apkeyrand($length){
    $str = '0123mDEFGHIuxyzArv45cdeJKLMnopqfghbNOPQTijbNOPQTklwstRSBC6789aUVWXYZ';
    
    $strlen = 62;
    while($length > $strlen){
        $str .= $str;
        $strlen += 60;
    }

    $str = str_shuffle($str);
    return substr($str, 0,$length);
}

if($u->try_post()){
	$pub = new Model('user');
	$pwdrand = apkeyrand(8);
	$pub->find(array('un'=>$u->un,'email'=>$u->pwd));
	if($pub->has_id()){
		$isstop = $pub->isstop;
		if($isstop == 0){
		$ru = new Model('user');
		$ru->update(array('id'=>$pub->id),array('pwd'=>$pwdrand));
		$mail = new PHPMailer(true); 
		$mail->IsSMTP();
		$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
		$mail->SMTPAuth   = true;                  //开启认证
		$mail->Port       = $email->port;                    
		$mail->Host       = $email->server; 
		$mail->Username   = $email->emailname;    
		$mail->Password   = $email->emailpwd;            
		$mail->AddReplyTo($email->emailname,$email->webname."管理员");
		$mail->From       = $email->emailname;
		$mail->FromName   = $email->webname."管理员";
		$mail->AddAddress($u->pwd);
		$mail->Subject  = "找回您的登录密码（自：".$email->webname."）";
		$mail->Body = "<p>亲爱的".$u->un."：</br> 你提交了找回密码的申请，系统为您生成了随机密码为<span style='color:red'>".$pwdrand."</span>，</br>您现在的用户名为:<span style='color:red'>".$u->un."</span>,密码为：<span style='color:red'>".$pwdrand."</span>，你可以登录后台后修改。</p>".$email->findpwd."</br>这是一封系统自动发出的邮件，请不要直接回复。</br>
	如有疑问可与客服联系。</br>"
	.$email->webname."<span style='color:red'>http://".$email->weburl."</span>";
		$mail->WordWrap   = 80; // 设置每行字符串的长度
		$mail->IsHTML(true); 
        if($mail->Send()){
			Response::exejs('alert("密码已重置请登录邮箱查看");goto("/login.html")');
		}else{
			tusi('您好，邮件发送失败，请联系管理员。');
		}
		}
		if($isstop == 1){
		tusi('您的账号未启用，请联系管理员启用');
		}
	}else{
		tusi('用户名或者邮箱不正确');
	}
}