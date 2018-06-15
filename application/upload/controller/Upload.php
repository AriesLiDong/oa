<?php
namespace app\upload\controller;
use \app\base\controller\Base as ControllerBase;

use think\Session;
class Upload extends ControllerBase
{
    public function up(){
        $path = ROOT_PATH.'public/';
//        print_r(ROOT_PATH  );exit;
        if(is_uploaded_file($_FILES['excel_file']['tmp_name'])){                                            //判断是否是上传文件
            //unlink($_FILES['file1']['tmp_name']);
            move_uploaded_file($_FILES['excel_file']['tmp_name'], $path."{$_FILES['excel_file']['name']}");     //将缓存文件移动到指定位置
        }
    }

    public function get_progress(){
//        print_r(Session::get());
//        print_r($_REQUEST);exit;
    }
}
