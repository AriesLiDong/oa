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
    	$flag = Session::get('user_name');
    	return $this->fetch();
    }
}
