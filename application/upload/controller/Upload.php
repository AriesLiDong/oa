<?php
namespace app\upload\controller;
use \app\base\controller\Base as ControllerBase;

use think\Session;
class Upload extends ControllerBase
{
    public function up(){
        set_time_limit(0);
        $path = ROOT_PATH.'public/upload/';
        if(is_uploaded_file($_FILES['excel_file']['tmp_name'])){                                            //判断是否是上传文件
            //unlink($_FILES['file1']['tmp_name']);
            move_uploaded_file($_FILES['excel_file']['tmp_name'], $path."{$_FILES['excel_file']['name']}");     //将缓存文件移动到指定位置
        }
    }

    public function get_progress(){
        session_start();
        //ini_get()获取php.ini中环境变量的值
        $i = ini_get('session.upload_progress.name');
         //ajax中我们使用的是get方法，变量名称为ini文件中定义的前缀 拼接 传过来的参数
        $key = ini_get("session.upload_progress.prefix") . $_GET[$i];    
        //判断 SESSION 中是否有上传文件的信息
        if (!empty($_SESSION[$key])) {                                        
            //已上传大小
            $current = $_SESSION[$key]["bytes_processed"];
            //文件总大小
            $total = $_SESSION[$key]["content_length"];
             //向 ajax 返回当前的上传进度百分比。
            echo $current < $total ? ceil($current / $total * 100) : 100;
        }else{
            echo 100;                                                       
        }
    }
}
