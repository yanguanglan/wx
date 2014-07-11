<?php
class CompanyAction extends UserAction{
	public $token;
	public $isBranch;
	public $company_model;
	public function _initialize() {
		parent::_initialize();
		$this->token=session('token');
		$this->assign('token',$this->token);
		//权限
		if ($this->token!=$_GET['token']){
			exit();
		}
		//是否是分店
		$this->isBranch=0;
		if (isset($_GET['isBranch'])&&intval($_GET['isBranch'])){
			$this->isBranch=1;
		}
		$this->assign('isBranch',$this->isBranch);
		//
		$this->company_model=M('Company');
	}
	public function index(){
		$where=array('token'=>$this->token);
		if ($this->isBranch){
			$id=intval($_GET['id']);
			$where['id']=$id;
			$where['isbranch']=1;
		}else {
			$where['isbranch']=0;
		}
		$thisCompany=$this->company_model->where($where)->find();
		if(IS_POST){
			if (!$thisCompany){
				if ($this->isBranch){
					$this->insert('Company',U('Company/branches',array('token'=>$this->token,'isBranch'=>$this->isBranch)));
				}else {
					$this->insert('Company',U('Company/index',array('token'=>$this->token,'isBranch'=>$this->isBranch)));
				}
			}else {
				if($this->company_model->create()){
					if($this->company_model->where($where)->save($_POST)){
						if ($this->isBranch){
							$this->success('修改成功',U('Company/branches',array('token'=>$this->token,'isBranch'=>$this->isBranch)));
						}else{
							$this->success('修改成功',U('Company/index',array('token'=>$this->token,'isBranch'=>$this->isBranch)));
						}
					}else{
						$this->error('操作失败');
					}
				}else{
					$this->error($this->company_model->getError());
				}
			}
			
		}else{
			$this->assign('set',$thisCompany);
			$this->display();
		}
	}
	public function branches(){
		$branches=$this->company_model->where(array('isbranch'=>1,'token'=>$this->token))->order('taxis ASC')->select();
		$this->assign('branches',$branches);
		$this->display();
	}
	public function delete(){
		$where=array('token'=>$this->token,'isbranch'=>1,'id'=>intval($_GET['id']));
		$rt=$this->company_model->where($where)->delete();
		if($rt==true){
			$this->success('删除成功',U('Company/branches',array('token'=>$this->token,'isBranch'=>1)));
		}else{
			$this->error('服务器繁忙,请稍后再试',U('Company/branches',array('token'=>$this->token,'isBranch'=>1)));
		}
	}
	//店铺分类
	public function company_cate(){
		$where['token']=$this->token;
		if($this->_GET('type')){
			$where['type']=$this->_GET('type');
		}
		$company_cate=M('company_cate')->where($where)->select();
		$this->assign('company_cate',$company_cate);
		$this->display();
	}
	public function company_cate_set(){
		$id=$this->_GET('id');
		$token=$this->token;
		if(!$token){
			$this->error('不合法token');exit;
		}
		$db   = M('company_cate');
		if(IS_POST){
			$data['name']=$this->_POST('name',trim);
			$data['logourl']=$this->_POST('logourl',trim);
			$data['info']=$this->_POST('info',trim);
			$data['type']=$this->_POST('type',trim);
			$data['token']=$token;
			$data['status']=$this->_POST('status',trim);
			if($id){
				if ($db->create()) {
					if($db->where(array('id'=>$id))->save($data)){
						$this->success('修改成功',U('Company/company_cate',array('token'=>$this->token)));
					}else{
						$this->error('修改失败');
					}
				}
			}else{
				if ($db->create()) {
					if($db->add($data)){
						$this->success('添加成功',U('Company/company_cate',array('token'=>$this->token)));
					}else{
						$this->error('添加失败');
					}
				}
			}
		}else{
			$company_cate=$db->where(array('id'=>$id,'token'=>$token))->find();
			$this->assign('set',$company_cate);
			$this->display();
		}
	}
	public function company_cate_del(){
		$where['id']=$this->_GET('id');
		$where['token']=$this->token;
		if(!$where['token']){
			$this->error('不合法token');exit;
		}
		if(M('company_cate')->where($where)->delete()){
			$this->success('删除成功',U('Company/company_cate',array('token'=>$this->token)));
		}else{
			$this->error('服务器繁忙,请稍后再试',U('Company/company_cate',array('token'=>$this->token)));
		}
	}
	//区域设置
	public function company_area(){
		$where['token']=$this->token;
		if($this->_GET('type')){
			$where['type']=$this->_GET('type');
		}
		$company_area=M('company_area')->where($where)->select();
		$this->assign('company_area',$company_area);
		$this->display();
	}
	public function company_area_set(){
		$id=$this->_GET('id',trim);
		$token=$this->token;
		if(!$token){
			$this->error('不合法token');exit;
		}
		$db   = M('company_area');
		if(IS_POST){
			$data['name']=$this->_POST('name',trim);
			$data['logourl']=$this->_POST('logourl',trim);
			$data['info']=$this->_POST('info',trim);
			$data['type']=$this->_POST('type',trim);
			$data['token']=$token;
			$data['status']=$this->_POST('status',trim);
			if($id){
				if ($db->create()) {
					if($db->where(array('id'=>$id))->save($data)){
						$this->success('修改成功',U('Company/company_area',array('token'=>$this->token)));
					}else{
						$this->error('修改失败');
					}
				}
			}else{
				if ($db->create()) {
					if($db->add($data)){
						$this->success('添加成功',U('Company/company_area',array('token'=>$this->token)));
					}else{
						$this->error('添加失败');
					}
				}
			}
		}else{
			$company_area=$db->where(array('id'=>$id,'token'=>$token))->find();
			$this->assign('set',$company_area);
			$this->display();
		}
	}
	public function company_area_del(){
		$where['id']=$_GET('id',trim);
		$where['token']=$this->token;
		if(!$where['token']){
			$this->error('不合法token');exit;
		}
		if(M('company_area')->where($where)->delete()){
			$this->success('删除成功',U('Company/company_area',array('token'=>$this->token)));
		}else{
			$this->error('服务器繁忙,请稍后再试',U('Company/company_area',array('token'=>$this->token)));
		}
	}
}


?>