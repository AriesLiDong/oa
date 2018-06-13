<?php

namespace app\push\controller;

use think\worker\Server;
use think\Log;
class Worker extends Server
{
    protected $socket = 'websocket://www.project.com:2346';

    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data)
    {
        $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");

        fwrite($myfile, $data);
        fclose($myfile);
        // return $data;
        $filepath = 'D:\PHPstudy\PHPTutorial\WWW\oa\public\123';
        move_uploaded_file($data,$filepath.".png");
        $connection->send(333);
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {

    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {
        
    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {

    }
}
