<?php

class LinkAction extends UserAction
{
    public $where;
    public $modules;
    public function _initialize()
    {
        parent::_initialize();
        $this->where = array('token' => $this->token);
        $this->modules = array('Home' => '首页', 'Classify' => '网站分类', 'Img' => '图文回复', 'Company' => 'LBS信息', 'Adma' => 'DIY宣传页', 'Photo' => '相册', 'Selfform' => '万能表单', 'Host' => '商家订单', 'Groupon' => '团购', 'Shop' => '商城', 'Dining' => '订餐', 'Wedding' => '婚庆喜帖', 'Vote' => '投票', 'Panorama' => '全景', 'Lottery' => '大转盘', 'Guajiang' => '刮刮卡', 'Zadan' => '砸金蛋','Liuyan' => '留言', 'Coupon' => '优惠券', 'MemberCard' => '会员卡', 'Estate' => '微房产', 'Jiudian' => '微酒店', 'Yiliao' => '微医疗', 'Yuyue' => '微预约', 'Diaoyan' => '微调研','Zuche'=>'微租车','Car'=>'微汽车','Lvyou'=>'微旅游','Jianshen'=>'微健身','Jiaoyu'=>'微教育','Jiuba'=>'微酒吧','Ktv'=>'微Ktv','Meirong'=>'微美容');
    }
    public function insert()
    {
        $modules = $this->modules();
        $this->assign('modules', $modules);
        $this->display();
    }
    public function modules()
    {
        return array(
			array('module' => 'Home', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Index&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => '微网站首页', 'sub' => 0, 'canselected' => 1, 'linkurl' => '', 'keyword' => $this->modules['Home'], 'askeyword' => 1), 
			array('module' => 'Classify', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Index&a=lists&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Classify'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 0),
			array('module' => 'Img', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Index&a=content&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Img'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Company', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Company&a=map&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Company'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '地图', 'askeyword' => 1),
			array('module' => 'Adma', 'linkcode' => '{siteUrl}/index.php/show/' . $this->token, 'name' => $this->modules['Adma'], 'sub' => 0, 'canselected' => 1, 'linkurl' => '', 'keyword' => '', 'askeyword' => 0),
			array('module' => 'Photo', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Photo&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Photo'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '相册', 'askeyword' => 1),
			array('module' => 'Selfform', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Selfform&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Selfform'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Host', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Host&a=detail&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Host'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Groupon', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Groupon&a=grouponIndex&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Groupon'], 'sub' => 0, 'canselected' => 1, 'linkurl' => '', 'keyword' => '团购', 'askeyword' => 1), 
			array('module' => 'Shop', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Product&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Shop'], 'sub' => 0, 'canselected' => 1, 'linkurl' => '', 'keyword' => '商城', 'askeyword' => 1), 
			array('module' => 'Dining', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Dining&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Dining'], 'sub' => 0, 'canselected' => 1, 'linkurl' => '', 'keyword' => '订餐', 'askeyword' => 1), 
			array('module' => 'Wedding', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Wedding&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Wedding'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Vote', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Vote&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Vote'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1), 
			array('module' => 'Panorama', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Panorama&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Panorama'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => $this->modules['Panorama'], 'askeyword' => 1), 
			array('module' => 'Lottery', 'linkcode' => '', 'name' => $this->modules['Lottery'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1), 
			array('module' => 'Guajiang', 'linkcode' => '', 'name' => $this->modules['Guajiang'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Zadan', 'linkcode' => '', 'name' => $this->modules['Zadan'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Coupon', 'linkcode' => '', 'name' => $this->modules['Coupon'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'MemberCard', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Card&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['MemberCard'], 'sub' => 0, 'canselected' => 1, 'linkurl' => '', 'keyword' => '会员卡', 'askeyword' => 1),
			array('module' => 'Estate', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Estate&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'name' => $this->modules['Estate'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '微房产', 'askeyword' => 1),
			array('module' => 'Lvyou', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Lvyou&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'name' => $this->modules['Lvyou'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '微旅游', 'askeyword' => 1),
			array('module' => 'Jiaoyu', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiaoyu&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'name' => $this->modules['Jiaoyu'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '微教育', 'askeyword' => 1),
			array('module' => 'Jiuba', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiuba&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'name' => $this->modules['Jiuba'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '微酒吧', 'askeyword' => 1),
			array('module' => 'Jianshen', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jianshen&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'name' => $this->modules['Jianshen'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '微健身', 'askeyword' => 1),
			array('module' => 'Ktv', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Ktv&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'name' => $this->modules['Ktv'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '微KTV', 'askeyword' => 1),
			array('module' => 'Meirong', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Meirong&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'name' => $this->modules['Meirong'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '微美容', 'askeyword' => 1),
			array('module' => 'Jiudian', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiudian&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Jiudian'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Yiliao', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Yiliao&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Yiliao'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Yuyue', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Yuyue&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Yuyue'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Diaoyan', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Diaoyan&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Diaoyan'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1), 
			array('module' => 'Zuche', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Zuche&a=stores&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Zuche'], 'sub' => 0, 'canselected' => 1, 'linkurl' => '', 'keyword' => '租车', 'askeyword' => 1),
			array('module' => 'Car', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Car&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'name' => $this->modules['Car'], 'sub' => 1, 'canselected' => 1, 'linkurl' => '', 'keyword' => '微汽车', 'askeyword' => 1),
			array('module' => 'Liuyan', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Liuyan&a=index&token=' . $this->token) . '&wecha_id={wechat_id}', 'name' => $this->modules['Liuyan'], 'sub' => 0, 'canselected' => 1, 'linkurl' => '', 'keyword' => '留言', 'askeyword' => 1));
    }
    public function Classify()
    {
        $this->assign('moduleName', $this->modules['Classify']);
        $db = M('Classify');
        $where = $this->where;
		$where['url']='';
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['name'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Index&a=lists&token=' . $this->token) . '&wecha_id={wechat_id}&classid=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Img()
    {
        $this->assign('moduleName', $this->modules['Img']);
        $db = M('Img');
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Index&a=content&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Company()
    {
        $this->assign('moduleName', $this->modules['Company']);
        $db = M('Company');
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['name'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Company&a=map&token=' . $this->token) . '&wecha_id={wechat_id}&companyid=') . $item['id'], 'linkurl' => '', 'keyword' => '地图'));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Photo()
    {
        $this->assign('moduleName', $this->modules['Photo']);
        $db = M('Photo');
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Photo&a=plist&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Selfform()
    {
        $this->assign('moduleName', $this->modules['Selfform']);
        $db = M('Selfform');
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['name'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Selfform&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Host()
    {
        $moduleName = 'Host';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $db = M($moduleName);
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['name'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Host&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&hid=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Panorama()
    {
        $this->assign('moduleName', $this->modules['Panorama']);
        $db = M('Panorama');
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('time DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['name'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Panorama&a=item&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Wedding()
    {
        $moduleName = 'Wedding';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $db = M($moduleName);
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Wedding&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Lottery()
    {
        $moduleName = 'Lottery';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $db = M($moduleName);
        $where = $this->where;
        $where['type'] = 1;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Lottery&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Guajiang()
    {
        $moduleName = 'Guajiang';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $db = M('Lottery');
        $where = $this->where;
        $where['type'] = 2;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Guajiang&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
	public function Zadan()
    {
        $moduleName = 'Zadan';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $db = M('Lottery');
        $where = $this->where;
        $where['type'] = 4;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Guajiang&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Coupon()
    {
        $moduleName = 'Coupon';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $db = M('Lottery');
        $where = $this->where;
        $where['type'] = 3;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Coupon&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Vote()
    {
        $moduleName = 'Vote';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $db = M($moduleName);
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Vote&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    public function Estate()
    {
        $moduleName = 'Estate';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $items = array();
        array_push($items, array('id' => 1, 'name' => '楼盘介绍', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Estate&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微房产'));
        array_push($items, array('id' => 2, 'name' => '楼盘相册', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Estate&a=album&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微房产'));
        array_push($items, array('id' => 3, 'name' => '户型全景', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Estate&a=housetype&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微房产'));
        array_push($items, array('id' => 4, 'name' => '专家点评', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Estate&a=impress&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微房产'));
        $Estate = M('Estate')->where(array('token' => $this->token))->find();
        $rt = M('Yuyue')->where(array('id' => $Estate['res_id']))->find();
        array_push($items, array('id' => 5, 'name' => '看房预约', 'linkcode' => ((('{siteUrl}/index.php?g=Wap&m=Yuyue&a=index&id=' . $Estate['res_id']) . '&token=') . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => $rt['keyword']));
        $this->assign('list', $items);
        $this->display('detail');
    }
	 public function Lvyou()
    {
        $moduleName = 'Lvyou';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $items = array();
        array_push($items, array('id' => 1, 'name' => '景区介绍', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Lvyou&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
        array_push($items, array('id' => 2, 'name' => '风景照片', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Lvyou&a=album&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
        array_push($items, array('id' => 3, 'name' => '景点展示', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Lvyou&a=housetype&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
        array_push($items, array('id' => 4, 'name' => '游客印象', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Lvyou&a=impress&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
		array_push($items, array('id' => 5, 'name' => '新闻动态', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Lvyou&a=news&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
		array_push($items, array('id' => 6, 'name' => '关于我们', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Lvyou&a=aboutus&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
        $rt = M('Yuyue')->where(array('token' => $this->token,'type'=>'lvyou'))->select();
		foreach($rt as $key=>$vo){
        array_push($items, array('id' => 7+$key, 'name' => $vo['title'], 'linkcode' => ((('{siteUrl}/index.php?g=Wap&m=Yuyue&a=index&id=' . $vo['id']) . '&token=') . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => $vo['keyword']));
        }
		$this->assign('list', $items);
        $this->display('detail');
    }
	 public function Jianshen()
    {
        $moduleName = 'Jianshen';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $items = array();
        array_push($items, array('id' => 1, 'name' => '俱乐部介绍', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jianshen&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
        array_push($items, array('id' => 2, 'name' => '健身相册', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jianshen&a=album&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
        array_push($items, array('id' => 3, 'name' => '健身室展示', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jianshen&a=housetype&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
        array_push($items, array('id' => 4, 'name' => '会员印象', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jianshen&a=impress&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
		array_push($items, array('id' => 5, 'name' => '新闻动态', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jianshen&a=news&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
		array_push($items, array('id' => 6, 'name' => '关于我们', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jianshen&a=aboutus&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微旅游'));
        $rt = M('Yuyue')->where(array('token' => $this->token,'type'=>'jianshen'))->select();
		foreach($rt as $key=>$vo){
        array_push($items, array('id' => 7+$key, 'name' => $vo['title'], 'linkcode' => ((('{siteUrl}/index.php?g=Wap&m=Yuyue&a=index&id=' . $vo['id']) . '&token=') . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => $vo['keyword']));
        }
		$this->assign('list', $items);
        $this->display('detail');
    }
	 public function Jiaoyu()
    {
        $moduleName = 'Jiaoyu';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $items = array();
        array_push($items, array('id' => 1, 'name' => '学校介绍', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiaoyu&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微教育'));
        array_push($items, array('id' => 2, 'name' => '教学相册', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiaoyu&a=album&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微教育'));
        array_push($items, array('id' => 3, 'name' => '培训课程', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiaoyu&a=housetype&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微教育'));
        array_push($items, array('id' => 4, 'name' => '学员印象', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiaoyu&a=impress&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微教育'));
		array_push($items, array('id' => 5, 'name' => '新闻动态', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiaoyu&a=news&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微教育'));
		array_push($items, array('id' => 6, 'name' => '关于我们', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiaoyu&a=aboutus&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微教育'));
        $rt = M('Yuyue')->where(array('token' => $this->token,'type'=>'jiaoyu'))->select();
		foreach($rt as $key=>$vo){
        array_push($items, array('id' => 7+$key, 'name' => $vo['title'], 'linkcode' => ((('{siteUrl}/index.php?g=Wap&m=Yuyue&a=index&id=' . $vo['id']) . '&token=') . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => $vo['keyword']));
        }
		$this->assign('list', $items);
        $this->display('detail');
    }
	 public function Jiuba()
    {
        $moduleName = 'Jiuba';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $items = array();
        array_push($items, array('id' => 1, 'name' => '酒吧介绍', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiuba&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微酒吧'));
        array_push($items, array('id' => 2, 'name' => '酒水相册', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiuba&a=album&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微酒吧'));
        array_push($items, array('id' => 3, 'name' => '特色酒水', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiuba&a=housetype&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微酒吧'));
        array_push($items, array('id' => 4, 'name' => '会员印象', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiuba&a=impress&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微酒吧'));
		array_push($items, array('id' => 5, 'name' => '新闻动态', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiuba&a=news&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微酒吧'));
		array_push($items, array('id' => 6, 'name' => '关于我们', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Jiuba&a=aboutus&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微酒吧'));
        $rt = M('Yuyue')->where(array('token' => $this->token,'type'=>'Jiuba'))->select();
		foreach($rt as $key=>$vo){
        array_push($items, array('id' => 7+$key, 'name' => $vo['title'], 'linkcode' => ((('{siteUrl}/index.php?g=Wap&m=Yuyue&a=index&id=' . $vo['id']) . '&token=') . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => $vo['keyword']));
        }
		$this->assign('list', $items);
        $this->display('detail');
    }
	 public function Ktv()
    {
        $moduleName = 'Ktv';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $items = array();
        array_push($items, array('id' => 1, 'name' => '健身房介绍', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Ktv&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微Ktv'));
        array_push($items, array('id' => 2, 'name' => '宣传相册', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Ktv&a=album&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微Ktv'));
        array_push($items, array('id' => 3, 'name' => 'KTV包厢', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Ktv&a=housetype&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微Ktv'));
        array_push($items, array('id' => 4, 'name' => '游客印象', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Ktv&a=impress&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微Ktv'));
		array_push($items, array('id' => 5, 'name' => '新闻动态', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Ktv&a=news&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微Ktv'));
		array_push($items, array('id' => 6, 'name' => '关于我们', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Ktv&a=aboutus&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微Ktv'));
        $rt = M('Yuyue')->where(array('token' => $this->token,'type'=>'Ktv'))->select();
		foreach($rt as $key=>$vo){
        array_push($items, array('id' => 7+$key, 'name' => $vo['title'], 'linkcode' => ((('{siteUrl}/index.php?g=Wap&m=Yuyue&a=index&id=' . $vo['id']) . '&token=') . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => $vo['keyword']));
        }
		$this->assign('list', $items);
        $this->display('detail');
    }
	 public function Meirong()
    {
        $moduleName = 'Meirong';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $items = array();
        array_push($items, array('id' => 1, 'name' => '美容店简介', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Meirong&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微美容'));
        array_push($items, array('id' => 2, 'name' => '美容相册', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Meirong&a=album&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微美容'));
        array_push($items, array('id' => 3, 'name' => '美容服务', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Meirong&a=housetype&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微美容'));
        array_push($items, array('id' => 4, 'name' => '客人印象', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Meirong&a=impress&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微美容'));
		array_push($items, array('id' => 5, 'name' => '新闻动态', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Meirong&a=news&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微美容'));
		array_push($items, array('id' => 6, 'name' => '关于我们', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Meirong&a=aboutus&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微美容'));
        $rt = M('Yuyue')->where(array('token' => $this->token,'type'=>'Meirong'))->select();
		foreach($rt as $key=>$vo){
        array_push($items, array('id' => 7+$key, 'name' => $vo['title'], 'linkcode' => ((('{siteUrl}/index.php?g=Wap&m=Yuyue&a=index&id=' . $vo['id']) . '&token=') . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => $vo['keyword']));
        }
		$this->assign('list', $items);
        $this->display('detail');
    }
	public function Car()
    {
        $moduleName = 'Car';
        $this->assign('moduleName', $this->modules[$moduleName]);
        $items = array();
        array_push($items, array('id' => 1, 'name' => '品牌介绍', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Car&a=brands&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微汽车'));
        array_push($items, array('id' => 2, 'name' => '销售顾问', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Car&a=salers&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微汽车'));
        array_push($items, array('id' => 3, 'name' => '预约试驾', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Car&a=CarReserveBook&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微汽车'));
        array_push($items, array('id' => 4, 'name' => '车主关怀', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Car&a=owner&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微汽车'));
		array_push($items, array('id' => 5, 'name' => '实用工具', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Car&a=tool&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微汽车'));
		array_push($items, array('id' => 6, 'name' => '车型欣赏', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Car&a=showcar&token=' . $this->token) . '&wecha_id={wechat_id}&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' => '微汽车'));
        array_push($items, array('id' => 7, 'name' => '预约保养', 'linkcode' => ('{siteUrl}/index.php?g=Wap&m=Car&a=CarReserveBook&token=' . $this->token) . '&wecha_id={wechat_id}&addtype=maintain&sgssz=mp.weixin.qq.com', 'linkurl' => '', 'keyword' =>'微汽车' ));
        $this->assign('list', $items);
        $this->display('detail');
    }
	public function Jiudian()
    {
        $this->assign('moduleName', $this->modules['Jiudian']);
        $db = M('Jiudian');
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Jiudian&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
	public function Yiliao()
    {
        $this->assign('moduleName', $this->modules['Yiliao']);
        $db = M('Yiliao');
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Yiliao&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
	public function Yuyue()
    {
        $this->assign('moduleName', $this->modules['Yuyue']);
        $db = M('Yuyue');
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Yuyue&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
	public function Diaoyan()
    {
        $this->assign('moduleName', $this->modules['Diaoyan']);
        $db = M('Diaoyan');
        $where = $this->where;
        $count = $db->where($where)->count();
        $Page = new Page($count, 5);
        $show = $Page->show();
        $list = $db->where($where)->limit(($Page->firstRow . ',') . $Page->listRows)->order('id DESC')->select();
        $items = array();
        if ($list) {
            foreach ($list as $item) {
                array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'linkcode' => (('{siteUrl}/index.php?g=Wap&m=Diaoyan&a=index&token=' . $this->token) . '&wecha_id={wechat_id}&id=') . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
            }
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
}
?>