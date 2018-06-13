<?php
namespace app\qdhk\controller;

use app\base\controller\Base as ControllerBase;
use app\base\model\PhoneUser as PhoneUserModel;
class Phone extends ControllerBase
{
    public function search(){
        return $this->fetch();
    }

    /*
     * 电话名录查询页面
     * */
    public function search_phone(){
        $phoneuser_model = new PhoneUserModel();
        $res = $phoneuser_model->search();
        return array(
            'draw'=>(int)input('draw'),
            'auth_group'=>$res['data'],
            'count'=>$res['count']
        );
    }

    /*
     * 电话名录添加表单
     * */
    public function add_phone(){
        return $this->fetch();
    }

    /*
     * 电话名录编辑表单
     * */
    public function edit_phone(){
        $params = $this->request->get();
        if(!$params['id']){
            return false;
        }
        $phoneuser_model = new PhoneUserModel();
        $data = $phoneuser_model->getPhoneUsrById($params['id'])->toArray();
        $this->assign('user_info',$data);
        return $this->fetch();
    }

    /*
     * 电话名录添加操作
     * */
    public function add_action(){
        if($this->request->isAjax()){
            $params = $this->request->post();
            if(!$params['telephone'] || !$params['depart'] || !$params['service']){
                self::showReturnCode(500,'缺少必要信息');
            }
            $data = array(
                'number'=>$params['telephone'],
                'username'=>$params['user_name'],
                'service'=>$params['service'],
                'department'=>$params['depart']
            );

            $phoneuser_model = new PhoneUserModel();
            $res = $phoneuser_model->add($data);
            return $res;
        }else{
            return false;
        }
    }

    /*
     * 电话名录编辑操作
     * */
    public function edit_action(){
        if($this->request->isAjax()){
            $params = $this->request->post();
            if(!$params['id'] || !$params['telephone'] || !$params['depart'] || !$params['service']){
                self::showReturnCode(500,'缺少必要信息');
            }
            $data = array(
                'id'=>$params['id'],
                'number'=>$params['telephone'],
                'username'=>$params['user_name'],
                'service'=>$params['service'],
                'department'=>$params['depart']
            );

            $phoneuser_model = new PhoneUserModel();
            $res = $phoneuser_model->edit($data);
            return $res;
        }else{
            return false;
        }
    }

    /*
     * 电话名录删除操作
     * */
    public function del_action(){
        if($this->request->isAjax()){
            $params = $this->request->post();
            if(!$params['id']){
                self::showReturnCode(500,'缺少必要信息');
            }
            $phoneuser_model = new PhoneUserModel();
            $res = $phoneuser_model->del($params['id']);
            return $res;
        }else{
            return false;
        }
    }
}