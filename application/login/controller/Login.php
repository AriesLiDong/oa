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
		if($this->request->isAjax()){
			$user_model = new UserModel();
			$data = $user_model->getUserById(1)->toArray();
		}else{
			
		}
		
	}
}