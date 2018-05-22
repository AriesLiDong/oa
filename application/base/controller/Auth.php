<?php
namespace app\base\controller;
use app\base\controller\Base as BaseController;
/**
 * by shenlidong
 * 登录功能
 */
class Auth extends BaseController
{
	protected $login_type;
	protected $user_info;
	protected $uuid;

	/**
	 * 构造函数
	 */
	public function _initalize()
	{
		parent::_initialize();
		$this->login_type = Config::get("login_type") ? Config::get("login_type") : 'session';
		$this->checkLogin();
	}
	/**
	 * 检测是否登录
	 */
	public function checkLogin()
	{
		$check_status = false;
		switch ($this->login_type) {
			case 'session':
				$this->uuid = Session::get('uuid','oa');
				$this->user_info = Session::get('user_info','oa');
				if($this->uuid && $this->user_info){
					$check_status = true;
				}
				break;
			case 'cache':
				break;
			case 'redis':
				break;			
			default:
				break;
		}
		return $check_status;
	}

	/**
	 * 设置登录
	 */
	public function setLogin($user_info, $login_code)
	{
		$set_access = false;
		if($user_info){
			switch ($this->login_type) {
				case 'session':
					Session::set('uuid',$user_info['id'], 'oa');
					Session::set('user_info',$user_info, 'oa');
					if( Session::has('uuid','oa') && Session::has('user_info','oa') )
						$set_access = true;
					break;
				case 'cache':

					break;
				case 'redis':

					break;		
				default:
					break;
			}
		}

		return $set_access;	
	}

	/**
	 * 退出登录
	 */
	public function exitLogin()
	{
		switch ($this->login_type) {
			case 'session':
				Session::delete('uuid','oa');
				Session::delete('user_info','oa');
				break;
			case 'cache':
				break;
			case 'redis':
				break;
			default:
				# code...
				break;
		}
		$this->user_info = null;
		$this->uuid = null;
		return true;
	}
}