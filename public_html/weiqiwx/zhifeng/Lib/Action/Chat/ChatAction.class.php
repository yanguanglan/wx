<?php
class ChatAction extends Action{
    public function _initialize(){
		define('RES', __ROOT__.'/'.THEME_PATH . 'common');
        define('STATICS', __ROOT__.'/'.TMPL_PATH . 'static');
        $this->assign('action', $this->getActionName());
        if (session('userName') == false){
            $this -> error('您必须登陆后才能操作', U('Login/index'));
        }
    }
}
?>