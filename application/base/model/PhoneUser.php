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
        $user = PhoneUser::all($id);
        return $user;
    }

    public function search(){
        $phoneuser = $this->limit(10)->order('id','asc')->select()->toArray();
        $count = $this->count();
//        $phoneuser = PhoneUser::all(function($query){
//            $query->limit(10)->order('id', 'asc');
//        });
        return ['data'=>$phoneuser,'count'=>$count];
    }
}