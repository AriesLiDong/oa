<?php
namespace app\base\model;

use think\Model;

class User extends Model
{
	protected function _initialize()
	{
		parent::_initialize();
	}

	public function getUserById($id)
	{
		$user = User::get($id);
		return $user;
	}

	public function getUserByNamePwd($name,$pwd)
	{
		$user = User::get(['username'=>$name,'pwd'=>md5($pwd)]);
		return $user;
	}
}