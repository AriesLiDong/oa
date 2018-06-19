<?php
namespace app\report\controller;
use \app\base\controller\Base as ControllerBase;
use think\Session;
vendor("PHPExcel.PHPExcel");

class Excel extends ControllerBase
{
    public function liantong_report(){
        $phpexcel = new \PHPExcel();
        print_r($phpexcel);exit;
    }
}
