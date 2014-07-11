<?php 
$index = array(
		array(
				'name' => '我的助手',
				'ico'=>'home',
				'sub' => array(
						array(
								'name'=>'账号信息',
								'file'=>'userCenter/myAccount'
						),
						array(
								'name'=>'修改密码',
								'file'=>'userCenter/updatePwd'
						),
						array(
								'name'=>'微支付配置',
								'file'=>'weiqipay/pay'
						),
						array(
								'name'=>'短信配置',
								'file'=>'weiqiother/Sms'
						),
						array(
								'name'=>'邮件配置',
								'file'=>'weiqiother/Email'
						),
						array(
								'name'=>'无线打印配置',
								'file'=>'weiqiother/Prinet'
						),
						array(
								'name'=>'公共账号管理',
								'file'=>'userCenter/pubs'
						),
						array(
								'name'=>'运营图表',
								'file'=>'userCenter/statisticalData'
						),
						array(
								'name'=>'在线升级',
								'file'=>'cost/topay'
						)
						
				)
		),
		array(
				'name' => '素材库',
				'ico'=>'book',
				'sub' => array(
						array(
								'name'=>'首次关注',
								'file'=>'baseService/firstAttention'
						),
						array(
								'name'=>'关键字回复',
								'file'=>'baseService/keyword'
						),
						array(
								'name'=>'素材管理',
								'file'=>'baseService/contresource'
						),
						array(
								'name'=>'LBS设置',
								'file'=>'baseService/lbs'
						)
				)
		  ),
		array(
				'name' => '智能客服',
				'ico'=>'comments',
				'sub' => array(
						array(
								'name'=>'客服调教',
								'file'=>'baseService/intelligentServiceGuide'
						)
				)
		),
		array(
				'name' => '微服务',
				'ico'=>'paste',
				'sub' => array(
						array(
								'name'=>'应用管理',
								'file'=>'userCenter/sysapp'
						)
				)
		),
		array(
				'name' => '自定义菜单',
				'ico'=>'reorder',
				'sub' => array(
						array(
								'name'=>'授权设置',
								'file'=>'baseService/customSecImpower'
						),
						array(
								'name'=>'菜单设置',
								'file'=>'baseService/customMenu'
						 )
				       )
		      ),
		
		/**array(
				'name' => '微信会员卡',
				'ico'=>'user',
				'sub' => array(
						array(
								'name'=>'会员卡设置',
								'file'=>'businessModule/microMemberCard'
						),
						array(
								'name'=>'会员卡添加',
								'file'=>'businessModule/microMemberCardAdd'
						)
				)
		),**/
		array(
				'name' => '第三方接口',
				'ico'=>'eye-open',
				'sub' => array(
						array(
								'name'=>'第三方接口设置',
								'file'=>'api/list'
						)
				)
		),
		array(
				'name' => '微会员卡新',
				'ico'=>'user',
				'du'=>'<span class="badge badge-info">New</span>',
				'sub' => array(
				        array(
								'name'=>'关键字设置',
								'file'=>'weiqimber/mberkeyword'
						),
						array(
								'name'=>'店员设置',
								'file'=>'weiqimber/mber-dianyuan'
						),
						array(
								'name'=>'添加会员卡',
								'file'=>'weiqimber/mber-add'
						),
						array(
								'name'=>'会员卡管理',
								'file'=>'weiqimber/mber-member'
						),
						array(
								'name'=>'会员卡充值',
								'file'=>'weiqimber/mber-chongzhi'
						)
				)
		),
		array(
				'name' => '微官网',
				'ico'=>'globe',
				'sub' => array(
				        array(
								'name'=>'关键字设置',
								'file'=>'adds/webkeyword'
						),
						array(
								'name'=>'微模板预览',
								'file'=>'web/setview'
						),
						array(
								'name'=>'微官网设置',
								'file'=>'web/wsite'
						),
						array(
								'name'=>'微网站备份',
								'file'=>'web/beifen'
						),
						array(
								'name'=>'背景音乐设置',
								'file'=>'web/weiqimusic'
						),
						array(
								'name'=>'菜单导航设置',
								'file'=>'weiqiwebmenu/set'
						),
						array(
								'name'=>'背景轮播设置',
								'file'=>'web/weiqiwebbg'
						),
						array(
								'name'=>'底部版权设置',
								'file'=>'web/weiqiver'
						),
						array(
								'name'=>'游戏和功能',
								'file'=>'web/yx'
						)
		
				)
		),
		array(
				'name' => '摇一摇',
				'ico'=>'windows',
				'sub' => array(
						array(
								'name'=>'关键字设置',
								'file'=>'weiqiyyy/yyykeyword'
						),
						array(
								'name'=>'摇一摇配置',
								'file'=>'weiqiyyy/yyy'
						)	
						
				)
		),
		array(
				'name' => '微客服',
				'ico'=>'edit',
				'sub' => array(
						array(
								'name'=>'微客服系统配置',
								'file'=>'weiqikf/kefu'
						)
						
				)
		),
		array(
				'name' => '微动态',
				'ico'=>'weibo',
				'sub' => array(
						array(
								'name'=>'微动态系统配置',
								'file'=>'weiqidt/dongtai'
						)
						
				)
		),
		array(
				'name' => '微信墙',
				'ico'=>'list-alt',
				'sub' => array(
						array(
								'name'=>'微信墙设置',
								'file'=>'wall/set'
						),
						array(
								'name'=>'数据管理',
								'file'=>'wall/data'
						),
						array(
								'name'=>'微信墙审核',
								'file'=>'wall/check'
						)
				)
		),
		array(
				'name' => '微 WIFI',
				'ico'=>'signal',
				'sub' => array(
						array(
						'name'=>'关键字设置',
						'file'=>'weiqiwifi/wifikeyword'
						),
						array(
						'name'=>'设备管理',
						'file'=>'weiqiwifi/config'
						),
				)
		),
		 array(
        'name' => '微粉WIFI',
		'ico'=>'signal',
		'du'=>'<span class="badge badge-important">New</span>',
        'sub' => array(
            array(
                'name' => 'WIFI添加',
                'file' => 'wifi/add'
            ),
            array(
                'name' => 'WIFI列表',
                'file' => 'wifi/list'
            ),
            array(
                'name' => '自定义关注页',
                'file' => 'wifi/set'
            ),
            array(
                'name' => '自定义成功页',
                'file' => 'wifi/setad'
            ),
           
        )
    ),

		array(
				'name' => '微餐饮',
				'ico'=>'food',
				'sub' => array(
						array(
								'name'=>'关键字设置',
								'file'=>'weiqicy/canyinkeyword'
						),

						array(
								'name'=>'店铺LBS设置',
								'file'=>'weiqicy/canyin-6'
						),
						array(
								'name'=>'店铺信息设置',
								'file'=>'weiqicy/canyin-5'
						),
						array(
								'name'=>'分类管理',
								'file'=>'weiqicy/canyin-1'
						),
						array(
								'name'=>'菜品管理',
								'file'=>'weiqicy/canyin-2'
						),
						array(
								'name'=>'桌台管理',
								'file'=>'weiqicy/canyin-3'
						),
						array(
								'name'=>'订单管理',
								'file'=>'weiqicy/canyin-4'
						),
				)
		),
		array(
				'name' => '微商城',
				'ico'=>'shopping-cart',
				'sub' => array(
						array(
								'name'=>'关键字设置',
								'file'=>'weiqishop/shopkeyword'
						),
						array(
								'name'=>'幻灯片配置',
								'file'=>'weiqishop/shophd'
						),
						array(
								'name'=>'分类管理',
								'file'=>'weiqishop/shop-fl'
						),
						array(
								'name'=>'商品管理',
								'file'=>'weiqishop/shop-sp'
						),
						array(
								'name'=>'订单管理',
								'file'=>'weiqishop/shop-dd'
						)
				)
		),
	
		array(
				'name' => '微活动',
				'ico'=>'trello',
				'sub' => array(
						array(
								'name'=>'优惠券',
								'file'=>'marketingPromotion/discountCoupon'
						),
						array(
								'name'=>'刮刮卡',
								'file'=>'marketingPromotion/ggk'
						),
						array(
								'name'=>'幸运机',
								'file'=>'marketingPromotion/xyj'
						),
						array(
								'name'=>'大转盘',
								'file'=>'marketingPromotion/xydzp'
						),
						array(
								'name'=>'一站到底',
								'file'=>'marketingPromotion/yzdd'
						),
						array(
								'name'=>'一站到底题库管理',
								'file'=>'marketingPromotion/yzddtk'
						)
						
						
				)
		),
		array(
				'name' => '万能表单',
				'ico'=>'tag',
				'sub' => array(
						array(
								'name'=>'万能表单配置',
								'file'=>'weiqiother/wannengbiaodan'
						)
				)
		),
		array(
				'name' => '音乐盒',
				'ico'=>'music',
				'sub' => array(
						array(
								'name'=>'关键字设置',
								'file'=>'music/keyword'
						),
						array(
								'name'=>'音乐列表管理',
								'file'=>'music/list'
						),
						array(
								'name'=>'音乐管理',
								'file'=>'music/music'
						)
		
				)
		),
		array(
				'name' => '微相册',
				'ico'=>'picture',
				'sub' => array(
						array(
								'name'=>'相册设置',
								'file'=>'xiangce/set'
						),
						array(
								'name'=>'相册管理',
								'file'=>'xiangce/list'
						)
				)
		),
		array(
				'name' => 'DIY宣传页',
				'ico'=>'crop',
				'sub' => array(
						array(
								'name'=>'宣传页设置',
								'file'=>'weiqidiy/diy'
						),
				)
		),
		array(
				'name' => '360全景图',
				'ico'=>'camera-retro',
				'sub' => array(
						array(
								'name'=>'全景图管理',
								'file'=>'quanjing/qj'
						),
						array(
								'name'=>'添加全景图',
								'file'=>'quanjing/qjadd'
						)
				)
		),
		
		array(
				'name' => '微留言',
				'ico'=>'comment',
				'sub' => array(
						array(
								'name'=>'留言板设置',
								'file'=>'liuyan/set'
						),
						array(
								'name'=>'留言管理',
								'file'=>'liuyan/mg'
						),
						array(
								'name'=>'黑名单管理',
								'file'=>'liuyan/hei'
						)
				)
		),
		array(
				'name' => '微点菜',
				'ico'=>'building',
				'sub' => array(
						array(
								'name'=>'公司设置',
								'file'=>'diancai/loupanjianjie'
						),				
						array(
								'name'=>'菜品分类管理',
								'file'=>'diancai/ziloupan'
						),
						array(
								'name'=>'菜品管理',
								'file'=>'diancai/loupanhuxing'
						),
						
						array(
								'name'=>'订单管理',
								'file'=>'diancai/loupanhaibao'
						)
				)
		),
		array(
				'name' => '微房产',
				'ico'=>'map-marker',
				'sub' => array(
						array(
								'name'=>'楼盘简介',
								'file'=>'fangchan/loupanjianjie'
						),
						array(
								'name'=>'楼盘海报',
								'file'=>'fangchan/loupanhaibao'
						),						
						array(
								'name'=>'子楼盘',
								'file'=>'fangchan/ziloupan'
						),
						array(
								'name'=>'楼盘户型',
								'file'=>'fangchan/loupanhuxing'
						),
						array(
								'name'=>'楼盘相册',
								'file'=>'fangchan/loupanxiangce'
						),
						array(
								'name'=>'房友印象',
								'file'=>'fangchan/fangyouyinxiang'
						),
						array(
								'name'=>'专家点评',
								'file'=>'fangchan/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微汽车',
				'ico'=>'shield',
				'sub' => array(
						array(
								'name'=>'关键字回复',
								'file'=>'car/keyword'
						),
						array(
								'name'=>'品牌管理',
								'file'=>'car/pinpai'
						),
						array(
								'name'=>'车系管理',
								'file'=>'car/chexi'
						),
						array(
								'name'=>'车型管理',
								'file'=>'car/chexing'
						),
						array(
								'name'=>'销售管理',
								'file'=>'car/xiaoshou'
						),
						array(
								'name'=>'预约保养',
								'file'=>'car/yyby'
						),
						array(
								'name'=>'预约试驾',
								'file'=>'car/yysj'
						),
						array(
								'name'=>'车主关怀',
								'file'=>'car/guanhuai'
						),
						array(
								'name'=>'实用工具',
								'file'=>'car/tool'
						)
		
				)
		),
		array(
				'name' => '微酒店',
				'ico'=>'screenshot',
				'sub' => array(
						array(
								'name'=>'酒店管理',
								'file'=>'jiudian/set'
						),	
				)
		),
		array(
				'name' => '微喜帖',
				'ico'=>'credit-card',
				'sub' => array(
						 array(
								'name'=>'功能设置',
								'file'=>'xitie/index'
						    )
				         )
		      ), 
		array(
				'name' => '盛世源码业',
				'ico'=>'compass',
				'sub' => array(      
						array(
								'name'=>'企业简介',
								'file'=>'qiye/loupanjianjie'
						),
						array(
								'name'=>'企业海报',
								'file'=>'qiye/loupanhaibao'
						),						
						array(
								'name'=>'分公司设置',
								'file'=>'qiye/ziloupan'
						),
						array(
								'name'=>'部门管理',
								'file'=>'qiye/loupanhuxing'
						),
						array(
								'name'=>'企业相册',
								'file'=>'qiye/loupanxiangce'
						)
					
				)
		),
		 	array(
				'name' => '微医疗',
				'ico'=>'stethoscope',
				'sub' => array(      
						array(
								'name'=>'医院简介',
								'file'=>'yiliao/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'yiliao/loupanhaibao'
						),						
						array(
								'name'=>'分院设置',
								'file'=>'yiliao/ziloupan'
						),
						array(
								'name'=>'科室管理',
								'file'=>'yiliao/loupanhuxing'
						),
						array(
								'name'=>'医院相册',
								'file'=>'yiliao/loupanxiangce'
						),
						array(
								'name'=>'客户印象',
								'file'=>'yiliao/fangyouyinxiang'
						),
						array(
								'name'=>'专家点评',
								'file'=>'yiliao/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微医疗新版',
				'ico'=>'stethoscope',
				'sub' => array(
						array(
						'name'=>'挂号设置',
						'file'=>'weiqiyiliao/guahao'
						),
						array(
						'name'=>'内容设置',
						'file'=>'weiqiyiliao/shezhi'
						),
						array(
						'name'=>'预约查询',
						'file'=>'weiqiyiliao/chaxun'
						),
						array(
						'name'=>'预约统计',
						'file'=>'weiqiyiliao/tongji'
						),
				)
		),
		array(
				'name' => '微食品',
				'ico'=>'suitcase',
				'sub' => array(
						array(
								'name'=>'食品公司简介',
								'file'=>'shipin/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'shipin/loupanhaibao'
						),						
						array(
								'name'=>'销售门店',
								'file'=>'shipin/ziloupan'
						),
						array(
								'name'=>'会员服务',
								'file'=>'shipin/loupanhuxing'
						),
						array(
								'name'=>'食品相册',
								'file'=>'shipin/loupanxiangce'
						),
						array(
								'name'=>'会员印象',
								'file'=>'shipin/fangyouyinxiang'
						),
						array(
								'name'=>'客户点评',
								'file'=>'shipin/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微美容',
				'ico'=>'rocket',
				'sub' => array(
						array(
								'name'=>'美容机构简介',
								'file'=>'meirong/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'meirong/loupanhaibao'
						),						
						array(
								'name'=>'美容店面',
								'file'=>'meirong/ziloupan'
						),
						array(
								'name'=>'美容服务',
								'file'=>'meirong/loupanhuxing'
						),
						array(
								'name'=>'宣传相册',
								'file'=>'meirong/loupanxiangce'
						),
						array(
								'name'=>'客户印象',
								'file'=>'meirong/fangyouyinxiang'
						),
						array(
								'name'=>'专家点评',
								'file'=>'meirong/zhuanjiadianping'
						)
				)
		),
		
		array(
				'name' => '微旅游',
				'ico'=>'star-empty',
				'sub' => array(
						array(
								'name'=>'旅游区简介',
								'file'=>'lvyou/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'lvyou/loupanhaibao'
						),						
						array(
								'name'=>'景区管理',
								'file'=>'lvyou/ziloupan'
						),
						array(
								'name'=>'景点管理',
								'file'=>'lvyou/loupanhuxing'
						),
						array(
								'name'=>'风景相册',
								'file'=>'lvyou/loupanxiangce'
						),
						array(
								'name'=>'游客印象',
								'file'=>'lvyou/fangyouyinxiang'
						),
						array(
								'name'=>'专家点评',
								'file'=>'lvyou/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微健身',
				'ico'=>'pinterest',
				'sub' => array(
						array(
								'name'=>'俱乐部简介',
								'file'=>'jianshen/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'jianshen/loupanhaibao'
						),						
						array(
								'name'=>'健身房',
								'file'=>'jianshen/ziloupan'
						),
						array(
								'name'=>'健身室',
								'file'=>'jianshen/loupanhuxing'
						),
						array(
								'name'=>'健身相册',
								'file'=>'jianshen/loupanxiangce'
						),
						array(
								'name'=>'会员印象',
								'file'=>'jianshen/fangyouyinxiang'
						),
						array(
								'name'=>'专家点评',
								'file'=>'jianshen/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微教育',
				'ico'=>'group',
				'sub' => array(
						array(
								'name'=>'培训机构简介',
								'file'=>'jiaoyu/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'jiaoyu/loupanhaibao'
						),
						array(
								'name'=>'校区管理',
								'file'=>'jiaoyu/ziloupan'
						),
						array(
								'name'=>'培训课程',
								'file'=>'jiaoyu/loupanhuxing'
						),
						array(
								'name'=>'教学相册',
								'file'=>'jiaoyu/loupanxiangce'
						),
						array(
								'name'=>'学员印象',
								'file'=>'jiaoyu/fangyouyinxiang'
						),
						array(
								'name'=>'家长点评',
								'file'=>'jiaoyu/zhuanjiadianping'
						)
				)
				
		),
		array(
				'name' => '微政务',
				'ico'=>'cloud',
				'sub' => array(
						array(
								'name'=>'政务部门简介',
								'file'=>'zhengwu/loupanjianjie'
						),
						array(
								'name'=>'服务海报',
								'file'=>'zhengwu/loupanhaibao'
						),						
						array(
								'name'=>'服务窗口',
								'file'=>'zhengwu/ziloupan'
						),
						array(
								'name'=>'服务类型',
								'file'=>'zhengwu/loupanhuxing'
						),
						array(
								'name'=>'政务相册',
								'file'=>'zhengwu/loupanxiangce'
						),
						array(
								'name'=>'市民印象',
								'file'=>'zhengwu/fangyouyinxiang'
						),
						array(
								'name'=>'领导点评',
								'file'=>'zhengwu/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微物业',
				'ico'=>'key',
				'sub' => array(
						array(
								'name'=>'物业公司简介',
								'file'=>'wuye/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'wuye/loupanhaibao'
						),						
						array(
								'name'=>'小区管理',
								'file'=>'wuye/ziloupan'
						),
						array(
								'name'=>'业主服务',
								'file'=>'wuye/loupanhuxing'
						),
						array(
								'name'=>'物业相册',
								'file'=>'wuye/loupanxiangce'
						),
						array(
								'name'=>'业主印象',
								'file'=>'wuye/fangyouyinxiang'
						),
						array(
								'name'=>'专家点评',
								'file'=>'wuye/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微KTV',
				'ico'=>'microphone',
				'sub' => array(
						array(
								'name'=>'经营主体简介',
								'file'=>'ktv/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'ktv/loupanhaibao'
						),
						array(
								'name'=>'KTV分店',
								'file'=>'ktv/ziloupan'
						),
						array(
								'name'=>'KTV包厢',
								'file'=>'ktv/loupanhuxing'
						),
						array(
								'name'=>'宣传相册',
								'file'=>'ktv/loupanxiangce'
						),
						array(
								'name'=>'客户印象',
								'file'=>'ktv/fangyouyinxiang'
						),
						array(
								'name'=>'客户点评',
								'file'=>'ktv/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微酒吧',
				'ico'=>'glass',
				'sub' => array(
						array(
								'name'=>'经营主体简介',
								'file'=>'jiuba/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'jiuba/loupanhaibao'
						),
						array(
								'name'=>'酒吧管理',
								'file'=>'jiuba/ziloupan'
						),
						array(
								'name'=>'特色酒水',
								'file'=>'jiuba/loupanhuxing'
						),
						array(
								'name'=>'酒吧相册',
								'file'=>'jiuba/loupanxiangce'
						),
						array(
								'name'=>'会员印象',
								'file'=>'jiuba/fangyouyinxiang'
						),
						array(
								'name'=>'客户点评',
								'file'=>'jiuba/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微婚庆',
				'ico'=>'warning-sign',
				'sub' => array(
						array(
								'name'=>'婚庆公司简介',
								'file'=>'hunqing/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'hunqing/loupanhaibao'
						),
						array(
								'name'=>'婚庆门店',
								'file'=>'hunqing/ziloupan'
						),
						array(
								'name'=>'成功案例',
								'file'=>'hunqing/loupanhuxing'
						),
						array(
								'name'=>'婚庆相册',
								'file'=>'hunqing/loupanxiangce'
						),
						array(
								'name'=>'客户印象',
								'file'=>'hunqing/fangyouyinxiang'
						),
						array(
								'name'=>'客户点评',
								'file'=>'hunqing/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微装修',
				'ico'=>'signout',
				'sub' => array(
						array(
								'name'=>'装修公司简介',
								'file'=>'zhuangxiu/loupanjianjie'
						),
						array(
								'name'=>'装修海报',
								'file'=>'zhuangxiu/loupanhaibao'
						),
						array(
								'name'=>'门店管理',
								'file'=>'zhuangxiu/ziloupan'
						),
						array(
								'name'=>'成功案例',
								'file'=>'zhuangxiu/loupanhuxing'
						),
						array(
								'name'=>'装修相册',
								'file'=>'zhuangxiu/loupanxiangce'
						),
						array(
								'name'=>'客户印象',
								'file'=>'zhuangxiu/fangyouyinxiang'
						),
						array(
								'name'=>'专家点评',
								'file'=>'zhuangxiu/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微花店',
				'ico'=>'rotate-right',
				'sub' => array(
						array(
								'name'=>'经营主体简介',
								'file'=>'huadian/loupanjianjie'
						),
						array(
								'name'=>'宣传海报',
								'file'=>'huadian/loupanhaibao'
						),
						array(
								'name'=>'门店管理',
								'file'=>'huadian/ziloupan'
						),
						array(
								'name'=>'特色鲜花',
								'file'=>'huadian/loupanhuxing'
						),
						array(
								'name'=>'花店相册',
								'file'=>'huadian/loupanxiangce'
						),
						array(
								'name'=>'客户印象',
								'file'=>'huadian/fangyouyinxiang'
						),
						array(
								'name'=>'客户点评',
								'file'=>'huadian/zhuanjiadianping'
						)
				)
		),
		array(
				'name' => '微团购',
				'ico'=>'barcode',
				'sub' => array(
						array(
								'name'=>'微团购设置',
								'file'=>'businessModule/microGroupBuy'
						),
						array(
								'name'=>'微团购新增',
								'file'=>'businessModule/microGroupBuyAdd'
						)
		
				)
		),
		array(
				'name' => '微话题',
				'ico'=>'tags',
				'sub' => array(
						array(
								'name'=>'微吧配置',
								'file'=>'marketingPromotion/weiba'
						),
						array(
								'name'=>'话题管理',
								'file'=>'marketingPromotion/weibaht'
						)
				)
		),
		
		
		array(
				'name' => '微调研',
				'ico'=>'italic',
				'sub' => array(
						array(
								'name'=>'调研管理',
								'file'=>'businessModule/microSurvey'
						),
						array(
								'name'=>'调研新增',
								'file'=>'businessModule/microSurveyAdd'
						)
		
				)
		),
		array(
				'name' => '微预约',
				'ico'=>'adjust',
				'sub' => array(
						array(
								'name'=>'新版预约管理',
								 'file'=>'newyy/yyby'
							
						),
						array(
								'name'=>'新版预约新增',
								'file'=>'newyy/yybynew'
							
						),
						array(
								'name'=>'老版本预约管理',
								'file'=>'businessModule/onlineBooking'
							
						),
				)
		),
		
		array(
				'name' => '微投票',
				'ico'=>'link',
				'sub' => array(
						array(
								'name'=>'微投票管理',
								'file'=>'businessModule/microVote'
						),
						array(
								'name'=>'微投票新增',
								'file'=>'businessModule/microVoteAdd'
						)
				)
		),
		array(
				'name' => '微门店',
				'ico'=>'linkedin',
				'sub' => array(
						array(
								'name'=>'门店管理',
								'file'=>'businessModule/shop'
						),
						array(
								'name'=>'门店新增',
								'file'=>'businessModule/shopadd'
						)
				)
		),
		array(
				'name' => '微贺卡',
				'ico'=>'h-sign',
				'sub' => array(
						array(
								'name'=>'贺卡大全',
								'file'=>'businessModule/whkdq'
						),
						array(
								'name'=>'微贺卡设置',
								'file'=>'businessModule/whk'
						)
				)
		)
		

);