<?php
namespace app\base\model;
use think\Model;

class PhoneUser extends Model
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    public function getPhoneUsrById($id)
    {
        $user = PhoneUser::get($id);
        return $user;
    }

    public function search($start=null,$limit=null,$sort=[]){
        $phoneuser = $this->limit($start,$limit)->order($sort['name'],$sort['dir'])->select()->toArray();
        $count = $this->count();

        return ['data'=>$phoneuser,'count'=>$count];
    }

    public function add($data){
        $res = $this->insert($data);
        return $res;
    }

    public function add_m($data){
        $res = $this->insert($data);
        return $res;
    }

    public function edit($data){
        $res = $this->update($data);
        return $res;
    }

    public function del($data){
        $map = array('id'=>$data);
        $res = $this->where($map)->delete();
        return $res;
    }
}