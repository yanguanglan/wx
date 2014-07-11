<?php
class MemberAction extends UserAction{
	public function index(){
		$sql=M('Member');
		$data['token']=$this->_get('token');
		$data['uid']=session('uid');
		$member=$sql->field('homepic')->where($data)->find();
		$this->assign('member',$member);
		$list=M('Userinfo')->where(array('token'=>$data['token']))->select();
		
		if(IS_POST){
			 
			$key = $this->_post('searchkey');
			if(empty($key)){
				exit("关键词不能为空.");
			}
			$map['token'] = $this->get('token'); 
			$map['tel|wechaname'] = array('like',"%$key%"); 
			$list = M('Userinfo')->where($map)->select();
			 
			 
		}
		$this->assign('list',$list);
		$tbsign = M('Member_card_sign')->where(array('token'=>$data['token']))->select();
		$this->assign('tbsign',$tbsign);
		//var_dump($tbsign);
		$this->display();
	}
	public function add(){
		$sql=M('Member');
		$data['token']=$this->_get('token');
		$data['uid']=session('uid');
		$member=$sql->field('id')->where($data)->find();
		$pic['homepic']=$this->_post('homepic');
		if($member!=false){
			$back=$sql->where($data)->save($pic);
			if($back){
				$this->success('更新成功');
			}else{
				$this->error('服务器繁忙，请稍后再试1');
			}
		}else{
			$data['homepic']=$pic['homepic'];
			$back=$sql->add($data);
			if($back){
				$this->success('更新成功');
			}else{
				$this->error('服务器繁忙，请稍后再试');
			}
		}
	
	}
	public function del(){
		$data['token']=$this->_get('token');
		$data['id']=$this->_get('id');
		$back=M('Userinfo')->where($data)->delete();
		if($back){
			$this->success('操作成功');
		}else{
			$this->error('服务器繁忙，请稍候再试');
		} 
	}

	//------------------------------------------
	// 添加消费积分记录
	//------------------------------------------

	public function edit(){

		if(!IS_POST){
			$this->error('没有提交任何东西');exit;	
		}

		$token = $this->_post('token');
		$wecha_id = $this->_post('wecha_id');
		$add_expend = (int)$this->_post('add_expend');
		$add_expend_time = $this->_post('add_expend_time');
		
		if($add_expend <= 0){
			$this->error('消费金额必须大于0元');exit;	
		}
		//获取商家设置 tp_member_card_exchange
		$exchange = M('Member_card_exchange');
		$getset = $exchange->where(array('token'=>$token))->find();
		//var_dump($getset['continuation']); 
		// 积分 = 消费总金额 * $getset['continuation']
		$userinfo = M('Userinfo')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->find();


		 $data['token']    = $token;
		 $data['wecha_id'] = $wecha_id;
		 $data['sign_time'] = strtotime($add_expend_time);
		 $data['score_type'] = 2;
		 $data['expense']  = ceil($add_expend * $getset['continuation']);
		 $data['sell_expense'] = $add_expend; //消费金额
		 //var_dump($data);exit;
		 $back = M('Member_card_sign')->data($data)->add();

		//总记录
		$da['total_score']   = $userinfo['total_score'] +  $data['expense'];
        	$da['expend_score']  = $userinfo['expend_score'] + $data['expense'];
        	$da['add_expend']    = $add_expend;
        	$da['add_expend_time']=strtotime($add_expend_time);
        	$back2 = M('Userinfo')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->save($da);
        	if($back && $back2){
			$this->success('操作成功');
		}else{
			$this->error('服务器繁忙，请稍候再试');
		} 
	}
	/*
	**会员列表
	**author：zfwzh
	*/
	 public function members()
    {
        $card_create_db    = M('Member_card_create');
        $where             = array();
        $where['token']    = $this->token;
        $where['wecha_id'] = array(
            'neq',
            ''
        );
        if (IS_POST) {
            if (isset($_POST['searchkey']) && trim($_POST['searchkey'])) {
                $where['number'] = array(
                    'like',
                    '%' . trim($_POST['searchkey']) . '%'
                );
            }
        }
        $count     = $card_create_db->where($where)->count();
        $Page      = new Page($count, 15);
        $show      = $Page->show();
        $list      = $card_create_db->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $members   = $card_create_db->where($where)->select();
        $wecha_ids = array();
        if ($members) {
            foreach ($members as $member) {
                array_push($wecha_ids, $member['wecha_id']);
            }
            $userinfo_db                = M('Userinfo');
            $userinfo_where['wecha_id'] = array(
                'in',
                $wecha_ids
            );
            $users                      = $userinfo_db->where($userinfo_where)->select();
            $usersArr                   = array();
            if ($users) {
                foreach ($users as $u) {
                    $usersArr[$u['wecha_id']] = $u;
                }
            }
            $i = 0;
            foreach ($members as $member) {
                $thisUser                    = $usersArr[$member['wecha_id']];
                $members[$i]['truename']     = $thisUser['truename'];
                $members[$i]['wechaname']    = $thisUser['wechaname'];
                $members[$i]['qq']           = $thisUser['qq'];
                $members[$i]['tel']          = $thisUser['tel'];
                $members[$i]['account']  = $thisUser['account'];
                $members[$i]['getcardtime']  = $thisUser['getcardtime'];
                $members[$i]['expensetotal'] = $thisUser['expensetotal'];
                $members[$i]['total_score']  = $thisUser['total_score'];
                $i++;
            }
            $this->assign('members', $members);
            $this->assign('page', $show);
        }
        $this->display();
    }
	/*
	**会员充值
	**author：zfwzh
	*/
	public function recharge(){
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
      	if(!$token||!$wecha_id){
      		$this->error('无法验证充值身份！！');
      	}
        if (IS_POST) {
        	$fangshi=$_POST['fangshi'];
        	if($fangshi==1){
        		$price=floatval($_POST['price']);
        		$retype=1;
        	}else{
        		$price=floatval('-'.$_POST['price']);
        		$retype=2;
        	}
        	
        	if(M('Userinfo')->where(array('token' =>$token,'wecha_id' =>$wecha_id))->setInc('account',$price)){
	        	$data['price']=$price;
	        	$data['reuser']=$_POST['reuser'];
	        	$data['retype']=$retype;
	        	$data['info']=$_POST['info'];
	        	$data['token']=$token;
	        	$data['wecha_id']=$wecha_id;
	        	$data['time']=time();
	        	M('member_accountlog')->add($data);
	            $this->success('操作成功');
            }else{
            	$this->error('充值失败');
            }
        }else{
			$this->display(); 
        }
            
    }
    /*
    **会员账户记录
    **author：zfwzh
    ** 
    */
    public function accountlog(){
    	$where['token']=$this->_get('token');
    	$where['wecha_id']=$this->_get('wecha_id');
    	$members=$this->getuserinfo($where['wecha_id'],$where['token']);
    	$count=M('member_accountlog')->where($where)->count();
   		$Page      = new Page($count, 15);
        $show      = $Page->show();
        $list      = M('member_accountlog')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();	 	
        $logs=M('member_accountlog')->where($where)->select();
        $this->assign('members',$members);
        $this->assign('logs', $logs);
        $this->assign('page', $show);
    	$this->display(); 
    }
   	/*
    **delect会员账户记录
    **author：zfwzh
    ** 
    */
    public function accountlog_del(){
    	$where['token']=$this->_get('token');
    	$where['wecha_id']=$this->_get('wecha_id');
    	$where['id']=$this->_get('id');	 	
        $logs=M('member_accountlog')->where($where)->delete();
	    if($logs){
	    	 $this->success('删除成功');
	    }else{
	    	 $this->error('删除失败');
	    } 
    }

    /*获得会员信息*/
    public function getuserinfo($wecha_id,$token)
    {
        $uinfo    = M('Userinfo')->where(array(
            'wecha_id' => $wecha_id,
            'token' => $token
        ))->order('id DESC')->find();
       return $uinfo;
       
    }
}
?>