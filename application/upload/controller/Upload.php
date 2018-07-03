<?php
namespace app\upload\controller;
use \app\base\controller\Base as ControllerBase;
use think\Session;
use think\Config;

class Upload extends ControllerBase
{
    public function up(){
//        set_time_limit(0);
        $path = Config::get('upload_path');
        $type = input('type');//上传功能类型
        $step = input('step');//上传步数
        //联通账单
        if($type=='liantong'){
            if($step==1){
                Session::delete('liantong_report');
            }
            $uid = Session::get('uuid');
            $tmpname = $_FILES['excel_file']['name'];
            $tail = explode('.',$tmpname);
            $count = count($tail);
            $filename = $uid.'_'.$type.$step.'.'.$tail[$count-1];
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

    public function down(){
        $file1 = Session::get('liantong_file1');
//        $file2 = Session::get('liantong_file2');
//        $zip = new ZipArchive();
        $down_path = Config::get('down_path');
        if( empty($file1) || empty($file2) ){
            $this->error('文件不能为空');
        }
        $file = fopen ( $down_path.$file1, "rb" );
//        $file2 = fopen($down_path.$file2,"rb");
        //告诉浏览器这是一个文件流格式的文件
        Header ( "Content-type: application/octet-stream" );
        //请求范围的度量单位
        Header ( "Accept-Ranges: bytes" );
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header ( "Accept-Length: " . filesize (  $down_path.$file1 ) );
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header ( "Content-Disposition: attachment; filename=" . $file1 );

        //读取文件内容并直接输出到浏览器
        echo fread ( $file, filesize ( $down_path. $file1 ) );
        fclose ( $file );
        exit ();
    }
}
