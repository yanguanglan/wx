<?php
class YuyueAction extends UserAction{

	public $token;

	public $Yuyue_model;

	public $yuyue_order;

	//public $type;

	public function _initialize() {

		parent::_initialize();

		$token_open=M('token_open')->field('queryname')->where(array('token'=>session('token')))->find();

		if(!strpos($token_open['queryname'],'Yuyue')){

            	$this->error('您还开启该模块的使用权,请到功能模块中添加',U('Function/index',array('token'=>session('token'),'id'=>session('wxid'))));

		}

		$this->Yuyue_model=M('yuyue');
		$this->yuyue_order=M('yuyue_order');
		$this->yuyue_setcin=M('yuyue_setcin');
		$this->token=session('token');
		$this->assign('token',$this->token);
		$this->assign('module','Yuyue');
		$this->type=$this->_GET('type')?$this->_GET('type'):"Yuyue";
		$this->assign('type',$this->type);
		//echo $this->type;exit;
	}
	//预约列表

	public function index(){

		$where = array('token'=> $this->token,'type'=>$this->type);
		$count      = $this->Yuyue_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$data = $this->Yuyue_model->where($where)->order('id desc')->select();
		$this->assign('page',$show);
		$this->assign('data',$data);
		$this->display();
	}

	

	//添加预约
	public function add(){ 
		$_POST['token'] = $this->token;
		$_POST['type']=$this->type;
		$lbs=M("Company")->where(array('token'=>$this->token))->order('id asc')->select();
		$arr=array();
		foreach($lbs as $v){
			$arr[$v['id']]=array('id'=>$v['id'],'address'=>$v['address'],'phone'=>$v['tel'],'latitude'=>$v['latitude'],'longitude'=>$v['longitude']);
		}

		if(IS_POST){	
			if($_POST["lbs"]==1){
				$cid=$_POST['cid'];
				$_POST['phone']=$arr[$cid]['phone'];
				$_POST['address']=$arr[$cid]['address'];
				$_POST['longitude']=$arr[$cid]['longitude'];
				$_POST['latitude']=$arr[$cid]['latitude'];
			}
			if($id = $this->Yuyue_model->add($_POST)){

				$keyword_model=M('Keyword');

				$key = array(

					'keyword'=>$_POST['keyword'],

					'pid'=>$id,

					'token'=>$this->token,

					'module'=> $this->type

				);

				$keyword_model->add($key);

				$this->success('添加成功！',U('Yuyue/index',array('token'=>$this->token,'type'=>$this->type)));

			}else{

				$this->error('添加失败！');

			}

		}else{

			$set=array();

			$set['time']=time()+10*24*3600;

			$this->assign('set',$set);

			$this->assign('arr',$arr);

			$this->display('set');

		}

	}

	//修改预约
	public function set(){
        $id = intval($this->_get('id')); 
		$checkdata = $this->Yuyue_model->where(array('id'=>$id))->find();
		if(empty($checkdata)||$checkdata['token']!=$this->token){
            $this->error("没有相应记录.您现在可以添加.",U('Yuyue/index',array('token'=>$this->token,'type'=>$this->type)));
        }
		$lbs=M("Company")->where(array('token'=>$this->token))->order('id asc')->select();
		$arr=array();
		foreach($lbs as $v){
			$arr[$v['id']]=array('id'=>$v['id'],'address'=>$v['address'],'phone'=>$v['tel'],'latitude'=>$v['latitude'],'longitude'=>$v['longitude']);
		}
		if(IS_POST){ 
			if(!$_POST['type']){
				$_POST['type']=$this->type;
			}
            $where=array('id'=>$this->_post('id'),'token'=>$this->token);

			$check=$this->Yuyue_model->where($where)->find();

			if($check==false)$this->error('非法操作');
			if($this->Yuyue_model->create()){

				if($_POST["lbs"]==1){

					$cid=$_POST['cid'];

					$_POST['phone']=$arr[$cid]['phone'];

					$_POST['address']=$arr[$cid]['address'];

					$_POST['longitude']=$arr[$cid]['longitude'];

					$_POST['latitude']=$arr[$cid]['latitude'];

				}
				if($this->Yuyue_model->where($where)->save($_POST)){
					$this->success('修改成功',U('Yuyue/index',array('token'=>$this->token,'type'=>$this->type)));

					$keyword_model=M('Keyword');

					$keyword_model->where(array('token'=>$this->token,'pid'=>$id,'module'=>$this->type))->save(array('keyword'=>$_POST['keyword']));

				}else{
					$this->error('操作失败');

				}

			}else{

				$this->error($this->Yuyue_model->getError());

			}

		}else{

			$this->assign('isUpdate',1);

			$this->assign('set',$checkdata);

			$this->assign('arr',$arr);

			$this->assign('act',$id);

			$this->display();	

		

		}

	}

	//删除预约
	public function del(){

		if($this->_get('token')!=$this->token){$this->error('非法操作');}

        $id = intval($this->_get('id'));

        if(IS_GET){                              

            $where=array('id'=>$id,'token'=>$this->token);

			$wher=array('pid'=>$id,'token'=>$this->token);

            $check=$this->Yuyue_model->where($where)->find();

            if($check==false)   $this->error('非法操作');

            $back=$this->Yuyue_model->where($where)->delete();

            if($back==true){

				M('yuyue_order')->where($wher)->delete();

				M('setinfo')->where($wher)->delete();

            	M('Keyword')->where(array('token'=>$this->token,'pid'=>$id,'module'=>$this->type))->delete();

                $this->success('操作成功',U('Yuyue/index',array('token'=>$this->token,'type'=>$this->type)));

            }else{

                 $this->error('服务器繁忙,请稍后再试',U('Yuyue/index',array('token'=>$this->token,'type'=>$this->type)));

            }

        }        

	}

	//订单列表显示
	public function infos(){
		$where = array('token'=> $this->token,'pid'=>$this->_get('id'));

		$data = $this->yuyue_order->where($where)->order('id desc')->select();

		$count = $this->yuyue_order->where($where)->count();	

		$Page = new Page($count,20);

		$show = $Page->show();
		$this->assign('page',$show);
		$this->assign('data', $data);
		$this->display();
	}

	//订单详细信息
	public function infos_detail(){

		$where = array('token'=> $this->token,'id'=>$this->_get('id'));

		$data = $this->yuyue_order->where($where)->order('id desc')->select();

		$info=$data[0]['fieldsigle'].$data[0]['fielddownload'];

		$info=substr($info,1);

		$info=explode('$',$info);

		$detail=array();

		foreach($info as $v){

			$detail['info'][]=explode('#',$v);	

		}

		$detail['all']=$data[0];



		$this->assign('detail', $detail);

		$this->display();

	}

	//删除订单
	public function delinfos(){

		if($this->_get('token')!=$this->token){$this->error('非法操作');}

        $id = intval($this->_get('id'));

        if(IS_GET){                              

            $where=array('id'=>$id,'token'=>$this->token);

            $check=M('yuyue_order')->where($where)->find();

            if($check==false)   $this->error('非法操作');

            $back=M('yuyue_order')->where($where)->delete();

            if($back==true){

                $this->success('操作成功',U('Yuyue/infos',array('token'=>$this->token,'id'=>$check['pid'],'type'=>$this->type)));

            }else{

                 $this->error('服务器繁忙,请稍后再试','Yuyue/infos',array('token'=>$this->token,'id'=>$check['xid'],'type'=>$this->type));

            }

        }        

	}

	//处理订单
	public function setType(){

		if($this->_get('token')!=$this->token){$this->error('非法操作');}

        $id = intval($this->_get('id'));

		$type = intval($this->_get('type'));

		$pid = intval($this->_get('pid'));

        if(IS_GET){                              

			$where = array(

				'id'=> $id,

				'token'=> $this->token,

			);

			$data = array(

				'type'=> $type

			);

			if($this->yuyue_order->where($where)->setField($data)){

				$this->success('修改成功！',U('Yuyue/infos',array('id'=>$pid,'token'=>$this->token,'type'=>$this->type)));

			}else{

				$this->error('修改失败！');

			}

        }

	}

	public function inputs(){

		$where['xid'] = $this->_get('id');

		$where['token'] = $this->_get('token');

		if(IS_POST){

			$key = $this->_post('searchkey');

			if(empty($key)){

				$this->error("关键词不能为空");

			}



			$where['name'] = array('like',"%$key%");

			$list = M('Canyu')->where($where)->order('time DESC')->select();

			$count      = M('Canyu')->where($where)->count();

			$Page       = new Page($count,20);

			$show       = $Page->show();

			$this->assign('key',$key);

		}else {

			$count      = M('Canyu')->where($where)->count();

			

			$Page       = new Page($count,20);

			$show       = $Page->show();

			$list=M('Canyu')->where($where)->order('time DESC')->select();

		}

		$num = 0;

		foreach($list as $key=>$val){

			$num += $val['number'];

		}

		

		$this->assign('num',$num);

		$this->assign('list',$list);

		$this->assign('page',$show);

		$this->display();

	}

	//类型设置
	public function setcin(){
		$id = $this->_get('id');
		$cin=$this->yuyue_setcin;
		$data=$cin->where(array('type'=>$this->type,'pid'=>$id))->select();
		$this->assign('id',$id);
		$this->assign('data',$data);
		$this->display();
	}

	//增加类型
	public function addcin(){

		$id = $this->_get('id');

		$cin=$this->yuyue_setcin;

		if(IS_POST){

			$_POST['pid']=$id;

			$_POST['type']=$this->type;

			if($cin->add($_POST)){
				$this->success('添加成功！',U('Yuyue/setcin',array('id'=>$id,'token'=>$this->token,'type'=>$this->type)));
			}else{
				$this->error('添加失败！');
			}

		}else{

			$this->assign('id',$id);

			$this->display();

		}
	}

	//修改类型

	public function updatecin(){

		$id = $this->_get('id');

		$pid = $this->_get('aid');

		$cin=$this->yuyue_setcin;

		$data=$cin->where(array('id'=>$id))->find();

		

		if(IS_POST){

			//print_r($_POST);die;

			if($cin->where(array('id'=>$id))->save($_POST)){

				$this->success('修改成功！',U('Yuyue/setcin',array('id'=>$pid,'token'=>$this->token,'type'=>$this->type)));

			}else{

				$this->error('修改失败！');

			}

		}else{

			$this->assign('data',$data);

			$this->assign('id',$pid);

			$this->display('addcin');

		}

	}

	//删除类型
	public function delcin(){

		if($this->_get('token')!=$this->token){$this->error('非法操作');}

		$id = intval($this->_get('id'));

		$pid = intval($this->_get('aid'));

		$cin=$this->yuyue_setcin;



        if(IS_GET){                              

            $where=array('id'=>$id);

            $check=$cin->where($where)->find();

            if($check==false)   $this->error('非法操作');

            $back=$cin->where($where)->delete();

            if($back==true){

                $this->success('操作成功',U('Yuyue/setcin',array('id'=>$pid,'token'=>$this->token,'type'=>$this->type)));

            }else{

                 $this->error('服务器繁忙,请稍后再试');

            }

        }   

			

	}
	
	//订单设置
	public function setinfo(){ 
		$_POST['token'] = $this->token;
		$pid = $this->_get('id');
		$setinfo=M('setinfo');
		$data=$setinfo->where(array('token'=>$this->token,'type'=>$this->type,'pid'=>$pid))->select();
		//$nums=$setinfo->where(array('token'=>$_GET["token"]))->count();
		$str=array();
		if(!empty($data)){
			foreach($data as $v){
				$str[$v["name"]]=$v["value"];
			}
		}else{
			$str=array("person" => 1 ,"phone" => 1 ,"date" => 1 ,"time" => 1,);
			$setinfo->add(array('token'=>$this->token,'name'=>'person','value'=>1,'kind'=>1,'type'=>$this->type,'pid'=>$pid));
			$setinfo->add(array('token'=>$this->token,'name'=>'phone','value'=>1,'kind'=>1,'type'=>$this->type,'pid'=>$pid));
			$setinfo->add(array('token'=>$this->token,'name'=>'date','value'=>1,'kind'=>2,'type'=>$this->type,'pid'=>$pid));
			$setinfo->add(array('token'=>$this->token,'name'=>'time','value'=>1,'kind'=>2,'type'=>$this->type,'pid'=>$pid));
			$setinfo->add(array('token'=>$this->token,'name'=>'备注','kind'=>5,'type'=>$this->type,'pid'=>$pid));
		}
		$this->assign('data',$str);
		$arr=$setinfo->where(array('token'=>$this->token,'kind'=>'3','type'=>$this->type,'pid'=>$pid))->select();
		/*if(empty($arr[0][name])){
			$arr[0][name]="您要预约的医师";
			$arr[0][value]="请输入您要预约的医师名字";
		}*/
		//print_r($arr);die;
		$this->assign('arr',$arr);
		$list=$setinfo->where(array('token'=>$this->token,'kind'=>'4','type'=>$this->type,'pid'=>$pid))->select();
		/*if(empty($list[0][name])){
			$list[0][name]="医疗科目";
			$list[0][value]="门诊|急诊|口腔科|神经科";
		}*/
		//print_r($list);die;
		$this->assign('list',$list);
		$line=$setinfo->where(array('token'=>$this->token,'kind'=>'5','type'=>$this->type,'pid'=>$pid))->select();
		$this->assign('line',$line);
		$check=0;
		if(IS_POST){
			foreach($arr as $key=> $val){
				$id[]=$val['id'];
			}
			foreach($list as $key=> $val){
				$id[]=$val['id'];
			}
			for($i=0;$i<12;$i++){
				//echo $_POST['name'.$i];
				if($_POST['name'.$i]!=""){
					$count=$setinfo->count('id');
					$add['value'] = 1;
					$add['token'] = $_POST['token'];
					$add['type'] = $this->type;
					$add['id']=$_POST['id'.$i];
					if(!empty($add['id'])&&in_array($add['id'],$id)){
						$setinfo->where(array('id'=>$add['id']))->save(array('name'=>$_POST['name'.$i],'value'=>$_POST['content'.$i]));
						$check++;
					}else{
						if($i<6){
							//$add['orderid'] = $count;
							$add['name']= $_POST['name'.$i];
							$add['value'] = $_POST['content'.$i];
							$add['kind']= '3';
							$add['pid']=$pid;
							//echo "die;";die;
							$setinfo->add($add);
							$check++;
						}else{
							$add['name']= $_POST['name'.$i];
							$add['value'] = $_POST['content'.$i];
							$add['kind']= '4';
							$add['pid']= $pid;
							$add['type'] = $this->type;
							$setinfo->add($add);
							$check++;						
						}
					}
				}else{
					$add['id']=$_POST['id'.$i];
					if(in_array($add['id'],$id)){
						$setinfo->where(array('id'=>$add['id']))->delete();
						$check++;
					}
				 }
			}
			//保存备注
			if(!empty($_POST['id'])){
				$setinfo->where(array('id'=>$_POST['id']))->save(array('name'=>$_POST['textname'],'value'=>$_POST['text'],'pid'=>$pid));
				$check++;
			}	
		}
		if($check != 0 ){
			$setinfo->where(array('token'=>$this->token,'name'=>'person','type'=>$this->type,'pid'=>$pid))->save(array('value'=>$_POST['person']));
			$setinfo->where(array('token'=>$this->token,'name'=>'phone','type'=>$this->type,'pid'=>$pid))->save(array('value'=>$_POST['phone']));
			$setinfo->where(array('token'=>$this->token,'name'=>'date','type'=>$this->type,'pid'=>$pid))->save(array('value'=>$_POST['date']));
			$setinfo->where(array('token'=>$this->token,'name'=>'time','type'=>$this->type,'pid'=>$pid))->save(array('value'=>$_POST['time']));
			$this->success('修改成功！',U('Yuyue/index',array('token'=>$this->token,'id'=>$pid,'type'=>$this->type)));die;
		}
		$this->assign('pid',$pid);
		$this->display();
	}
}





?>