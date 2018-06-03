<?php
namespace app\qdhk\controller;

use app\base\controller\Base as ControllerBase;
use app\base\model\PhoneUser as PhoneUserModel;
class Phone extends ControllerBase
{
    public function search(){
        return $this->fetch();
    }

    public function search_phone(){
        $phoneuser_model = new PhoneUserModel();
        $res = $phoneuser_model->search();
        return array(
            'draw'=>(int)input('draw'),
            'auth_group'=>$res['data'],
            'count'=>$res['count']
        );
    }
}