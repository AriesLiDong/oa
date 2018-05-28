<?php
namespace app\base\controller;
use think\Controller;
 
class Base extends Controller
{

	static public function showReturnCode($code, $data=[], $msg='')
	{

		$return_code = array(
			'code'=>'500',
			'msg'=>'未定义消息',
			'data'=>$data
		);
	}
}