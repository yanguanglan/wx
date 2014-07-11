<?php
class ZucheAction extends UserAction{
	public $token;
	public $zuche_model;
	public $zuche_cat_model;
	//public $isDining;
	//public $isBranch;
	public $company_model;
	public function _initialize() {
		parent::_initialize();
		$token_open=M('token_open')->field('queryname')->where(array('token'=>session('token')))->find();
		if(!strpos($token_open['queryname'],'Zuche')){
            $this->error('您还开启该模块的使用权,请到功能模块中添加',U('Function/index',array('token'=>session('token'),'id'=>session('wxid'))));
		}
		$this->token=session('token');
		$this->assign('token',$this->token);
		//$this->zuche_stores=M('Zuche_company');
		$this->zuche_model=M('Zuche');
		//查询店铺列表
		$stores =M('Company')->where(array('token'=>$this->token))->order('taxis asc')->select();
		if(!$stores){
			$this->error('请设置公司LBS信息',U('Company/index',array('token'=>$this->token)));
		}
		$this->stores=$stores;
		$this->assign('Stores',$stores);
		$storeid=$this->_get('storeid','intval');
		$this->storeid=$storeid;
		$this->assign('storeid',$storeid);
	}
	public function index(){
		$catid=intval($_GET['catid']);
		$zuche_model=M('Zuche');
		$dining_cat_model=M('Zuche_cat');
		$where=array('token'=>$this->token);
		if($this->storeid){
			$where['storeid']=$this->storeid;
		}
		if ($catid){
			$where['catid']=$catid;
		}
		$where['groupon']=0;
        if(IS_POST){
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->_get('token'); 
            $map['name|intro|keyword'] = array('like',"%$key%"); 
            $list = $zuche_model->where($map)->select(); 
            $count      = $zuche_model->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        }else{
        	$count      = $zuche_model->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $zuche_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }
		foreach($list as $k=>$v){
			$list[$k]['catname']=M('Zuche_cat')->where(array('id'=>$v['catid']))->getField('name');
		}
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('isDiningPage',1);
		
		$this->display();		
	}
	public function cats(){
		$parentid=intval($_GET['parentid']);
		$parentid=$parentid==''?0:$parentid;
		$data=M('Zuche_cat');
		$where=array('parentid'=>$parentid,'token'=>$this->token);
		if($this->storeid){
			$where['storeid']=$this->storeid;
		}
        if(IS_POST){
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->_get('token'); 
            $map['name|des'] = array('like',"%$key%"); 
            $list = $data->where($map)->select(); 
            $count      = $data->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        }else{
        	$count      = $data->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		if ($parentid){
			$parentCat = $data->where(array('id'=>$parentid))->find();
		}
		$this->assign('parentCat',$parentCat);
		$this->assign('parentid',$parentid);
		$this->display();		
	}
	public function catAdd(){ 
		if(IS_POST){
			//子分类继承上级分类storeid
			if($parentid !=0){
				$_POST['storeid']=M('Zuche_cat')->where(array("id"=>$parentid))->getField('storeid');
			}
			$this->insert('Zuche_cat','/cats?parentid='.$this->_post('parentid'));
		}else{
			$parentid=intval($_GET['parentid']);
			$this->assign('parentid',$parentid);
			$this->display('catSet');
		}
	}
	public function catDel(){
		if($this->_get('token')!=$this->token){$this->error('非法操作');}
        $id = $this->_get('id');
        if(IS_GET){                              
            $where=array('id'=>$id,'token'=>$this->token);
            $data=M('Zuche_cat');
            $check=$data->where($where)->find();
            if($check==false)   $this->error('非法操作');
            $zuche_model=M('Zuche');
            $diningsOfCat=$zuche_model->where(array('catid'=>$id))->select;
            if (count($diningsOfCat)){
            	$this->error('本分类下有商品，请删除商品后再删除分类',U('Zuche/cats',array('token'=>$this->token,'storeid'=>$this->storeid)));
            }
            $back=$data->where($wehre)->delete();
            if($back==true){
                $this->success('操作成功',U('Zuche/cats',array('token'=>$this->token,'parentid'=>$check['parentid'],'storeid'=>$this->storeid)));
            }else{
                 $this->error('操作失败');
            }
        }        
	}
	public function catSet(){
        $id = $this->_get('id'); 
		$checkdata = M('Zuche_cat')->where(array('id'=>$id))->find();
		if(empty($checkdata)){
            $this->error("没有相应记录.您现在可以添加.",U('Zuche/catAdd',array('token'=>$this->token,'storeid'=>$this->storeid)));
        }
		if(IS_POST){ 
            $data =D('Zuche_cat');
			$parentid =$this->_post('parentid');
            //不能把自己放到自己或者自己的子目录们下面
			$pid_str = $this->get_catpids($parentid);
			$pid_arr =explode("|",$pid_str);
            if (in_array($id, $pid_arr)) {
                 $this->error("不能选择此分类");
            }
			//子分类继承上级分类storeid
			if($parentid !=0){
				$_POST['storeid']=M('Zuche_cat')->where(array("id"=>$parentid))->getField('storeid');
			}
			if($data->create()){
				if($data->where($where)->save($_POST)){
					$this->success('修改成功',U('Zuche/cats',array('token'=>$this->token,'parentid'=>$this->_post('parentid'),'storeid'=>$this->storeid)));	
				}else{
					$this->error('操作失败');
				}
			}else{
				$this->error($data->getError());
			}
		}else{
			//获得分类节点
            $spid = $this->get_catpids($checkdata['parentid']);
            $this->assign('selected_ids',$spid); //分类选中
			
			$this->assign('parentid',$checkdata['parentid']);
			$this->assign('set',$checkdata);
			$this->display();	
		
		}
	}
	public function add(){ 
		if(IS_POST){
			$catid=$pid = $this->_post('catid', 'intval');
			if(!$catid)  $this->error("请选择分类");
			$_POST['storeid']=M('Zuche_cat')->where(array("id"=>$catid))->getField('storeid');
			$this->insert('Zuche','/index?token='.$this->token.'&storeid='.$this->storeid);
		}else{
			$this->display('set');
		}
	}
	/**
	 * 商品类别ajax select
	 *
	 */
	public function ajaxCatOptions(){
		$pid = $this->_get('pid', 'intval');
        $catWhere=array('parentid'=>$pid,'token'=>$this->token);
		if($this->storeid){
			$catWhere['storeid']=$this->storeid;
		}
        $return = M('Zuche_cat')->field('id,name')->where($catWhere)->select();
        if ($return) {
            $this->ajaxReturn(1,'操作成功', $return);
        } else {
            $this->ajaxReturn(0, '操作失败');
        }
	}
	public function set(){
        $id = $this->_get('id'); 
		$checkdata = $this->zuche_model->where(array('id'=>$id))->find();
		if(empty($checkdata)){
            $this->error("没有相应记录.您现在可以添加.",U('Zuche/add'));
        }
		if(IS_POST){ 
            $where=array('id'=>$this->_post('id'),'token'=>$this->token);
			$check=$this->zuche_model->where($where)->find();
			if($check==false)$this->error('非法操作');
			
			$catid=$pid = $this->_post('catid', 'intval');
			if(!$catid)  $this->error("请选择分类");
			$_POST['storeid']=M('Zuche_cat')->where(array("id"=>$catid))->getField('storeid');
			if($this->zuche_model->create()){
				if($this->zuche_model->where($where)->save($_POST)){
					$this->success('修改成功',U('Zuche/index',array('token'=>$this->token)));
					$keyword_model=M('Keyword');
					$keyword_model->where(array('token'=>$this->token,'pid'=>$this->_post('id'),'module'=>'Zuche'))->save(array('keyword'=>$this->_post('keyword')));
				}else{
					$this->error('操作失败');
				}
			}else{
				$this->error($this->zuche_model->getError());
			}
		}else{
			//获得分类节点
            $spid = $this->get_catpids($checkdata['catid']);
            $this->assign('selected_ids',$spid); //分类选中

			$this->assign('isUpdate',1);
			$this->assign('set',$checkdata);
			$this->assign('isDiningPage',1);
			$this->display();	
		
		}
	}
	public function get_catpids($catid){
		$dining_cat_model=M('Zuche_cat');
		$parentid = $dining_cat_model->where(array('id'=>$catid))->getField('parentid');
		if( $parentid==0 ){
			$spid = $catid;
		}else{
			$spid = $this->get_catpids($parentid)."|".$catid;
		}
		return $spid;
	}
	//商品类别下拉列表
	public function catOptions($cats,$selectedid){
		$str='';
		if ($cats){
			foreach ($cats as $c){
				$selected='';
				if ($c['id']==$selectedid){
					$selected=' selected';
				}
				$str.='<option value="'.$c['id'].'"'.$selected.'>'.$c['name'].'</option>';
			}
		}
		return $str;
	}
	public function del(){
		$zuche_model=M('Zuche');
		if($this->_get('token')!=$this->token){$this->error('非法操作');}
        $id = $this->_get('id');
        if(IS_GET){                              
            $where=array('id'=>$id,'token'=>$this->token);
            $check=$zuche_model->where($where)->find();
            if($check==false)   $this->error('非法操作');

            $back=$zuche_model->where($wehre)->delete();
            if($back==true){
            	$keyword_model=M('Keyword');
            	$keyword_model->where(array('token'=>$this->token,'pid'=>$id,'module'=>'Zuche'))->delete();
                $this->success('操作成功',U('Zuche/index',array('token'=>$this->token)));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Zuche/index',array('token'=>$this->token)));
            }
        }        
	}
	//店铺列表
	public function stores(){
		$stores=$this->stores;
		$this->assign('stores',$stores);
		$this->display();
	}
}


?>