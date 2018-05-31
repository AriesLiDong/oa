<?php
namespace app\qdhk\controller;

use app\base\controller\Base as ControllerBase;
class Phone extends ControllerBase
{
    public function search(){
        return $this->fetch();
    }
}