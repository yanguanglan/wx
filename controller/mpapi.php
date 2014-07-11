<?php 
/**
  * 盛世源码 www.weiqimobile.com 
  * QQ 631989322
  * 盛世源码 2014-4-20 
  */
//define your token
define("TOKEN",md5(Request::get('appid').Conf::$management_center_target));
$wid = Request::get('appid');
if($GLOBALS["HTTP_RAW_POST_DATA"]){	
	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];	
	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);	
	//extract post data
	if (!empty($postStr)){
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$keyword = trim($postObj->Content);
		$type = $postObj->MsgType;
		if($type=='image'){
	$d = new Model('micro_wall_user_name');
	$d->find(array('wid'=>$wid,'wxid'=>$postObj->FromUserName));
	if($d->sqmode){
	if($d->mode == 2){
	$PicUrl = $postObj->PicUrl;
	$file = "ups/wall/".time().".jpg";
	file_put_contents("../public_html/".$file,HttpCurl::get($PicUrl));
	$d->img = trim(Conf::$http_path.$file);
	$d->mode = 3;
	$d->save();
	 		$res = "头像上传成功~尽情刷墙吧！";
			response_text($res,$postObj);
	}
	}
		}elseif($type=='location'){
			$data_tj = new Model('data_statistics');
			$data_tj->wid = $wid;
			$data_tj->type= 4;
			$data_tj->ctime= DB::raw('NOW()');
			$data_tj->save();
			//地理位置信息
			$m = new Model('lbs');
			$lres = $m->where(array('wid'=>$wid))->list_all();
			$jd = floatval($postObj->Location_Y);
			$wd = floatval($postObj->Location_X);
			$jlarr = array();
			$ja = false;
			foreach ($lres as $l){
				$jl = get_distance_by_lng_lat($jd,$wd,floatval($l->jd),floatval($l->wd));//Conf::$http_path.'weiweb/'.$wid.'/'
				$name = preg_replace("/\s/","",$l->name);
				$content = preg_replace("/\s/","",strip_tags($l->content));
				$hyurl = Conf::$http_path.'api/map.html?mtit='.urlencode($name).'&mcon='.urlencode($l->address).'&center='.$l->jd.','.$l->wd.'&zoom=20';
				$jlarr[$jl] = array('tit'=>($l->name.',距离'.$jl.'米'),'pic'=>Conf::$http_path.$l->pic,'url'=>$hyurl,'ms'=>$content);
				if(!$ja){
					$ja = $jlarr[$jl];
				}
			}		
			ksort($jlarr);
			if(count($lres)>1){
				response_more($jlarr,$postObj);
			}else{
				response_one($ja['tit'].'。',$ja['pic'],$ja['ms'],$ja['url'],$postObj);
			}
			
			
		}elseif($type=='event'){
			if('subscribe'==$postObj->Event){
				$data_tj = new Model('data_statistics');
				$data_tj->wid = $wid;
				$data_tj->type= 1;
				$data_tj->ctime= DB::raw('NOW()');
				$data_tj->save();
				//关注
				$fa = new Model('first_attention');
				$fa->find(array('wid'=>$wid));
				if($fa->has_id()){
					if($fa->typ=='0'){
						response_text($fa->content,$postObj);
					}else{
						$res = new Model('res');
						$res->find($fa->resource_id);
						response_arts($res,$postObj);
					}
				}else{
					
				}
				
			}if('unsubscribe'==$postObj->Event){
				$data_tj = new Model('data_statistics');
				$data_tj->wid = $wid;
				$data_tj->type= 5;
				$data_tj->ctime= DB::raw('NOW()');
				$data_tj->save();
			}elseif('CLICK'==$postObj->Event){
				$data_tj = new Model('data_statistics');
				$data_tj->wid = $wid;
				$data_tj->type= 3;
				$data_tj->ctime= DB::raw('NOW()');
				$data_tj->save();
				
				$key = $postObj->EventKey;
				//Log::error($key);
				/////////////////////////////--------------------------
				$key = explode('@', $key);
				if($key[0]=='res_wb'){
					//文本回复
					$ggk = new Model('res_text');
					$ggk->find($key[1]);
					if($ggk->has_id()){
						response_text($ggk->txt,$postObj);
					}
				}elseif($key[0]=='res_tw' || $key[0]=='res_dtw'){
					//图文回复
					$res = new Model('res');
					$res->find($key[1]);
					if($res->has_id()){
						response_arts($res,$postObj);
					}					
				}elseif($key[0]=='res_gjz'){
					//关键字
					$ggk = new Model('res_text');
					$ggk->find($key[1]);
					if($ggk->has_id()){
						check_and_replay($ggk->txt,$postObj);
					}
				}elseif($key[0]=='res_yhq'){
					//优惠券
					$coupon = new Model('coupon');
					$coupon->find($key[1]);
					if($coupon->has_id()){
						$url = Conf::$http_path.'wx/yhq-'.$coupon->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						response_one($coupon->name,Conf::$http_path.$coupon->pic,$coupon->ms,$url,$postObj);
						
					}
				}elseif($key[0]=='res_ggk'){
					//刮刮卡
					$ggk = new Model('ggk');
					$ggk->find($key[1]);
					if($ggk->has_id()){
						$url = Conf::$http_path.'wx/ggk-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
						
					}
				}elseif($key[0]=='res_xydzp'){
					//幸运大转盘
					$ggk = new Model('xydzp');
					$ggk->find($key[1]);
					if($ggk->has_id()){
						$url = Conf::$http_path.'wx/xydzp-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
						
					}
					}elseif($key[0]=='res_wgw'){
					$ggk = new Model('wwz_keyword');
					$ggk->find(array('wid'=>$wid));
					if($ggk->has_id()){
						$url = Conf::$http_path.'weiweb/'.$wid."/?wxid=".$postObj->FromUserName.'&wecha_id='.$postObj->FromUserName;
						response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
						
					}
				}elseif($key[0]=='res_xyj'){
					//幸运机
					$ggk = new Model('xyj');
					$ggk->find($key[1]);
					if($ggk->has_id()){
						$url = Conf::$http_path.'wx/xyj-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
						
					}
				}elseif($key[0]=='res_wtg'){
					//微团购
					$ggk = new Model('micro_group_buy');
					$ggk->find($key[1]);
					if($ggk->has_id()){
						$url = Conf::$http_path.'wx/wtg-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
						
					}
				}elseif($key[0]=='res_yzdd'){
					//一站到底
					$ggk = new Model('yzdd');
					$ggk->find($key[1]);
					if($ggk->has_id()){
						$url = Conf::$http_path.'wx/yzdd-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
						
					}
				}elseif($key[0]=='res_weiba'){
					//微吧
					$ggk = new Model('weiba');
					$ggk->find(array('wid'=>$wid));
					if($ggk->has_id()){
						$arts = array();
						$fart = array();
						$url = Conf::$http_path.'wx/weiba-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						$fart['tit'] = $ggk->ms;
						$fart['url'] = $url;
						$fart['pic'] = Conf::$http_path.$ggk->pic;
						$arts[] = $fart;
						//查找话题
						$m = new Model('weiba_ht');
						$subres = $m->where(array('wid'=>$wid))->order('zm desc')->limit('7')->list_all();
						if(count($subres)>0){
							foreach ($subres as $re){
								$tart = array();
								$tart['tit'] = '#'.$re->keywd.'#';
								$tart['url'] = Conf::$http_path.'wx/weiba-'.$ggk->id.'-'.$re->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
								$fart['pic'] = Conf::$http_path.'res/s.png';
								$arts[] = $tart;
							}
						}else{
							$tart = array();
							$tart['tit'] = '发起新话题';
							$tart['url'] = Conf::$http_path.'wx/weiba-'.$ggk->id.'-new.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
							$fart['pic'] = Conf::$http_path.'res/s.png';
							$arts[] = $tart;
						}
						response_more($arts,$postObj);
						
					}
				}elseif($key[0]=='res_whyk'){
					//会员卡
					$coupon = new Model('micro_member_card');
					$coupon->find($key[1]);
					if($coupon->has_id()){
						$url = Conf::$http_path.'wx/hyk-'.$coupon->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						//查询我的会员卡号
						$red = new Model('micro_member_card_record');
						$red->find(array('cid'=>$coupon->id,'wxid'=>$postObj->FromUserName));
						if($red->has_id()){
							response_one($coupon->name,Conf::$http_path.$coupon->pic,'尊敬的会员卡用户，您的卡号为：'.$red->sn.'。'.$coupon->ms,$url,$postObj);
						}else{
							response_one($coupon->name,Conf::$http_path.$coupon->pic,$coupon->ms,$url,$postObj);
						}
						
						
					}
				}elseif($key[0]=='res_wdy'){
					//微调研
					$ggk = new Model('micro_survey');
					$ggk->find($key[1]);
					if($ggk->has_id()){
						$url = Conf::$http_path.'wx/wdy-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
						
					}
				}elseif($key[0]=='res_wtp'){
					//微投票
					$ggk = new Model('micro_vote');
					$ggk->find($key[1]);
					if($ggk->has_id()){
						$url = Conf::$http_path.'wx/wtp-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
						
					}
					  }elseif($key[0]=="res_wktv"){
					  //微KTV
	$booking = new Model('micro_ktv_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'ktv/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wjd"){
					  //微酒店
	$booking = new Model('micro_hotel');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'wjd/index-'.$booking->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->tit,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wfc"){
					  //微房产
	$booking = new Model('micro_estate_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'wfc/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wmr"){
					  //微美容
	$booking = new Model('micro_meirong_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'wmr/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wwy"){
					  //微物业
	$booking = new Model('micro_wuye_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'wwy/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}	 
	                 }elseif($key[0]=="res_qiye"){
					  //盛世源码版盛世源码业
	$booking = new Model('micro_qiye_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'qiye/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
		             }elseif($key[0]=="res_hyknew"){
	$booking = new Model('weiqiwx_reply_info');
	$booking->find(array('token'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiqiwx/index.php?g=Wap&m=Card&a=index&wecha_id='.$postObj->FromUserName.'&token='.$wid;
		response_one($booking->title,Conf::$http_path.$booking->picurl,$booking->info,$url,$postObj);
		
	}
					}elseif($key[0]=="res_yiliao"){
					  //盛世源码版微医疗
	$booking = new Model('micro_yiliao_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'yiliao/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wzw"){
					  //微政务
	$booking = new Model('micro_zhengwu_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'wzw/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wsp"){
					  //微食品
	$booking = new Model('micro_shipin_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'shipin/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wly"){
					  //微旅游
	$booking = new Model('micro_lvyou_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'wlvy/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wjs"){
					  //微健身
	$booking = new Model('micro_jianshen_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'wjs/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wjy"){
					  //微教育
	$booking = new Model('micro_jiaoyu_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'jiaoyu/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wjb"){
					  //微酒吧
	$booking = new Model('micro_jiuba_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'jiuba/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_whq"){
					  //微婚庆
	$booking = new Model('micro_hunqing_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'hunqing/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wzx"){
					  //微装修
	$booking = new Model('micro_zhuangxiu_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'zhuangxiu/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_whd"){
					  //微花店
	$booking = new Model('micro_huadian_set');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'huadian/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wqc"){
					  //微汽车
	$booking = new Model('micro_car_keyword');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiweb/'.$wid.'/'.$booking->xwid.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wsc"){
					  //微商城
	$booking = new Model('weiqi_shop_keyword');
	$booking->find(array('wid'=>$wid));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiqiwx/index.php?g=Wap&m=Product&a=index&wecha_id='.$postObj->FromUserName.'&wid='.$wid.'&token='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		
	}
					  }elseif($key[0]=="res_wcy"){
					  //微餐饮
					$ggk = new Model('micro_canyin_keyword');
					$ggk->find(array('wid'=>$wid));
					if($ggk->has_id()){
						$url = Conf::$http_path.'weiqiwx/index.php?g=Wap&m=Dining&a=index&wecha_id='.$postObj->FromUserName.'&wid='.$wid.'&token='.$wid;
						response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
					
	}
				}elseif($key[0]=='res_wyyn'){
					//新版预约
					$zxyd = new Model('newyy');
					$zxyd->find($key[1]);
					if($zxyd->has_id()){
						$url = Conf::$http_path.'yuyue/yy-'.$zxyd->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
						response_one($zxyd->tit,Conf::$http_path.$zxyd->pic,$zxyd->ms,$url,$postObj);
						
  }
		        }elseif($key[0]=="res_wnbd"){
					  //零--度-万--能--表--单
	$booking = new Model('weiqiwx_selfform');
	$booking->find($key[1]);
	if($booking->has_id()){
		$url = Conf::$http_path.'weiqiwx/index.php?g=Wap&m=Selfform&a=index&wecha_id='.$postObj->FromUserName.'&id='.$booking->id.'&token='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->logourl,'',$url,$postObj);
		
	}
				}
				////////////////////////////-----------------------------
			}
			//菜单待续		
		}elseif($type=='text'){
			$data_tj = new Model('data_statistics');
			$data_tj->wid = $wid;
			$data_tj->type= 2;
			$data_tj->ctime= DB::raw('NOW()');
			$data_tj->save();
			if($keyword == '盛世源码'){
				response_text('升级成功,感谢您使用盛世源码微信营销系统，如果您不是在我们网站购买，请立即申请退款',$postObj);
				//记住账户的uuid
				$pub = new Model('pubs');
				$pub->update(array('id'=>Request::get('appid')),array('uuid'=>$toUsername));
			}else{
				check_and_replay($keyword,$postObj);
			}
		}
	}
	die();
}else{
	$wechatObj = new wechatCallbackapiTest();	
	if($wechatObj->valid()){//更新验证规则
		$pub = new Model('pubs');
		$pub->update(array('id'=>Request::get('appid')),array('http'=>'','token'=>'','isval'=>'1'));
	}
	die();
}






//智能回复
function check_and_replay($keyword,$postObj){
	global $wid;
	//匹配各项活动
	//刷墙模式
	$booking = new Model('micro_wall_config');
	$booking->find(array('wid'=>$wid));
	$d = new Model('micro_wall_user_name');
	$d->find(array('wid'=>$wid,'wxid'=>$postObj->FromUserName));
	if($d->sqmode){
	if($keyword=="q"){
		$d->sqmode=0;
		$d->save();
	 		$res = "已经退出刷墙模式！";
			response_text($res,$postObj);
			return;
	}
	if($d->mode == 1){
	$d->name = $keyword;
	$d->mode = 2;
	$d->save();
	 		$res = "尊敬的“".$d->name."”同学~您的昵称设置成功~~~~但是无图无真相~回复一张照片设置您的头像吧！";
			response_text($res,$postObj);
			return;
	}elseif($d->mode == 2){
	 		$res = "无图无真相~回复一张照片设置您的头像吧！";
			response_text($res,$postObj);
			return;
	}else{
	$m = new Model('micro_wall_content');
	$m->wid=$wid;
	$m->wxid=$postObj->FromUserName;
	$m->content=$keyword;
	$m->check=$booking->need_check?0:1;
	$m->time=DB::raw('NOW()');
	$m->save();
	$key = str_replace($booking->tpgjz,"",$keyword);
	$s = new Model('micro_wall_vote');
	$s->find(array('wid'=>$wid,'id'=>$key));
	$s->count++;
	$s->save();
			response_text(trim($booking->res_word)."（回复小写'q'退出刷墙模式）",$postObj);
			return;
	}
	}
	//微信墙

	$d = new Model('micro_wall_user_name');
	$d->find(array('wid'=>$wid,'wxid'=>$postObj->FromUserName));
	if($booking->has_id() && substr($keyword,0,6)=='我叫'){
		$d->name = substr($keyword,6);
		$d->wid=$wid;
		$d->wxid=trim($postObj->FromUserName);
		$d->save();
		$res = "昵称设置成功!";
			response_text($res,$postObj);
			return;
	}
	if($booking->has_id()){
	if($keyword==$booking->sqgjz)
	{
		$d->wid=$wid;
		$d->wxid=trim($postObj->FromUserName);
	$d->sqmode = 1;
	 if(empty($d->name))
	 {
		$d->mode = 1;
	 	$res = "成功进入刷墙模式~回复你的名字设置上墙昵称吧~！";
	}else{
		$res = "成功进入刷墙模式~畅所欲言吧~！";
	}
			$d->save();
			response_text($res,$postObj);
			return;
	}}
	//优惠券
	$coupon = new Model('coupon');
	$coupon->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($coupon->has_id()){
		$url = Conf::$http_path.'wx/yhq-'.$coupon->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($coupon->name,Conf::$http_path.$coupon->pic,$coupon->ms,$url,$postObj);
		return;
	}
	//刮刮卡
	$ggk = new Model('ggk');
	$ggk->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($ggk->has_id()){
		$url = Conf::$http_path.'wx/ggk-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
		return;
	}
	
	//幸运大转盘
	$ggk = new Model('xydzp');
	$ggk->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($ggk->has_id()){
		$url = Conf::$http_path.'wx/xydzp-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
		return;
	}
	
	//幸运机
	$ggk = new Model('xyj');
	$ggk->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($ggk->has_id()){
		$url = Conf::$http_path.'wx/xyj-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
		return;
	}
	
	//微团购
	$ggk = new Model('micro_group_buy');
	$ggk->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($ggk->has_id()){
		$url = Conf::$http_path.'wx/wtg-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
		return;
	}

	
	//一站到底
	$ggk = new Model('yzdd');
	$ggk->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($ggk->has_id()){
		$url = Conf::$http_path.'wx/yzdd-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
		return;
	}
	
	//微吧
	$ggk = new Model('weiba');
	$ggk->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($ggk->has_id()){
		$arts = array();
		$fart = array();
		$url = Conf::$http_path.'wx/weiba-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		$fart['tit'] = $ggk->ms;
		$fart['url'] = $url;
		$fart['pic'] = Conf::$http_path.$ggk->pic;
		$arts[] = $fart;
		//查找话题
		$m = new Model('weiba_ht');
		$subres = $m->where(array('wid'=>$wid))->order('zm desc')->limit('7')->list_all();
		if(count($subres)>0){
			foreach ($subres as $re){
				$tart = array();
				$tart['tit'] = '#'.$re->keywd.'#';
				$tart['url'] = Conf::$http_path.'wx/weiba-'.$ggk->id.'-'.$re->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
				$fart['pic'] = Conf::$http_path.'res/s.png';
				$arts[] = $tart;
			}			
		}else{
			$tart = array();
			$tart['tit'] = '发起新话题';
			$tart['url'] = Conf::$http_path.'wx/weiba-'.$ggk->id.'-new.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
			$fart['pic'] = Conf::$http_path.'res/s.png';
			$arts[] = $tart;
		}
		response_more($arts,$postObj);
		return;
	}
	
	//会员卡	
	$coupon = new Model('micro_member_card');
	$coupon->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($coupon->has_id()){
		$url = Conf::$http_path.'wx/hyk-'.$coupon->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;		
		//查询我的会员卡号
		$red = new Model('micro_member_card_record');
		$red->find(array('cid'=>$coupon->id,'wxid'=>$postObj->FromUserName));
		if($red->has_id()){
			response_one($coupon->name,Conf::$http_path.$coupon->pic,'尊敬的会员卡用户，您的卡号为：'.$red->sn.'。'.$coupon->ms,$url,$postObj);
		}else{
			response_one($coupon->name,Conf::$http_path.$coupon->pic,$coupon->ms,$url,$postObj);
		}
		return;
	}
	
	//微调研
	$ggk = new Model('micro_survey');
	$ggk->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($ggk->has_id()){
		$url = Conf::$http_path.'wx/wdy-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
		return;
	}
	//微投票
	$ggk = new Model('micro_vote');
	$ggk->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($ggk->has_id()){
		$url = Conf::$http_path.'wx/wtp-'.$ggk->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($ggk->name,Conf::$http_path.$ggk->pic,$ggk->ms,$url,$postObj);
		return;
	}
	//在线预订
	$booking = new Model('online_booking');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wx/onlineBooking-'.$booking->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		return;
	}
	
	//微相册
	$booking = new Model('micro_photo_list');
	$booking->find(array('wid'=>$wid,'keyword'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wx/wxclist-'.$booking->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->artpic,$booking->ms,$url,$postObj);
		return;
	}
	//微房产
	$booking = new Model('micro_estate_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wfc/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微食品
	$booking = new Model('micro_shipin_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'shipin/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//零-度-万-能-表-单
	$booking = new Model('weiqiwx_selfform');
	$booking->find(array('token'=>$wid,'keyword'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiqiwx/index.php?g=Wap&m=Selfform&a=index&wecha_id='.$postObj->FromUserName.'&id='.$booking->id.'&token='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->logourl,'',$url,$postObj);
		
	}
	//微旅游
	$booking = new Model('micro_lvyou_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wlvy/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微健身
	$booking = new Model('micro_jianshen_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wjs/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微美容
	$booking = new Model('micro_meirong_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wmr/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微政务
	$booking = new Model('micro_zhengwu_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wzw/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微点菜
	$booking = new Model('micro_diancai_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'dc/index-'.$wid.'.html?wxid='.$postObj->FromUserName;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微物业
	$booking = new Model('micro_wuye_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wwy/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微ktv
	$booking = new Model('micro_ktv_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'ktv/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微教育
	$booking = new Model('micro_jiaoyu_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'jiaoyu/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微酒吧
	$booking = new Model('micro_jiuba_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'jiuba/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微花店
	$booking = new Model('micro_huadian_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'huadian/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微婚庆
	$booking = new Model('micro_hunqing_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'hunqing/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微装修
	$booking = new Model('micro_zhuangxiu_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'zhuangxiu/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//盛世源码版微医疗
	$booking = new Model('micro_yiliao_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'yiliao/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//盛世源码版盛世源码业
	$booking = new Model('micro_qiye_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'qiye/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微汽修
	$booking = new Model('micro_qixiu_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'qixiu/index.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微餐饮
	$booking = new Model('micro_canyin_keyword');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiqiwx/index.php?g=Wap&m=Dining&a=index&wecha_id='.$postObj->FromUserName.'&wid='.$wid.'&token='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
    //微会员卡0DU新版
	$booking = new Model('weiqiwx_reply_info');
	$booking->find(array('token'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiqiwx/index.php?g=Wap&m=Card&a=index&wecha_id='.$postObj->FromUserName.'&token='.$wid;
		response_one($booking->title,Conf::$http_path.$booking->picurl,$booking->info,$url,$postObj);
		return;
	}
	//微商城
	$booking = new Model('weiqi_shop_keyword');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiqiwx/index.php?g=Wap&m=Product&a=index&wecha_id='.$postObj->FromUserName.'&wid='.$wid.'&token='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微无线
	$booking = new Model('weiqi_wifi_keyword');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
			$fromUsername = trim($postObj->FromUserName);
			$url = "http://service.rippletek.com/Portal/Wx/wxFunLogin?appId=".trim($booking->appId)."&appKey=".trim($booking->appKey)."&nodeId=".trim($booking->nodeId)."&openId=".trim($fromUsername);
  			$a = file_get_contents($url);
				$b = json_decode($a,true);
		response_one($booking->name,Conf::$http_path.$booking->pic,"本机上网点击开启无线.\r\n如其他机器使用请在设备登陆界面输入验证码：".$b['token'],$b['url'],$postObj);
		return;
	}
	//微喜帖
	$booking = new Model('micro_xitie_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wx/wXiTie-'.$booking->id.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->art_pic,'',$url,$postObj);
		return;
	}
	//微酒店
	$booking = new Model('micro_hotel');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wjd/index-'.$booking->id.'.html?wid='.$wid.'&wxid='.$postObj->FromUserName;
		response_one($booking->tit,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微留言
	$booking = new Model('liuyan_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wly/ly.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->tit,Conf::$http_path.$booking->pic,'',$url,$postObj);
		return;
	}
	//微汽车
	$booking = new Model('micro_car_keyword');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		
		$url = Conf::$http_path.'weiweb/'.$wid.'/'.$booking->xwid.'.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		return;
	}
	//车主关怀
	$booking = new Model('micro_car_guanhuai');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wqc/guanhuai.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		return;
	}
	//预约试驾
$booking = new Model('micro_car_yysj');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wqc/yysj.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->tit,Conf::$http_path.$booking->pic,$booking->des,$url,$postObj);
		return;
	}
	//预约保养
	$booking = new Model('micro_car_yyby');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wqc/yyby.html?wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->tit,Conf::$http_path.$booking->pic,$booking->des,$url,$postObj);
		return;
	}
	//官网

	$booking = new Model('wwz_keyword');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiweb/'.$wid."/?wxid=".$postObj->FromUserName.'&wecha_id='.$postObj->FromUserName;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		return;
	}
	//贺卡
	$booking = new Model('z01_hk');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'hk/hk.html?wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		return;
	}
	//贺卡
	$booking = new Model('z01_hkdq');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'heka/index.html?wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		return;
	}
	//360
	$booking = new Model('360_full_view');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'qj/quanjing-'.$booking->id.'.html?wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		return;
	}
	//音乐盒
	$booking = new Model('micro_music_keyword');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'box/box.html?wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		return;
	}
	//新版预约
	$booking = new Model('newyy');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'yuyue/yy-'.$booking->id.'.html?wid='.$wid."&wxid=".$postObj->FromUserName;
		response_one($booking->tit,Conf::$http_path.$booking->pic,$booking->ms,$url,$postObj);
		return;
	}
	//医疗
	$booking = new Model('weiqi_yiliao_set');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'wyl/guahao.html?wid='.$wid."&wxid=".$postObj->FromUserName;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->desc,$url,$postObj);
		return;
	}
	//摇一摇
	$cs = new Model('weiqi_yyy_keyword');
	$cs->find(array('wid'=>$wid));
	if(preg_match("|^".$cs->gjz."[0-9]{11}$|",$keyword,$re)){
		preg_match("|[0-9]{11}$|",$keyword,$re);
		$url = Conf::$http_path."/weiqiwx/index.php?g=Wap&m=Shakedo&a=index&token=".$wid."&phone=".$re[0]."&wecha_id=".$postObj->FromUserName;
		response_one($cs->name,Conf::$http_path.$cs->pic,$cs->ms,$url,$postObj);
		return;
	}
	//第三方接口
	$booking = new Model('weiqi_api_set');
	$booking->find(array('wid'=>$wid,'name'=>$keyword));
	if($booking->has_id()){
	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	$url = trim($booking->ms);
$headers = array("Content-Type: text/xml; charset=utf-8");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postStr);
$output = curl_exec($ch);
curl_close($ch);
echo $output;
		return;
	}
	//微动态
	$booking = new Model('weiqi_dongtai');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiqidt/dongtai.html?&wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->jianjie,$url,$postObj);
		return;
	}
	//微客服
	$booking = new Model('weiqi_kefu');
	$booking->find(array('wid'=>$wid,'gjz'=>$keyword));
	if($booking->has_id()){
		$url = Conf::$http_path.'weiqikf/kefu.html?&wxid='.$postObj->FromUserName.'&wid='.$wid;
		response_one($booking->name,Conf::$http_path.$booking->pic,$booking->jianjie,$url,$postObj);
		return;
	}
	//匹配关键词
	$key_word = new Model('key_word');
	$kkres = $key_word->where(array('pubsId'=>$wid))->list_all();
	foreach ($kkres as $kk){
		$kkarr = $kk->keyWord.'';
		$kkarr = str_replace('，', ',', $kkarr);
		$kkarr = explode(',', $kkarr);
		if(in_array($keyword, $kkarr)){
			check_and_replay_keyword($kk,$postObj);
			return;
		}
	}
	
	foreach ($kkres as $kk){
		$kkarr = $kk->keyWord.'';
		$keytype = $kk->matchingType;
		if($keytype=='1'){
			$kkarr = str_replace('，', ',', $kkarr);
			$kkarr = explode(',', $kkarr);
			foreach ($kkarr as $tkw){
				if(strpos($keyword, $tkw)!==false){
					check_and_replay_keyword($kk,$postObj);
					return;
				}
				if(strpos($tkw, $keyword)!==false){
					check_and_replay_keyword($kk,$postObj);
					return;
				}
			}
		}

	}
	/**
	$key_word->find(array('keyWord@~'=>$keyword,'pubsId'=>$wid));
	if($key_word->has_id()){
		check_and_replay_keyword($key_word,$postObj);
		return;
	}
	*/
	//匹配智能客服
	//智能客服(调教)
	$myans = new Model('my_answer');
	$myans->find(array('question'=>$keyword,'wid'=>$wid));
	if($myans->has_id()){
		response_text($myans->answer,$postObj);
		return;
	}
	/**
	$myans->find(array('question@~'=>$keyword,'wid'=>$wid));
	if($myans->has_id()){
		response_text($myans->answer,$postObj);
		return;
	}
	*/
	$cs = new Model('customer_service');
	$cs->find(array('wid'=>$wid));
	$nc = trim($cs->name);
	$nc = $nc==''?'小宝':$nc;
	if($cs->tianqi=='1' && strpos($keyword,'天气')!==false){
		//天气
		$keyword = str_replace('天气', '', $keyword);
		$keyword = str_replace('市', '', $keyword);
		if(!empty($keyword)){
		$f['name'] = $keyword;
$fx = array("0" => "无持续风向",
"1" => "东北风",
"2" => "东风",
"3" => "东南风",
"4" => "南风",
"5" => "西南风",
"6" => "西风",
"7" => "西北风",
"8" => "北风",
"9" => "旋转风");

$fl = array("0" => "微风<10m/h",
"1" => "3-4级10~17m/h",
"2" => "4-5级17~25m/h",
"3" => "5-6级25~34m/h",
"4" => "6-7级34~43m/h",
"5" => "7-8级43~54m/h",
"6" => "8-9级54~65m/h",
"7" => "9-10级65~77m/h",
"8" => "10-11级77~89m/h",
"9" => "11-12级89~102m/h");

$tq = array("00" => "晴",
"01" => "多云",
"02" => "阴",
"03" => "阵雨",
"04" => "雷阵雨",
"05" => "雷阵雨伴有冰雹",
"06" => "雨夹雪",
"07" => "小雨",
"08" => "中雨",
"09" => "大雨",
"10" => "暴雨",
"11" => "大暴雨",
"12" => "特大暴雨",
"13" => "阵雪",
"14" => "小雪",
"15" => "中雪",
"16" => "大雪",
"17" => "暴雪",
"18" => "雾",
"19" => "冻雨",
"20" => "沙尘暴",
"21" => "小到中雨",
"22" => "中到大雨",
"23" => "大到暴雨",
"24" => "暴雨到大暴雨",
"25" => "大暴雨到特大暴雨",
"26" => "小到中雪",
"27" => "中到大雪",
"28" => "大到暴雪",
"29" => "浮尘",
"30" => "扬沙",
"31" => "强沙尘暴",
"53" => "霾",
"99" => "无");

$m = new Model('weiqiwx_weather');

$m->find($f);
$url = "http://mobile.weather.com.cn/data/forecast/".$m->code.".html?_=".time()."000";
$data = tianqi_curl($url);
$json = json_decode($data,true);
$sk = $json["c"]["c9"]." ".$json["c"]["c7"]." ".$json["c"]["c5"]." ".$json["c"]["c3"]."(".$json["c"]["c10"]."级城市 区号：".$json["c"]["c11"]."邮政编码：".$json["c"]["c12"].")";

for($a=0;$a<6;$a++)
{
$b = $json['f']['f1'][$a];
$jt .= "\r\n".date("m-d",time()+$a*60*60*24).":\r\n".$tq[$b['fa']]."转".$tq[$b['fb']]."\r\n气温：".$b['fc']."~".$b['fd']."\r\n风向：".$fx[$b['fe']]."转".$fx[$b['ff']]."\r\n风力：".$fl[$b['fg']]."-".$fl[$b['fh']];

}

$url = "http://mobile.weather.com.cn/data/sk/".$m->code.".html?_=".time()."000";
$data = tianqi_curl($url);
$json = json_decode($data,true);
$sk .= "\r\n实事气温：".$json['sk_info']['temp'].$json['sk_info']['tempF'].$json['sk_info']['wd'].":".$json['sk_info']['ws']."实事湿度：".$json['sk_info']['sd'];
$url = "http://mobile.weather.com.cn/data/zsM/".$m->code.".html?_=".time()."000";
$data = tianqi_curl($url);
$json = json_decode($data,true);
foreach($json['zs'] as $a => $b)
{
$js .= $b['name'].":".$b['hint']."(".$b['des'].")\r\n";

}
$res = $sk.$jt.$js;
			response_text($res,$postObj);
		}
	}
	if($cs->translate=='1' && (strpos($keyword,'@')===0 ||strpos($keyword,'翻译')===0)){
		$keyword = str_replace('翻译', '', $keyword);
		$keyword = str_replace('@', '', $keyword);
		//翻译
		$res = curl_file_get_contents('http://openapi.baidu.com/public/2.0/bmt/translate?client_id=zVlUXsRYTxNQ94Aaz1yKdmaI&from=auto&to=auto&q='.urlencode($keyword));
		if(!empty($res)){
            $fanyijson = json_decode($res,true);
$res = "翻译:".$fanyijson['trans_result']['0']['src']."\r\nfrom:".$fanyijson['from']."  to:".$fanyijson['to']."\r\nresult:\r\n".$fanyijson['trans_result']['0']['dst'];
response_text($res,$postObj);
			return;
		}
	}

	if($cs->cangts=='1' && strpos($keyword,'藏头诗')!==false){
		$res = HttpClient::quickGet('http://api.ajaxsns.com/api.php?key=free&appid=0&msg='.urlencode($keyword),3);
		if(!empty($res)){
			$res = json_decode($res);
			$res = str_replace('{br}', "\n\r", $res->content);
			response_text($res,$postObj);
			return;
		}
	}
	if($cs->chengyu=='1' && strpos($keyword,'成语')!==false){
	$keyword = str_replace('成语', '', $keyword);
$url = "http://api.uihoo.com/chengyu/chengyu.http.php?format=json&key=".urlencode($keyword);
$huochestr = curl_file_get_contents($url);
$a = json_decode($huochestr, true);
$res = "成语：".$a['0']['chengyu']."\r\n拼音：".$a['0']['pinyin']."\r\n典故：". $a['0']['diangu']."\r\n出处：".$a['0']['chuchu'];
response_text($res,$postObj);
return;
}
	if($cs->jiemeng=='1' && strpos($keyword,'梦见')!==false){
	$keyword = str_replace('梦见', '', $keyword);
$url = "http://api.uihoo.com/dream/dream.http.php?format=json&key=".$keyword;
$huochestr = curl_file_get_contents($url);
$re = json_decode($huochestr, true);
$n = count($re);
if($n>20)
{
$n = 20;
}
for($a=1;$a<$n+1;$a++)
{
$res .= $a.".".$re[$a-1]."\r\n";
}
response_text($res,$postObj);
return;
}
	if($cs->shipin=='1' && strpos($keyword,'视频')!==false){
	$keyword = str_replace('视频', '', $keyword);
	$res = array();
  $jd = curl_file_get_contents("https://openapi.youku.com/v2/searches/show/by_keyword.json?client_id=03f8a1bc8f722ca3&keyword=".$keyword);
  $json = json_decode($jd,true);
  if($json['total'] == 0)
  {
  $jd = curl_file_get_contents("https://openapi.youku.com/v2/searches/video/by_keyword.json?client_id=03f8a1bc8f722ca3&keyword=".$keyword);
  $json = json_decode($jd,true);
      if($json['total'] == 0)
  {
  response_one("抱歉","","抱歉未查询到相关视频!","",$postObj);
    return;
  }
    if($json['total']>10)
{
$re[0] = 10;
}else{
$re[0] = $json['total'];
}
for($n=0;$n<$re[0];$n++)
{
$res[] = array('tit'=>$json['videos'][$n]['title'],'pic'=>$json['videos'][$n]['thumbnail_v2'],'url'=>$json['videos'][$n]['link'],'ms'=>"");
}  
response_more($res,$postObj);
return;
  }
      if($json['total']>10)
{
$re[0] = 10;
}else{
$re[0] = $json['total'];
}
for($n=0;$n<$re[0];$n++)
{
$res[] = array('tit'=>$json['shows'][$n]['name'].$json['show'][$n]['episode_updated'],'pic'=>$json['shows'][$n]['thumbnail'],'url'=>$json['shows'][$n]['paly_link'],'ms'=>"");
} 
response_more($res,$postObj);
return; 
	}
	if($cs->tushu=='1' &&  strpos($keyword,'图书')!==false){
	$keyword = str_replace('图书', '', $keyword);
$jd = curl_file_get_contents("http://api.douban.com/v2/book/search?q=".$keyword);
$json = json_decode($jd,true);
  if($json['total'] >0)
  {
response_one($json['books'][0]['title'],$json['books'][0]['image'],$json['books'][0]['summary'],$json['books'][0]['alt'],$postObj);
  }else{
  response_one("抱歉","","未找到相关书籍！！","",$postObj);
  }
return;
}
	if($cs->baojia=='1' && strpos($keyword,'报价')!==false){ 
$sea = curl_file_get_contents("http://wap.zol.com.cn/index.php?keyword=".$key."&c=List_List");
$li = explode("<li >",$sea);
$n = 1;
$res = array();
foreach($li as $a => $b)
{
if($n>10)
{
break;
}else{
preg_match("|\/[0-9]{3}\/[0-9]{6}\/[^\"]*|",$b,$re);
if(!empty($re[0]))//  url
{
preg_match("|http:\/\/[^\"]*|",$b,$re1);//$re[0] img
$re3 = preg_replace("|[\s]*\<[^\>]*\>[\s]*|","",$b);
$re4 = explode("周浏览",$re3);
$res[] = array('tit'=>$re4[0],'pic'=>$re1[0],'url'=>"http://wap.zol.com.cn".$re[0],'ms'=>"");
$n++;
}
}
}
response_more($res,$postObj);
return;
	}
	if($cs->xiaohua=='1' && $keyword=='笑话'){
		$res = HttpClient::quickGet("http://api.dingxiaoyu.com/Api/?use=test&key=test&type=joke&msg=&show=txt&encode=utf8&submit=%E6%8F%90%E4%BA%A4",3);
		if(!empty($res)){
			response_text($res,$postObj);
			return;
		}
	}
	
	//彩票
	if($cs->caipiao=='1' && strpos($keyword,'彩票')===0){
		$keyword = str_replace('彩票', '', $keyword);
		$res = HttpClient::quickGet('http://api2.sinaapp.com/search/lottery/?appkey=0020130430&appsecert=fa6095e113cd28fd&reqtype=text&keyword='.urlencode($keyword),3);
		if(!empty($res)){
			$res = json_decode($res);
			$res = str_replace('{br}', "\n\r", $res->text->content);
			response_text($res,$postObj);
			return;
		}
	}
	
	//计算
	if($cs->jisuan=='1' && strpos($keyword,'计算')===0){
		if($keyword !='计算'){
			$keyword = str_replace('计算', '', $keyword);
		}		
		$res = HttpClient::quickGet('http://api.ajaxsns.com/api.php?key=free&appid=0&msg='.urlencode($keyword),3);
		if(!empty($res)){
			$res = json_decode($res);
			$res = str_replace('{br}', "\n\r", $res->content);
			response_text($res,$postObj);
			return;
		}
	}
	
	//股票
	if($cs->gupiao=='1' && strpos($keyword,'股票')===0){
		$keyword = str_replace('股票', '', $keyword);
		$res = HttpClient::quickGet('http://api2.sinaapp.com/search/stock/?appkey=0020130430&appsecert=fa6095e113cd28fd&reqtype=text&keyword='.urlencode($keyword),3);
		if(!empty($res)){
			$res = json_decode($res);
			$res = str_replace('{br}', "\n\r", $res->text->content);
			response_text($res,$postObj);
			return;
		}
	}
	
	//人品
	if($cs->renpin=='1' && strpos($keyword,'人品')===0){
		$keyword = str_replace('人品', '', $keyword);
		$rp = renpin($keyword,$fs);
		response_text('【'.$keyword.'的人品:'.$fs.'】'."\r\n".renpin($keyword)."\n\r人品内容纯属虚构，仅供娱乐之用，切勿当真！",$postObj);
		return;
	}
	//无线
	if($keyword=='无线'){
			$fromUsername = trim($postObj->FromUserName);
  				$a = curl_file_get_contents("http://web.rippletek.com/api/portal/login?appId=8554xDPMCuLTkwVn&appKey=otF9HKubMQHOEKKbgwLyMnb30oXjMbvJ&nodeId=jhms&openId=".$fromUsername);
				$b = json_decode($a,true);
	
		response_text("<a href='".$b['url']."' > 点击开启无线</a>",$postObj);
		return;
	}
	//拨打
	if(strpos($keyword,'拨打')===0){
		$keyword = str_replace('拨打', '', $keyword);
		$hykrecord = new Model('micro_member_card_record');
		$fromUsername = $postObj->FromUserName;
		$a['wid']=$wid;
		$a['wxid']=trim($fromUsername);
		$hykrecord->find($a);
		if($hykrecord->has_id()){
$url = "http://3g.inbai.com/servlet/SignInInterfaceServlet?agentid=null&action=makecall&srcNumber=".$hykrecord->tel."&callNumber=".$keyword;
$a = curl_file_get_contents($url);
$s = "预约成功";
		response_text($s,$postObj);
		return;
		}
	}
	//火车
	if($cs->huoche=='1' && strpos($keyword,'火车')===0){
		$keyword = str_replace('火车', '', $keyword);
		$keyword = explode('到', $keyword);
		if(count($keyword)!=2){
			response_text('输入有误',$postObj);
			return;
		}
		$res = HttpClient::quickGet("http://www.twototwo.cn/train/Service.aspx?format=json&action=QueryTrainScheduleByTwoStation&key=5da453b2-b154-4ef1-8f36-806ee58580f6&startStation=" . urlencode($keyword[0]) . "&arriveStation=" . urlencode($keyword[1]) . "&startDate=" . date('Y') . "&ignoreStartDate=0&like=1&more=0",3);
		$str = '';
		if ($res) {
			try{
				$data = json_decode($res);
				$main = $data->Response->Main->Item;
				if (count($main) > 10) {
					$conunt = 10;
				} else {
					$conunt = count($main);
				}
				for ($i = 0; $i < $conunt; $i++) {
					$str .= "\n【车次】" . $main[$i]->CheCiMingCheng . "\n【类型】" . $main[$i]->CheXingMingCheng . "\n【发车时间】:　" . $main[$i]->FaShi . "\n【耗时】" . $main[$i]->LiShi . ' 小时';
					$str .= "\n----------------------";
				}
			}catch(Exception $e){
				response_text('输入有误',$postObj);
				return;
			}
		} else {
			$str = '没有找到 ' . $keyword[0] . ' 至 ' . $keyword[1] . ' 的列车';
		}
		response_text($str,$postObj);
		return;
	}
	
	
	
	//快递
	if($cs->kuaidi=='1' && strpos($keyword,'快递')===0){
		$keyword = str_replace('快递', '', $keyword);
		$keyword = explode('@', $keyword);
		$res = HttpClient::quickGet('http://www.weinxinma.com/api/index.php?m=Express&a=index&name='.urlencode($keyword[0]).'&number='.$keyword[1],3);
		if(!empty($res)){
			$res = str_replace('{br}', "\n\r", $res);
			response_text($res,$postObj);
			return;
		}
	}
	//无线
	if($keyword=='无线'){
			$fromUsername = trim($postObj->FromUserName);
  				$a = curl_file_get_contents("http://web.rippletek.com/api/portal/login?appId=8554xDPMCuLTkwVn&appKey=otF9HKubMQHOEKKbgwLyMnb30oXjMbvJ&nodeId=jhms&openId=".$fromUsername);
				$b = json_decode($a,true);
	
		response_text("<a href='".$b['url']."' > 点击开启无线</a>",$postObj);
		return;
	}
	//百科
	if($cs->baike=='1' && strpos($keyword,'百科')===0){
		$keyword = mb_substr($keyword, 2);
		$name_gbk         = iconv('utf-8', 'gbk', $keyword);
		$encode           = urlencode($name_gbk);
		$url              = 'http://baike.baidu.com/list-php/dispose/searchword.php?word=' . $encode . '&pic=1';
		$get_contents_gbk     = HttpClient::quickGet($url);
		preg_match("/URL=(\S+)'>/s", $get_contents_gbk, $out);
		$real_link     = 'http://baike.baidu.com' . $out[1];
		$get_contents2 = HttpClient::quickGet($real_link);
		preg_match('#"Description"\scontent="(.+?)"\s\/\>#is', $get_contents2, $matchresult);
		if (isset($matchresult[1]) && $matchresult[1] != "") {
			response_text(htmlspecialchars_decode($matchresult[1]),$postObj);
		} else {
			response_text("抱歉，没有找到与“" . $keyword . "”相关的百科结果。",$postObj);
		}
		return;
	}
	//新闻
	if($cs->xinwen=='1' && $keyword=='新闻'){
		$rss = RSS::Parse('http://news.qq.com/newsgn/rss_newsgn.xml');
		$num = 0;
		$res = array();
		foreach ($rss['items'] as $it){
			if($num>8){
				break;
			}			
			$res[] = array('tit'=>$it['title'],'pic'=>Conf::$http_path.'res/s.png','url'=>$it['link'],'ms'=>$it['description']);
			$num++;
		}
		response_more($res,$postObj);
		return;
	}
	//huangli
	if($cs->huangli=='1' && ($keyword=='日历' || $keyword=='黄历' || $keyword=='万年历' || $keyword=='几号')){
		response_text(Lunar::today(),$postObj);
		return;
	}
	if($cs->autoans=='1'){	
		//系统客服回答
		$keyword = str_replace('翻译', '译', $keyword);
		$keyword = str_replace('天气', '天', $keyword);
		$keyword = str_replace('藏头诗', '诗', $keyword);
		$keyword = str_replace('笑话', '笑', $keyword);
		$keyword = str_replace('计算', '算', $keyword);
		$res = HttpClient::quickGet('http://api.ajaxsns.com/api.php?key=free&appid=0&msg='.urlencode($keyword),3);
		if(!empty($res)){
			$res = json_decode($res);
			$res = str_replace('{br}', "\n\r", $res->content);
			$res = str_replace('菲菲', $nc, $res);
			$res = str_replace('&quot;', '"', $res);
			response_text($res,$postObj);
			return;
		}
	}
	
	//匹配"*"关键词
	$key_word = new Model('key_word');
	$key_word->find(array('keyWord'=>'*','pubsId'=>$wid));
	if($key_word->has_id()){
		check_and_replay_keyword($key_word,$postObj);
		return;
	}
	//都没有匹配上则不回答
	
}
function check_and_replay_keyword($key_word,$postObj){
	global $wid;
	$rtyp = $key_word->replyType;
	if($rtyp=='1'){
		response_text($key_word->replyContent,$postObj);
	}else{
		$res = new Model('res');
		$res->find($key_word->resId);
		response_arts($res,$postObj);
	}
}

//回复文本
function response_text($txt,$postObj){
	$fromUsername = $postObj->FromUserName;
	$toUsername = $postObj->ToUserName;
	$textTpl = "<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<Content><![CDATA[%s]]></Content>
	<FuncFlag>0</FuncFlag>
	</xml>";
	$res = sprintf($textTpl, $fromUsername, $toUsername, time(), "text", trim($txt));
	//Log::error($res);
	echo $res;
}

function response_arts($res,$postObj){
	$r = json_decode($res->con);
	if(is_array($r)){
		response_morearts($r,$res->id,$postObj);
	}else{
		response_oneart($r,$res->id,$postObj);
	}
}
//回复单图文
function response_oneart($r,$rid,$postObj){
	global $wid;
	$fromUsername = $postObj->FromUserName;
	$toUsername = $postObj->ToUserName;
	$textTpl = "<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<ArticleCount>%s</ArticleCount>
	<Articles>
	ITEM
	</Articles>
	</xml>";
	$resstr =  sprintf($textTpl, $fromUsername, $toUsername, time(), "news", 1);				
	$subitem = "<item>
	<Title><![CDATA[%s]]></Title>
	<Description><![CDATA[%s]]></Description>
	<PicUrl><![CDATA[%s]]></PicUrl>
	<Url><![CDATA[%s]]></Url>
	</item>";
    if (isset($r->iswifi) && $r->iswifi) {
        $r->ourl = loginUrl($wid);
    }

     $addpos = '?';
    if (strpos($r->ourl, '?') !== false) {
        $addpos = '&';
    }
    
    $r->ourl = $r->ourl . $addpos . 'wxid=' . $fromUsername . '&wid=' . $wid . '&rid=' . $rid;
    $r->ourl = $r->ourl . '#mp.weixin.qq.com';
    $item = sprintf($subitem, $r->tit, $r->des, Conf::$http_path . $r->pic, $r->ourl);
    $resstr = str_replace('ITEM', $item, $resstr);
    echo $resstr;
}
//回复多图文
function response_morearts($res,$rid,$postObj){
	global $wid;
	$fromUsername = $postObj->FromUserName;
	$toUsername = $postObj->ToUserName;
	$textTpl = "<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<ArticleCount>%s</ArticleCount>
	<Articles>
	ITEM
	</Articles>
	</xml>";
	$resstr =  sprintf($textTpl, $fromUsername, $toUsername, time(), "news", count($res));
	$item = '';
	$subitem = "<item>
	<Title><![CDATA[%s]]></Title>
	<PicUrl><![CDATA[%s]]></PicUrl>
	<Url><![CDATA[%s]]></Url>
	</item>";
	foreach ($res as $r){
		$addpos = '?';
		if(strpos($r->ourl, '?')!==false){
			$addpos = '&';
		}
		$r->ourl = $r->ourl.$addpos.'wxid='.$fromUsername.'&wid='.$wid.'&rid='.$rid;
		$r->ourl = $r->ourl.'#mp.weixin.qq.com';
		$item.=sprintf($subitem, $r->tit, Conf::$http_path.$r->pic, $r->ourl);
	}
	$resstr = str_replace('ITEM', $item, $resstr);
	echo $resstr;
}



//回复单图文内容
function response_one($tit,$pic,$des,$url,$postObj){
	global $wid;
	$fromUsername = $postObj->FromUserName;
	$toUsername = $postObj->ToUserName;
	$textTpl = "<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<ArticleCount>%s</ArticleCount>
	<Articles>
	ITEM
	</Articles>
	</xml>";
	$resstr =  sprintf($textTpl, $fromUsername, $toUsername, time(), "news", 1);
	$subitem = "<item>
	<Title><![CDATA[%s]]></Title>
	<Description><![CDATA[%s]]></Description>
	<PicUrl><![CDATA[%s]]></PicUrl>
	<Url><![CDATA[%s]]></Url>
	</item>";
	$url = $url.'#mp.weixin.qq.com';
	$item=sprintf($subitem, $tit, $des, $pic, $url);
	$resstr = str_replace('ITEM', $item, $resstr);
	echo $resstr;
}


//回复多图文
function response_more($res,$postObj){
	global $wid;
	$fromUsername = $postObj->FromUserName;
	$toUsername = $postObj->ToUserName;
	$textTpl = "<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<ArticleCount>%s</ArticleCount>
	<Articles>
	ITEM
	</Articles>
	</xml>";
	$resstr =  sprintf($textTpl, $fromUsername, $toUsername, time(), "news", count($res));
	$item = '';
	$subitem = "<item>
	<Title><![CDATA[%s]]></Title>
	<PicUrl><![CDATA[%s]]></PicUrl>
	<Url><![CDATA[%s]]></Url>
	</item>";
	foreach ($res as $r){
		$r['url'] = $r['url'].'#mp.weixin.qq.com';
		$item.=sprintf($subitem, $r['tit'],$r['pic'], $r['url']);
	}
	$resstr = str_replace('ITEM', $item, $resstr);
	echo $resstr;
}
 function curl_file_get_contents($durl){
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $durl);
   curl_setopt($ch, CURLOPT_TIMEOUT, 30);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
   curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $r = curl_exec($ch);
    curl_close($ch);
   return $r;
 }
function tianqi_curl($durl){
   $ch = curl_init($durl);
    $header = array("Referer:".$durl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $data = curl_exec($ch);
	curl_close($ch);
   return $data;
 }
class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	return true;
        }
        return false;
    }
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}



/**
 *  @desc 根据两点间的经纬度计算距离
 *  @param float $lat 纬度值
 *  @param float $lng 经度值
 */
 
function get_distance_by_lng_lat($lng1,$lat1,$lng2,$lat2)
{
	$earthRadius = 6367000; //approximate radius of earth in meters

	/*
	 Convert these degrees to radians
	to work with the formula
	*/

	$lat1 = ($lat1 * pi() ) / 180;
	$lng1 = ($lng1 * pi() ) / 180;

	$lat2 = ($lat2 * pi() ) / 180;
	$lng2 = ($lng2 * pi() ) / 180;

	/*
	 Using the
	Haversine formula

	http://en.wikipedia.org/wiki/Haversine_formula

	calculate the distance
	*/

	$calcLongitude = $lng2 - $lng1;
	$calcLatitude = $lat2 - $lat1;
	$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
	$calculatedDistance = $earthRadius * $stepTwo;

	return round($calculatedDistance);
}
