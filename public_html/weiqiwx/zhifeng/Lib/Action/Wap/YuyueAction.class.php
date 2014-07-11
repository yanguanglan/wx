<?php
//wap
class YuyueAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $Yuyue_model;
	public $yuyue_order;
	public function _initialize(){
		
		parent::_initialize();
		$this->token = $this->_get('token');
		$this->assign('token',$this->token);
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->wecha_id){
			$this->wecha_id='';
		}
		$this->assign('wecha_id',$this->wecha_id);
		$this->Yuyue_model=M('yuyue');
		$this->yuyue_order=M('yuyue_order');
		
		$timepart_arr=array('0:00-1:00','1:00-2:00','2:00-3:00','3:00-4:00','4:00-5:00','5:00-6:00','6:00-7:00','7:00-8:00','8:00-9:00','9:00-10:00','10:00-11:00','11:00-12:00','12:00-13:00','13:00-14:00','14:00-15:00','15:00-16:00','16:00-17:00','17:00-18:00','18:00-19:00','19:00-20:00','20:00-21:00','21:00-22:00','22:00-23:00','23:00-24:00');
		$this->assign('timepart_arr',$timepart_arr);

	}

	
	//预约列表
	public function index(){
		$pid = $this->_get('id');
		$where = array('token'=> $this->token,'id'=>$pid);
		$cast = array(
			'token'=> $this->token,
			'wecha_id'=> $this->wecha_id,
			'pid'=>$pid
		);
		//最新预约
		$data = $this->Yuyue_model->where($where)->find();
		$data['count'] = $this->yuyue_order->where($cast)->count();
		$data['token'] = $this->token;
		$data['wecha_id'] = $this->wecha_id;
		
		//粉丝的预约信息
		//$order=$this->yuyue_order->where($cast)->find();

		//表单设置
		$wap= M('setinfo')->where(array('token'=> $this->token,'type'=>'Yuyue','pid'=>$pid))->select();
		$str=array();
		foreach($wap as $v){
			if($v['kind']==5){
				$str["message"]=$v["name"];
			}
			else{
				$str[$v["name"]]=$v["value"];
			}
		}
		//print_r($str);die;
		$arr= M('setinfo')->where(array('token'=>$this->token,'kind'=>'3','type'=>'Yuyue','pid'=>$pid))->select();
		$list= M('setinfo')->where(array('token'=>$this->token,'kind'=>'4','type'=>'Yuyue','pid'=>$pid))->select();
		$list_arr =array();
		$i=0;
		foreach($list as $v){
			$list[$i]['value']= explode("|",$v['value']);
			$i++;
		}
		//print_r($data);die;
		
		$this->assign('str', $str);
		$this->assign('arr',$arr);
		$this->assign('list',$list);
		$this->assign('list_arr',$list);
		$this->assign('yuyue', $data);
		$this->assign('order',$order);
		$this->display();
	}
	
	//添加订单
	public function add(){
		//print_r($_POST());die;
		if(IS_POST){
			$_POST['addtime']=time();
			$url = U('Yuyue/order',array('token'=>$this->$_POST['token'], 'wecha_id'=>$_POST['wecha_id'],$id=>$_POST['pid']));
			if($this->yuyue_order->add($_POST)){
				$json = array(
					'error'=> 1,
					'msg'=> '预约成功！',
					'url'=> $url
				);
				echo  json_encode($json);
			}else{
				$json = array(
					'error'=> 0,
					'msg'=> '预约失败！',
					'url'=> U('Yuyue/index',array('token'=>$this->$_POST['token'], 'wecha_id'=>$_POST['wecha_id'],$id=>$_POST['pid']))
				);
				echo  json_encode($json);
			}
		}
	}
	
	//订单列表
	public function order(){
		$token = $this->token;
		$wecha_id = $this->wecha_id;
		$where = array(
			'wecha_id'=> $wecha_id
		);
		$data = $this->yuyue_order->where($where)->select();
		
		$this->assign('order',$data);
		$this->display();
	}
	
	//修改订单
	public function set(){
		$id = $this->_get('id');
		$data = M('yuyue_order')->find($id);
		$pid = $data['pid'];
		//表单设置
		$wap= M('setinfo')->where(array('token'=> $this->token,'type'=>'Yuyue','pid'=>$pid))->select();
		$str=array();
		foreach($wap as $v){
			if($v['kind']==5){
				$str["message"]=$v["name"];
			}
			else{
				$str[$v["name"]]=$v["value"];
			}
		}
		//print_r($str);die;
		$arr= M('setinfo')->where(array('token'=>$this->token,'kind'=>'3','type'=>'Yuyue','pid'=>$pid))->select();
		$list= M('setinfo')->where(array('token'=>$this->token,'kind'=>'4','type'=>'Yuyue','pid'=>$pid))->select();
		$list_arr =array();
		$i=0;
		foreach($list as $v){
			$list[$i]['value']= explode("|",$v['value']);
			$i++;
		}
		$sigle_arr = $download_arr =array();
		$sigle_str = $data['fieldsigle'];
		$download_str = $data['fielddownload'];
		//自定义单行文本值
		$sigle_str_arr =array_filter(explode("$",$sigle_str));
		foreach($sigle_str_arr as $v){
			$sigle_str_arr2 =array_filter(explode("#",$v));
			$sigle_arr[$sigle_str_arr2[0]]=$sigle_str_arr2[1];
		}
		//print_r($sigle_arr);
		$this->assign('sigle_arr',$sigle_arr);
		//自定义多选值
		$download_str_arr =array_filter(explode("$",$download_str));
		foreach($download_str_arr as $v){
			$download_str_arr2 =array_filter(explode("#",$v));
			$download_arr[$download_str_arr2[0]]=$download_str_arr2[1];
		}
		//print_r($download_arr);
		$this->assign('download_arr',$download_arr);
		
		$this->assign('str', $str);
		$this->assign('arr',$arr);
		$this->assign('list',$list);
		$this->assign('list_arr',$list);
		$this->assign('order',$data);
		$this->display();
	}
	
	//修改订单
	public function runSet(){
		$id = $_POST['id']; 
		if(IS_POST){
			$url = U('Yuyue/order',array('token'=>$this->$_POST['token'], 'wecha_id'=>$_POST['wecha_id'],'id'=>$_POST['pid'],));
			$where = array(
				'id' =>$id
			);
			if($this->yuyue_order->where($where)->save($_POST)){
				$json = array(
					'error'=> 1,
					'msg'=> '修改成功！',
					'url'=> $url
				);
				echo  json_encode($json);
			}else{
				$json = array(
					'error'=> 0,
					'msg'=> '修改失败！',
					'url'=> $url
				);
				echo  json_encode($json);
			}
		}
		
	}
	
	//删除订单
	public function del(){
		if(IS_POST){
			$url = U('Yuyue/order',array('token'=>$this->$_POST['token'], 'wecha_id'=>$_POST['wecha_id'],'id'=>$_POST['pid'],));
			$where = array(
				'id' =>$_POST['id']
			);
			if($this->yuyue_order->where($where)->delete()){
				$json = array(
					'error'=> 1,
					'msg'=> '删除成功！',
					'url'=> $url
				);
				echo  json_encode($json);
			}else{
				$json = array(
					'error'=> 0,
					'msg'=> '删除失败！',
					'url'=> $url
				);
				echo  json_encode($json);
			}
		}
	}
	
}


?>