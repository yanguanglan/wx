<?php
class BaseAction extends Action
{
    protected function _initialize()
    {
        define('RES', __ROOT__.'/'.THEME_PATH . 'common');
        define('STATICS', __ROOT__.'/'.TMPL_PATH . 'static');
        $this->assign('action', $this->getActionName());
    }
    protected function all_insert($name = '', $back = '/index')
    {
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
            $id = $db->add();
            if ($id) {
                $m_arr = array(
                    'Img',
                    'Text',
                    'Voiceresponse',
                    'Ordering',
                    'Lottery',
                    'Host',
                    'Product',
					'Dining',
                    'Selfform',
		    		'Xitie',
					'Wedding',
					'Diaoyan',
					'Yuyue',
                    'Panorama',
					'Vote',
                    'Estate',
                    'Reservation',
                    'Zuche'
                );
                if (in_array($name, $m_arr)) {
                    $data['pid']     = $id;
                    $data['module']  = $name;
                    $data['token']   = session('token');
                    $data['keyword'] = $_POST['keyword'];
                    M('Keyword')->add($data);
                }
                $this->success('操作成功', U(MODULE_NAME . $back));
            } else {
                $this->error('操作失败', U(MODULE_NAME . $back));
            }
        }
    }
    protected function insert($name = '', $back = '/index')
    {
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
            $id = $db->add();
            if ($id == true) {
                $this->success('操作成功', U(MODULE_NAME . $back));
            } else {
                $this->error('操作失败', U(MODULE_NAME . $back));
            }
        }
    }
    protected function save($name = '', $back = '/index')
    {
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
            $id = $db->save();
            if ($id == true) {
                $this->success('操作成功', U(MODULE_NAME . $back));
            } else {
                $this->error('操作失败', U(MODULE_NAME . $back));
            }
        }
    }
    protected function all_save($name = '', $back = '/index')
    {
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
            $id = $db->save();
            if ($id) {
                $m_arr = array(
                    'Img',
                    'Text',
                    'Voiceresponse',
                    'Ordering',
                    'Lottery',
                    'Host',
                    'Product',
					'Dining',
                    'Selfform',
		    		'Xitie',
					'Wedding',
					'Diaoyan',
					'Yuyue',
                    'Panorama',
					'Vote',
                    'Estate',
                    'Reservation'
                );
                if (in_array($name, $m_arr)) {
                    $data['pid']    = $_POST['id'];
                    $data['module'] = $name;
                    $data['token']  = session('token');
                    $da['keyword']  = $_POST['keyword'];
                    M('Keyword')->where($data)->save($da);
                }
                $this->success('操作成功', U(MODULE_NAME . $back));
            } else {
                $this->error('操作失败', U(MODULE_NAME . $back));
            }
        }
    }
    protected function all_del($id, $name = '', $back = '/index')
    {
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->delete($id)) {
            $this->ajaxReturn('操作成功', U(MODULE_NAME . $back));
        } else {
            $this->ajaxReturn('操作失败', U(MODULE_NAME . $back));
        }
    }
	/**
     * AJAX返回数据标准
     *
     * @param int $status
     * @param string $msg
     * @param mixed $data
     * @param string $dialog
     */
    protected function ajaxReturn($status=1, $msg='', $data='', $dialog='') {
        parent::ajaxReturn(array(
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'dialog' => $dialog,
        ));
    }
	protected function _upload(){
		import("@.ORG.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 3292200;
		//设置上传文件类型
		$upload->allowExts = explode(',','jpg,gif,png,jpeg');
		//设置附件上传目录
		$upload->savePath = './data/';
		//设置需要生成缩略图，仅对图像文件有效
		$upload->thumb = true;
		// 设置引用图片类库包路径
		$upload->imageClassPath = '@.ORG.Image';
		//设置需要生成缩略图的文件后缀
		$upload->thumbPrefix = 'm_';
		//生产2张缩略图
		//设置缩略图最大宽度
		$upload->thumbMaxWidth = '720';
		//设置缩略图最大高度
		$upload->thumbMaxHeight = '400';
		//设置上传文件规则
		$upload->saveRule = uniqid;
		//删除原图
		$upload->thumbRemoveOrigin = true;
		if (!$upload->upload()) {
			//捕获上传异常
			return $upload->getErrorMsg();
		}else{
			//取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
			return $uploadList;
		}
	}
	//发短信
	protected function Send_sms($phone,$body){
		//查询短信配置
		$token = $this->_get('token');
		if (!$token){
			$token= session('token');
		}
		$set=M('sms_set')->where(array('token'=>$token))->find();
		if($set['status']){
			$sms = new Sms("smsbao",$set['account'],$set['password']);
			$re = $sms->sendsms($phone,$body);
			return $re;
		}
	}
	//发邮件函数
	protected function Send_email($Subject,$body,$to_email='',$FromName='管理员'){
		//查询邮件配置
		$token = $this->_get('token');
		if (!$token){
			$token= session('token');
		}
		$set =M('email_set')->where(array('token'=>$token))->find();
		$host =$set['server'];
		$port = $set['port'];
		$emailuser = $set['account'];
		$emailpassword = $set['password'];
		if($to_email==''){
			$to_email =$set['emails'];
		}
		if($set['status']){
			$mail = new PHPMailer();
			$mail->IsSMTP();
			// telling the class to use SMTP
			$mail->Host = $host;
			// SMTP server
			$mail->SMTPDebug = '0';
			// enables SMTP debug information (for testing)
			// 1 = errors and messages
			// 2 = messages only
			$mail->SMTPAuth = true;
			$mail->CharSet  = "UTF-8"; //字符集 
			$mail->Encoding = "base64"; //编码方式 
			// enable SMTP authentication
			$mail->Port = $port;
			// set the SMTP port for the GMAIL server
			$mail->Username = $emailuser;
			// SMTP account username
			$mail->Password = $emailpassword;
			// SMTP account password
			$mail->From = $emailuser;      // 发件人邮箱    
			$mail->FromName =  "=?utf-8?B?" . base64_encode($FromName) . "?=";  // 发件人    
			//$mail->AddAddress($to_email, '商户');
			//$mail->AddReplyTo($emailuser);
			$mail->Subject = "=?UTF-8?B?" . base64_encode($Subject) . "?=";
			$mail->IsHTML(true);  // send as HTML    
			// optional, comment out and test
			$mail->MsgHTML($body);
			$to_email_arr=array_filter(explode(",",$to_email));
			foreach($to_email_arr as $k => $v){ 
				$mail->AddAddress($v); 
			} 
			return($mail->Send());
		}
	}
}
?>