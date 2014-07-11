<?php
class ZucheAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $zuche_model;
	public $zuche_cat_model;

	public function _initialize(){
		parent::_initialize();
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->wecha_id){
			$this->wecha_id='null';
		}
		$this->assign('token',$this->token);
		$this->assign('wecha_id',$this->wecha_id);
		
		$this->zuche_model=M('Zuche');
		$this->zuche_cat_model=M('Zuche_cat');
	
		//当前店铺
		$storeid=$this->_get('storeid','intval');
		$stores =M('Company')->where(array('token'=>$this->token))->select();
		$this->assign('stores',$stores);
		if(!$storeid){
			//$storeid=$stores[0]['id'];
			$store_logourl=M('company')->where(array('token'=>$this->token,'id'=>$stores[0]['id']))->find();
		}
		//dump($store_logourl);exit;
		$store_info=M('company')->where(array('token'=>$this->token,'id'=>$storeid))->find();
		$this->storeid=$storeid;
		$this->assign('storeid',$storeid);
		$this->assign('store_info',$store_info);
		$this->assign('store_logourl',$store_logourl);
		//是否开启第三方预定
		$yzucheconfig=M('Reply_info')->field('config')->where(array('token'=>$this->token,'infotype'=>'Zuche'))->find();
		$zucheconfig=unserialize($yzucheconfig['config']);
		if($zucheconfig['iszuche']==1){
			$this->assign('zucheurl',$zucheconfig['zucheurl']);
		}
		$this->zucheconfig=$zucheconfig;
	}
	
	/*public function index(){
		//查询总店信息
		$Company=M('Company')->where(array('token'=>$this->token,'isbranch'=>0))->find();
		//查询首页配置
		$Reply_info=M('Reply_info')->where(array('token'=>$this->token,'infotype'=>'Zuche'))->find();
		
		$this->assign('logourl',$Company['logourl']);
		$this->assign('zucheset',$Reply_info);
		$this->assign('metaTitle','租车首页');
		$this->display();
	}*/
	public function stores(){
		$company_model=M('company');		
		$stores=$company_model->where(array('token'=>$this->token,'status'=>1))->order('taxis asc')->select();	
		$this->assign('stores',$stores);
		$this->assign('StoreCount',count($stores));
		$this->assign('metaTitle','店铺列表');
		$this->display();
	}
	public function cartlist(){
		$where['token']=$this->_get('token','trim');
		$catid=intval($_GET['catid']);
		
	
		$subids=$this->subcat_ids($catid);
		if($subids){
		$where['catid']=array('in',$subids);
		}
		
		$catWhere['storeid']=array($this->storeid,'NULL','or');
		//排序方式
		$method=isset($_GET['method'])&&($_GET['method']=='DESC'||$_GET['method']=='ASC')?$_GET['method']:'ASC';
		switch ($method){
			case 'DESC':
				$order='price DESC';
				break;
			case 'ASC':
				$order='price ASC';
				break;  				
			default:
			$order='id ASC';
		}
		$this->assign('method',$method);

		//分类查询
		$catWhere['token'] =$this->token;
		$zuche=M('Zuche')->where($where)->order($order)->select();
		if($this->zucheconfig['iszuche']==1){
			foreach($zuche as $key=>$val){
				$zuche[$key]['url']=str_replace('{$name}',$val['name'],$this->zucheconfig['zucheurl']);
			}
		}
		$zuche_cat=M('Zuche_cat')->where($catWhere)->select();
		$this->assign('catid',$catid);
		$this->assign('zuche',$zuche);
		$this->assign('cats',$zuche_cat);
		$this->display();
	}
	public function xuzhi(){
		$where['token']=$this->_get('token','trim');
		$company_model=M('company');		
		$stores=$company_model->where(array('token'=>$this->token,'status'=>1))->select();	
		//$stores=$company_model->where(array('status'=>1))->select();	
		$this->assign('stores',$stores);
		$this->assign('StoreCount',count($stores));
		$this->assign('metaTitle','租车须知');
	
		$where['title']=array('like','%租车须知%');
		$where['token']=$this->token;
		$where['status']=1;
		$content=M('Img')->where($where)->select();
		$yuyuezuche=M('yuyue')->where(array('keyword'=>array('like','%预约租车%'),'token'=>$this->token))->find();
		$this->assign('content',$content);
		//dump($yuyuezuche);
		$this->assign('yuyuezuche',$yuyuezuche);
		$this->display();		
	}
	public function xiangqing(){
		$this->display();		
	}
	//获得子类id
	public function subcat_ids($pid=0){
		$where=array('token'=>$this->token,'parentid'=>$pid);
		if ($pid==0){
			$where['storeid']=array(array('eq',$this->storeid),array('eq',0),'or');
		}
		$cats = M('zuche_cat')->where($where)->order('id asc')->select();
		$ids="";
		foreach ($cats as $v){
			$ids .=','.$this->subcat_ids($v['id']);
		}
		if($pid)
		return $pid.$ids;
		else
		return substr($ids,1);
	}
	
	/*public function cats(){
		$catid=intval($_GET['catid']);
		$this->assign('Catid',$catid);
		if($catid){
			$cat_data=$this->zuche_cat_model->where(array('id'=>$catid,'token'=>$this->token))->find();
			
			$this->assign('thisCat',$cat_data);
		}
		//多级分类支持
		$subcats_num =$this->zuche_cat_model->where(array('parentid'=>$catid,'token'=>$this->token))->count();
		$parentid = $subcats_num?$catid:$cat_data['parentid'];
		$catWhere = array('parentid'=>$parentid,'token'=>$this->token);
		if ($parentid==0){
			$catWhere['storeid']=array(array('eq',$this->storeid),array('eq',0),'or');
		}
		$cats = $this->zuche_cat_model->where($catWhere)->order('id asc')->select();
		$this->assign('cats',$cats);
		//产品列表
		$where=array('token'=>$this->token);
		//取下级所有分类ID
		$subids=$this->subcat_ids($catid);
		//echo $subids;
		$where['catid']=array('in',$subids);
		$count = $this->zuche_model->where($where)->count();
		$this->assign('count',$count); 
		//排序方式
		$method=isset($_GET['method'])&&($_GET['method']=='DESC'||$_GET['method']=='ASC')?$_GET['method']:'DESC';
		$orders=array('time','discount','price','salecount');
		$order=isset($_GET['order'])&&in_array($_GET['order'],$orders)?$_GET['order']:'time';
		$this->assign('order',$order);
		$this->assign('method',$method);
		//
		$products = $this->zuche_model->where($where)->order($order.' '.$method)->select();
		//格式化简介
		foreach($products as $k=>$v){
			$products[$k]['intro']=$this->remove_html_tag($v['intro']);
		}
		$this->assign('products',$products);
		$this->display();
	}
	public function subcat_ids($pid=0){
		$where=array('token'=>$this->token,'parentid'=>$pid);
		if ($pid==0){
			$where['storeid']=array(array('eq',$this->storeid),array('eq',0),'or');
		}
		$cats = $this->zuche_cat_model->where($where)->order('id asc')->select();
		$ids="";
		foreach ($cats as $v){
			$ids .=','.$this->subcat_ids($v['id']);
		}
		if($pid)
		return $pid.$ids;
		else
		return substr($ids,1);
	}


	*/
	
	function remove_html_tag($str){  //清除HTML代码、空格、回车换行符
		//trim 去掉字串两端的空格
		//strip_tags 删除HTML元素

		$str = trim($str);
		$str = @preg_replace('/<script[^>]*?>(.*?)<\/script>/si', '', $str);
		$str = @preg_replace('/<style[^>]*?>(.*?)<\/style>/si', '', $str);
		$str = @strip_tags($str,"");
		$str = @ereg_replace("\t","",$str);
		$str = @ereg_replace("\r\n","",$str);
		$str = @ereg_replace("\r","",$str);
		$str = @ereg_replace("\n","",$str);
		$str = @ereg_replace(" ","",$str);
		$str = @ereg_replace("&nbsp;","",$str);
		return trim($str);
	}
}
?>