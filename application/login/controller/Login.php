<?php
namespace app\login\controller;

use app\base\controller\Base as ControllerBase;
use app\base\controller\Auth;
use app\base\model\User as UserModel;
class Login extends ControllerBase
{
	public function login()
	{
		return $this->fetch();
	}

	public function doLogin()
	{
		$loginConfirm = false;
		if($this->request->isAjax()){
			$params = $this->request->post();
			$user_model = new UserModel();
			$data = $user_model->getUserByNamePwd($params['name'],$params['pwd']);
			if($data){
				$loginConfirm = true;
				
			}
		}
		
		if($loginConfirm){
			$user_info = $data->toArray();
			$id = $user_info['id'];
			$auth = new Auth();
			$result = $auth->setLogin($user_info,$id);
			return ['res'=>true,'url'=>url('index/index/index')];
		}else{
			return ['res'=>false,'url'=>false];
		}
		
	}

	public function loginout()
    {
        $auth = new Auth();
        $auth->exitLogin();
        $this->redirect('login');
    }
}