<?php
//微医疗
class YiliaoAction extends UserAction{

		public $token;
		
		public $Yiliao_model;
		
		public $yiliao_order;

		public function _initialize() {

		parent::_initialize();

		$token_open=M('token_open')->field('queryname')->where(array('token'=>session('token')))->find();

		if(!strpos($token_open['queryname'],'Yiliao')){
            $this->error('您还开启该模块的使用权,请到功能模块中添加',U('Function/index',array('token'=>session('token'),'id'=>session('wxid'))));
		}

		$this->Yiliao_model=M('Yiliao');
		$this->yiliao_order=M('yiliao_order');
		$this->yiliao_setcin=M('yiliao_setcin');
		$this->token=session('token');
		$this->assign('token',$this->token);
		$this->assign('module','Yiliao');
		$this->type="Yiliao";
	}

	//医疗列表

	public function index(){
		$where = array('token'=> $this->token);
		$count      = $this->Yiliao_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$data = $this->Yiliao_model->where($where)->order('id desc')->select();
		$this->assign('page',$show);
		$this->assign('data',$data);
		$this->display();	
	}

	

	public function add(){
		$_POST['token'] = $this->token;

		$_POST['type']=$this->type;

		$lbs=M("Company")->where(array('token'=>$this->token))->select();

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

			if($id = $this->Yiliao_model->add($_POST)){

				$keyword_model=M('Keyword');

				$key = array(

					'keyword'=>$_POST['keyword'],

					'pid'=>$id,

					'token'=>$this->token,

					'module'=> $this->type

				);

				$keyword_model->add($key);

				$this->success('添加成功！',U($this->type.'/index',array('token'=>$this->token)));

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

	//修改
	public function set(){
        $id = intval($this->_get('id')); 
		$checkdata = $this->Yiliao_model->where(array('id'=>$id))->find();
		if(empty($checkdata)||$checkdata['token']!=$this->token){
            $this->error("没有相应记录.您现在可以添加.",U($this->type.'/add'));
        }

		$lbs=M("Company")->where(array('token'=>$this->token))->select();
		$arr=array();
		foreach($lbs as $v){
			$arr[$v['id']]=array('id'=>$v['id'],'address'=>$v['address'],'phone'=>$v['tel'],'latitude'=>$v['latitude'],'longitude'=>$v['longitude']);
		}

		if(IS_POST){ 

            $where=array('id'=>$this->_post('id'),'token'=>$this->token);

			$check=$this->Yiliao_model->where($where)->find();

			if($check==false)$this->error('非法操作');

			if($this->Yiliao_model->create()){

				if($_POST["lbs"]==1){

					$cid=$_POST['cid'];

					$_POST['phone']=$arr[$cid]['phone'];

					$_POST['address']=$arr[$cid]['address'];

					$_POST['longitude']=$arr[$cid]['longitude'];

					$_POST['latitude']=$arr[$cid]['latitude'];

				}

				//print_r($_POST);die;

				if($this->Yiliao_model->where($where)->save($_POST)){

					$this->success('修改成功',U($this->type.'/index',array('token'=>$this->token)));

					$keyword_model=M('Keyword');

					$keyword_model->where(array('token'=>$this->token,'pid'=>$id,'module'=>$this->type))->save(array('keyword'=>$_POST['keyword']));

				}else{

					$this->error('操作失败');

				}

			}else{

				$this->error($this->Yiliao_model->getError());

			}

		}else{

			$this->assign('isUpdate',1);

			$this->assign('set',$checkdata);

			$this->assign('arr',$arr);

			$this->assign('act',$id);

			$this->display();	

		

		}

	}

	//删除

	public function del(){

		if($this->_get('token')!=$this->token){$this->error('非法操作');}

        $id = intval($this->_get('id'));

        if(IS_GET){                              

            $where=array('id'=>$id,'token'=>$this->token);
			$wher=array('pid'=>$id,'token'=>$this->token);
            $check=$this->Yiliao_model->where($where)->find();
            if($check==false)   $this->error('非法操作');
            $back=$this->Yiliao_model->where($where)->delete();
            if($back==true){
				$this->yiliao_order->where($wher)->delete();
				M('setinfo')->where($wher)->delete();
            	M('Keyword')->where(array('token'=>$this->token,'pid'=>$id,'module'=>$this->type))->delete();
                $this->success('操作成功',U($this->type.'/index',array('token'=>$this->token)));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U($this->type.'/index',array('token'=>$this->token)));
            }

        }        

	}

	//订单列表显示

	public function infos(){
		$pid=$this->_get('pid');
		$check=$this->Yiliao_model->where(array('id'=>$pid))->find();
		if(empty($check)){$this->error('请填写好医疗管理页面再进行设置',U($this->type.'/index',array('token'=>$this->token)));}
		$where = array('token'=> $this->token,'pid'=>$pid);
		$data = $this->yiliao_order->where($where)->order('id desc')->select();
		$count = $this->yiliao_order->where($where)->count();	
		$Page = new Page($count,20);
		$show = $Page->show();

		$this->assign('page',$show);
		$this->assign('data', $data);
		$this->assign('pid', $pid);
		$this->display();
	}

	

	//订单详细信息

	public function infos_detail(){

		$where = array('token'=> $this->token,'id'=>$this->_get('id'));

		$data = $this->yiliao_order->where($where)->order('id desc')->select();

		$pid = $data[0]['pid'];

		$info=$data[0]['fieldsigle'].$data[0]['fielddownload'];

		if(!empty($info)){

		$info=substr($info,1);

		$info=explode('$',$info);

		$detail=array();

		foreach($info as $v){

			$detail['info'][]=explode('#',$v);	

		}}

		$detail['all']=$data[0];

		$this->assign('detail', $detail);

		$this->assign('pid', $pid);

		$this->display();

	}

	

	//删除订单

	public function delinfos(){
		if($this->_get('token')!=$this->token){$this->error('非法操作');}
        $id = intval($this->_get('id'));
        if(IS_GET){                              
            $where=array('id'=>$id,'token'=>$this->token);
            $check=$this->yiliao_order->where($where)->find();
            if($check==false)   $this->error('非法操作');
            $back=$this->yiliao_order->where($where)->delete();
            if($back==true){
                $this->success('操作成功',U($this->type.'/infos',array('token'=>$this->token,'pid'=>$check['pid'])));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U($this->type.'/infos',array('token'=>$this->token,'pid'=>$check['pid'])));
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

			if($this->yiliao_order->where($where)->setField($data)){

				$this->success('修改成功！',U($this->type.'/infos',array('pid'=>$pid,'token'=>$this->token)));

			}else{

				$this->error('修改失败！');

			}

        }

	}

	

	//类型设置

	public function setcin(){

		$pid=$this->_get('pid');

		$checkdata=$this->Yiliao_model->where(array('id'=>$pid))->find();

		if(empty($checkdata)){$this->error('请填写好医疗管理页面再进行设置',U($this->type.'/index',array('token'=>$this->token)));}

		$cin=$this->yiliao_setcin;

		$where = array('pid'=>$pid);

		$data=$cin->where($where)->select();

		$count      = $cin->where($where)->count();

		$Page       = new Page($count,20);

		$show       = $Page->show();


		$this->assign('pid',$pid);
		$this->assign('data',$data);
		$this->assign('set',$checkdata);
		$this->assign('page',$show);
		$this->display();

	}

	

	//增加类型

	public function addcin(){

		$pid = $this->_get('pid');

		$cin=$this->yiliao_setcin;

		if(IS_POST){
			$_POST['pid']=$pid;
			$_POST['type']=$this->type;
			if($cin->add($_POST)){
				$this->success('添加成功！',U($this->type.'/setcin',array('token'=>$this->token,'pid'=>$pid)));
			}else{

				$this->error('添加失败！');

			}

		}else{

			$this->assign('pid',$pid);

			$this->display();

		}

		

	}

	

	//修改类型

	public function updatecin(){
		$id = $this->_get('id');
		$pid = $this->_get('pid');
		$cin=$this->yiliao_setcin;
		$data=$cin->where(array('id'=>$id))->find();
		if(IS_POST){
			if($cin->where(array('id'=>$id))->save($_POST)){
				$this->success('修改成功！',U($this->type.'/setcin',array('pid'=>$pid,'token'=>$this->token)));
			}else{
				$this->error('修改失败！');
			}

		}else{
			$this->assign('data',$data);
			$this->assign('pid',$pid);
			$this->display('addcin');
		}

	}

	

	//删除类型

	public function delcin(){

		if($this->_get('token')!=$this->token){$this->error('非法操作');}

		$id = intval($this->_get('id'));

		$pid = intval($this->_get('pid'));

		$cin=$this->yiliao_setcin;



        if(IS_GET){                              

            $where=array('id'=>$id);

            $check=$cin->where($where)->find();

            if($check==false)   $this->error('非法操作');

            $back=$cin->where($where)->delete();

            if($back==true){

                $this->success('操作成功',U($this->type.'/setcin',array('pid'=>$pid,'token'=>$this->token)));

            }else{

                 $this->error('服务器繁忙,请稍后再试');

            }

        }   

			

	}

	

	//订单设置

	 public function setinfo(){ 

		$pid=$this->_get('pid');

		$checkdata=$this->Yiliao_model->where(array('id'=>$pid))->find();

		if(empty($checkdata)){$this->error('请填写好医疗管理页面在再进行设置',U($this->type.'/index',array('token'=>$this->token)));}

		$_POST['token'] = $this->token;

		//print_r($_GET["token"]);die;

		$setinfo=M('setinfo');

		$data=$setinfo->where(array('token'=>$this->token,'type'=>$this->type,'pid'=>$pid))->select();

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

			$setinfo->add(array('token'=>$this->token,'name'=>'留言','kind'=>5,'type'=>$this->type,'pid'=>$pid));

		}

		$this->assign('data',$str);

		$arr=$setinfo->where(array('token'=>$this->token,'kind'=>'3','type'=>$this->type,'pid'=>$pid))->select();

		if(empty($arr[0][name])){

			$arr[0][name]="您要预约的医师";

			$arr[0][value]="请输入您要预约的医师名字";

		}

		//print_r($arr);die;

		$this->assign('arr',$arr);

		$list=$setinfo->where(array('token'=>$this->token,'kind'=>'4','type'=>$this->type,'pid'=>$pid))->select();

		if(empty($list[0][name])){

			$list[0][name]="医疗科目";

			$list[0][value]="门诊|急诊|口腔科|神经科";

		}

		//print_r($list);die;

		$this->assign('list',$list);

		$line=$setinfo->where(array('token'=>$this->token,'kind'=>'5','type'=>$this->type,'pid'=>$pid))->select();

		$this->assign('line',$line);

		$check=0;

		//print_r($_POST["person"]);die;

		if(IS_POST){

			//print_r($_POST);die;

			foreach($arr as $key=> $val){

				$id[]=$val['id'];

			}

			foreach($list as $key=> $val){

				$id[]=$val['id'];

			}

			//print_r($id);die;

			for($i=0;$i<12;$i++){

				 //echo $_POST['name'.$i];

				 

				 if($_POST['name'.$i]!=""){

				 

					//echo "/3333";

					//$count=$setinfo->count('id');

					$add['value'] = 1;

					$add['token'] = $_POST['token'];

					$add['type'] = $this->type;

					$add['id']=$_POST['id'.$i];

					if(!empty($add['id'])&&in_array($add['id'],$id)){

						//echo $add['id']."kk";

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

						// }elseif($i!=11){

							// $add['name']= $_POST['name'.$i];

							// $add['show']=1;

							// $add['token']=this->$token;

							// $setinfo->add($add);

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



			$this->success('修改成功！',U($this->type.'/setinfo',array('token'=>$this->token,'pid'=>$pid)));die;

		}

		$this->assign('pid',$pid);

		$this->display();

	}

}





?>