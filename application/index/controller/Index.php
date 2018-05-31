<?php
namespace app\index\controller;
use \app\base\controller\Base as ControllerBase;
use \app\base\controller\Auth;

use think\Session;
class Index extends ControllerBase
{
    public function _initialize()
    {
        parent::_initialize();
        $Auth = new Auth();
        $checkLogin = $Auth->checkLogin();
        if(!$checkLogin){
            $this->redirect('login/login/login');
        }
    }

    public function index()
    {
    	$user_info = Session::get('user_info');
    	$this->assign('name',$user_info['username']);
    	$this->assign('out_url',url('login/login/login'));
    	$this->assign('phone_url',url('qdhk/phone/search'));
    	return $this->fetch();
    }
}
