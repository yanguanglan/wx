<?php
class UserAction extends BaseAction{
	protected function _initialize(){
		parent::_initialize();

		$wxgjuid = $_GET['wxgjuid'];
		$wxgjpwd = $_GET['wxgjpwd'];
		$token   = $_GET['token'];
			if( !empty($token) && $token !=session('token'))
		{
		session('token',$token);
		}
		
		if(session('uid')==false)
		{
			$this->checkUser($wxgjuid,$wxgjpwd);
			$checklogin = $this->checklogin($wxgjuid,$wxgjpwd,$token);
			if(!$checklogin)//reg
			{
				$this->checkreg($wxgjuid,$wxgjpwd);
				$this->addpub($wxgjuid,$_SESSION['uid'],$token);
				$checklogin = $this->checklogin($wxgjuid,$wxgjpwd,$token);
			}

		}
		
		$userinfo=M('User_group')->where(array('id'=>session('gid')))->find();
		$users=M('Users')->where(array('id'=>$_SESSION['uid']))->find();
		$this->assign('thisUser',$users);
		//dump($users);
		$this->assign('viptime',$users['viptime']);
		if(session('uid')){
			if($users['viptime']<time()){
			/*	session(null);
				session_destroy();
				unset($_SESSION);
				$this->error('您的帐号已经到期，请充值后再使用');*/
			}
		}
		$wecha=M('Wxuser')->field('wxname,wxid,headerpic')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('wecha',$wecha);
		$this->assign('token',session('token'));
		//
		$this->assign('userinfo',$userinfo);
		if(session('uid')==false){
			$this->redirect('Home/Index/login');
		}

	}
	
	//登录
	public function checklogin($uname,$pwd,$token)
	{
		$db=D('Users');
		$where['username']=$uname;
		$res=$db->where($where)->find();
		if($res)
		{
			if($pwd===$res['password']){
				//if($res['status']==0){
				//	$this->error('请联系在线客户，为你人工审核帐号');exit;
				//}

				session('uid',$res['id']);
				session('gid',$res['gid']);
				session('uname',$res['username']);
				$info=M('user_group')->find($res['gid']);
				session('diynum',$res['diynum']);
				session('token',$token);
				session('connectnum',$res['connectnum']);
				session('activitynum',$res['activitynum']);
				session('viptime',$res['viptime']);
				session('gname',$info['name']);
				$tt=getdate();
				if($tt['mday']===1){
					$data['id']=$res['id'];
					$data['imgcount']=0;
					$data['textcount']=0;
					$data['musiccount']=0;
					$data['activitynum']=0;
					$db->save($data);
				}
				$db->where(array('id'=>$res['id']))->save(array('lasttime'=>time(),'lastip'=>$_SERVER['REMOTE_ADDR']));//最后登录时间
				return true;
			}else{

			}
		}
		else{
		return false;
		}
	}
	//注册
	public function checkreg($un,$pwd){
		$db=D('Users');
		$info=M('User_group')->find(1);
		$id=$db->add($data = array('money'=>100000,'gid'=>7,'username'=>$un,'password'=>$pwd,'createtime'=>time(),'lasttime'=>time(),'status'=>1,'viptime'=>'1809964800'));
		if($id){
			session('uid',$id);
			session('gid',1);
			session('uname',$_POST['username']);
			session('diynum',0);
			session('connectnum',0);
			session('activitynum',0);
			session('gname',$info['name']);
			return true;
		}
		else
		return false;

	}

	public  function checkUser($un,$pwd)
	{
		$con = $this->myconnect();
		$query = mysql_query("select pwd from user where id='$un'");
		$rs    = mysql_fetch_array($query);
		if($rs)
		{
			if(md5($rs['pwd'])==$pwd){
			return  true;}
			else
			{
			return false;
			}
		}
		else
		{return false;}
	}
	public  function myconnect()
	{
		$con = mysql_connect(C('db_host'),C('db_user'),C('db_pwd'));
		mysql_query("set names 'utf8'");
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
		$db_selecct=mysql_select_db(C('db_name'),$con);
		if(!$db_selecct)
		{
			die("could not to the database</br>".mysql_error());
		}
		return $con;
	}
	public function addpub($un,$uid,$tokenvalue)
	{
		//		$randLength=6;
		//		$chars='abcdefghijklmnopqrstuvwxyz';
		//		$len=strlen($chars);
		//		$randStr='';
		//		for ($i=0;$i<$randLength;$i++){
		//			$randStr.=$chars[rand(0,$len-1)];
		//		}
		//		$tokenvalue=$randStr.time();

		$data = array(
		   'wxname'=>$un,
		   'wxid'=>$un,
		   'weixin'=>$un,
		   'headerpic'=>'./tpl/User/default/common/images/portrait.jpg',
		   'token'=>$tokenvalue,
		   'uid'=>$uid,
		   'tpltypeid'=>'1',
		   'tpllistid'=>'1',
		   'tplcontentid'=>'1',
		   'tpltypename'=>'ty_index',
		   'tpllistname'=>'yl_list',
		   'tplcontentname'=>'ktv_content',
		   'createtime'=>time()
		);
		$db=D('Wxuser');
		$id=$db->add($data);
		if($id){
			$token_open=M('Token_open');
			$open = array('uid'=>$uid,'token'=>$tokenvalue,'queryname'=>'tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,choujiang,taobao,userinfo,fanyi,api,suanming,baike,caipiao,choujiang,gua2,shouye,adma,huiyuanka,shenhe,geci,host_kev,diyform,dx,shop,etuan,diymen_set,Panorama,WeiXitie,Yuyue,weidiaoyan');
			$token_open->data($open)->add();
		}
	}
}