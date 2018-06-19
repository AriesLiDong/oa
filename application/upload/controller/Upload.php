<?php
namespace app\upload\controller;
use \app\base\controller\Base as ControllerBase;

use think\Session;
class Upload extends ControllerBase
{
    public function up(){
//        set_time_limit(0);
        $path = ROOT_PATH.'public/upload/';
        $type = input('type');//上传功能类型
        $step = input('step');//上传步数
        //联通账单
        if($type=='liantong'){
            if($step==1){
                Session::delete('liantong_report');
            }
            $uid = Session::get('uuid');
            $tmpname = $_FILES['excel_file']['name'];
            $filename = $uid.'_'.$type.$step.'.'.explode('.',$tmpname)[1];
            if(is_uploaded_file($_FILES['excel_file']['tmp_name'])){                                            //判断是否是上传文件
                //unlink($_FILES['file1']['tmp_name']);
                move_uploaded_file($_FILES['excel_file']['tmp_name'], $path.$filename);     //将缓存文件移动到指定位置
            }

            $tmp_data = Session::get('liantong_report');
            if(empty($tmp_data)){
                $tmp_data = [$filename];
                Session::set('liantong_report',$tmp_data);
            }else{
                $tmp_data[] = $filename;
                Session::set('liantong_report',$tmp_data);
            }

        }
    }
}
