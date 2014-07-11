<?php

if(Request::get('wxid')){

	Session::set('wxid',Request::get('wxid'));

}

if(Request::get('wid')){

	Session::set('wid',Request::get('1'));

}

$red = new Model('micro_diancai_type_full_view');

$b = new Model('micro_diancai_haibao');

$m = $b->find(array('wxid'=>$wxid,'wid'=>Request::get('1')));



if(!empty($_POST))

{

foreach($_POST as $a => $b)

{

$red->$a =$b;

}

$red->time=time();

$red->state = "0";

$red->wid = Request::get('1');

$red->wxid = Request::get('wxid');

$red->save();

$numsms=0;
$smsphone="";
$message="";
$tresult=mysql_query("select * from user where id=".Request::get('1'));
if($trow=mysql_fetch_array($tresult, MYSQL_BOTH)){
	$numsms=$trow['surplus_sms'];
}
$tresult=mysql_query("select * from micro_diancai_set where wid=".Request::get('1'));
if($trow=mysql_fetch_array($tresult, MYSQL_BOTH)){
	if($trow['noticetelon']){
		$smsphone=$trow['noticetel'];
		$message=$trow['noticecontent'];
	}
}
if($smsphone!="" and $message!="" and $numsms>0){
require_once  YYUC_FRAME_PATH.'snoopy.php';
$snoopy = new snoopy();
$smsuser=conf::$smsusername;
$smspass=md5(conf::$smspassword);
$smsapi=conf::$smsapi;
$sendurl = "http://{$smsapi}/sms?u={$smsuser}&p={$smspass}&m={$smsphone}&c=".urlencode($message);
$snoopy->fetch($sendurl);
$result = $snoopy->results;
$numsms=$numsms-1;
mysql_query("update user set surplus_sms='$numsms' where id=".Request::get('1'));
}


$a=1;

}

